<?php
$host = "localhost";
$user = "forza2025";  // <-- SIN *
$pass = "Ficopwd.18";
$db   = "FORZA";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // ✅ Usa error_log en lugar de echo para debug
    error_log("✅ Conexión exitosa a la base de datos FORZA");

} catch (PDOException $e) {
    error_log("❌ Error de conexión: " . $e->getMessage());
    die("Lo sentimos, error al conectar con la base de datos.");
}
?>