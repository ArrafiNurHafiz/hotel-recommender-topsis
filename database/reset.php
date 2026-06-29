<?php
/**
 * Reset database: drop tables, run migration, seed data.
 * Usage: php database/reset.php
 */
require_once __DIR__ . '/../config/database.php';
$cfg = require __DIR__ . '/../config/database.php';

try {
    $pdo = new PDO("mysql:host={$cfg['host']};charset={$cfg['charset']}", $cfg['username'], $cfg['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $pdo->exec("DROP DATABASE IF EXISTS `{$cfg['dbname']}`");
    $pdo->exec("CREATE DATABASE `{$cfg['dbname']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `{$cfg['dbname']}`");
    echo "Database {$cfg['dbname']} created.\n";
} catch (PDOException $e) {
    die("DB error: " . $e->getMessage() . "\n");
}

$sql = file_get_contents(__DIR__ . '/migration.sql');
// The migration SQL has CREATE DATABASE and USE statements; skip those since we already did it
$parts = array_filter(explode(';', $sql), fn($s) => !preg_match('/CREATE\s+DATABASE|USE\s+/i', trim($s)));
foreach ($parts as $stmt) {
    $stmt = trim($stmt);
    if ($stmt) {
        $pdo->exec($stmt);
    }
}
echo "Migration applied.\n";

// Seed
$pdo->exec("USE `{$cfg['dbname']}`");
// Reuse seed.php logic but with the existing PDO connection
require __DIR__ . '/seed.php';
echo "Seeding complete.\n";
