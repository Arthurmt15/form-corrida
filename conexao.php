<?php
// Contexto: conexão PDO com MySQL (corrida_db). Ajuste host/user/pass se preciso.
$host = 'localhost';
$db   = 'corrida_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
  $pdo = new PDO($dsn, $user, $pass, $options);
  $db_ok = true;
} catch (PDOException $e) {
  // Contexto: sem MySQL o formulário ainda renderiza; salvar/listar exibem aviso.
  $pdo = null;
  $db_ok = false;
  $db_erro = $e->getMessage();
}
