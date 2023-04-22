<?php
// Telegram bot token-5909661258
define('BOT_TOKEN', '5991588711:AAGHuQCpjWdvHgMgmTSMyBpskdvLfmgcgxg');

// Telegram chat ID
define('CHAT_ID', '859103562');

// FTP server credentials
$ftp_server = 'ftpupload.net';
$ftp_username = 'epiz_33872455';
$ftp_password = '0CVsPpPtF6kJ';
$ftp_port = 21;

// Remote file to download from FTP server
$remote_file = '/my_backup.backup';

// Connect to FTP server
$conn_id = ftp_connect($ftp_server, $ftp_port);
$login_result = ftp_login($conn_id, $ftp_username, $ftp_password);

// Get file size
$file_size = ftp_size($conn_id, $remote_file);

// Telegram message caption
$caption = "Backup file size: " . $file_size . " bytes\n";

// Telegram document filename
$document_name = basename($remote_file);

// Open a connection to the Telegram bot API
$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL => 'https://api.telegram.org/bot' . BOT_TOKEN . '/sendDocument',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => array(
        'chat_id' => CHAT_ID,
        'document' => new CURLFile('ftp://' . $ftp_username . ':' . $ftp_password . '@' . $ftp_server . $remote_file),
        'caption' => $caption,
        'disable_notification' => true
    )
));

// Send the document to Telegram
$result = curl_exec($ch);
if (!$result) {
    // Error message
    $message = "Error sending backup file to Telegram.";

    // Send message to Telegram
    sendTelegramMessage($message);
    echo $message;
} else {
    // Success message
    $message = "Backup file sent to Telegram.";

    // Send message to Telegram
    sendTelegramMessage($message);
    echo $message;
}

// Close the connection to the Telegram bot API
curl_close($ch);

// Close FTP connection
ftp_close($conn_id);

// Function to send message to Telegram
function sendTelegramMessage($message) {
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => 'https://api.telegram.org/bot' . BOT_TOKEN . '/sendMessage',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => array(
            'chat_id' => CHAT_ID,
            'text' => $message,
            'disable_notification' => true
        )
    ));
    curl_exec($ch);
    curl_close($ch);
}
?>
