<?php
// config/database.php
define('BASE_PATH', __DIR__ . '/../..');

$host = 'localhost';
$dbname = 'u413819793_eventofpm';
$username = 'u413819793_fpm';
$password = 'g8R?Q7a!';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
