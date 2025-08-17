<?php
require __DIR__ . '/../vendor/autoload.php';

use App\LoggerFactory;
use Monolog\Logger;

$logger = LoggerFactory::createLogger();

$username = $_POST['username'] ?? '';
$action = $_POST['action'] ?? '';
$level = $_POST['level'] ?? 'info';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($username) || empty($action) || empty($level)) {
        $logger->warning("Incomplete form submission.");
        $message = "Please fill in all fields.";
    } else {
        $logMessage = "User '{$username}' performed action: '{$action}'";
        switch (strtolower($level)) {
            case 'info':
                $logger->info($logMessage);
                break;
            case 'warning':
                $logger->warning($logMessage);
                break;
            case 'error':
                $logger->error($logMessage);
                break;
            default:
                $logger->notice($logMessage);
                break;
        }
        $message = "Log ({$level}) recorded for {$username}.";
    }
}

$logContent = file_exists(__DIR__ . '/../logs/webapp.log') ? file_get_contents(__DIR__ . '/../logs/webapp.log') : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP Logger Dashboard</title>
    <style>
        body { font-family: Arial; margin: 30px; }
        textarea { width: 100%; height: 300px; background: #f9f9f9; font-family: monospace; padding: 10px; }
        .log-box { border: 1px solid #ccc; margin-top: 20px; }
        .success { color: green; }
    </style>
</head>
<body>
    <h2>PHP Logger Dashboard</h2>
    <form method="post">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label>Action:</label><br>
        <input type="text" name="action" required><br><br>

        <label>Log Level:</label><br>
        <select name="level">
            <option value="info">INFO</option>
            <option value="warning">WARNING</option>
            <option value="error">ERROR</option>
        </select><br><br>

        <input type="submit" value="Submit Log">
    </form>

    <p class="success"><?php echo $message ?? ''; ?></p>

    <div class="log-box">
        <h3>📄 Log Output (webapp.log)</h3>
        <textarea readonly><?php echo htmlspecialchars($logContent); ?></textarea>
    </div>
</body>
</html>
