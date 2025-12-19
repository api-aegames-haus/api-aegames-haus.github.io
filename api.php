<?php
header('Content-Type: text/plain');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Получаем параметры
$nickname = $_GET['nickname'] ?? '';
$password = $_GET['password'] ?? '';

// Проверяем наличие параметров
if (empty($nickname) || empty($password)) {
    echo '{auth=error,e=incorrectnicknameorpassword}';
    exit;
}

// Путь к файлу с аккаунтами
$accountsFile = 'accounts.txt';

// Проверяем существование файла
if (!file_exists($accountsFile)) {
    echo '{auth=error,e=404}';
    exit;
}

// Читаем файл
$accountsContent = file_get_contents($accountsFile);
if ($accountsContent === false) {
    echo '{auth=error,e=404}';
    exit;
}

// Разбиваем на строки
$lines = explode("\n", $accountsContent);
$found = false;

// Ищем аккаунт
foreach ($lines as $line) {
    $line = trim($line);
    if (empty($line)) continue;
    
    // Проверяем формат username:password
    $parts = explode(':', $line, 2);
    if (count($parts) === 2) {
        $storedNickname = trim($parts[0]);
        $storedPassword = trim($parts[1]);
        
        if ($storedNickname === $nickname && $storedPassword === $password) {
            $found = true;
            break;
        }
    }
}

// Возвращаем результат
if ($found) {
    echo '{auth=success,nickname="' . $nickname . '",password="' . $password . '"}';
} else {
    echo '{auth=error,e=incorrectnicknameorpassword}';
}
