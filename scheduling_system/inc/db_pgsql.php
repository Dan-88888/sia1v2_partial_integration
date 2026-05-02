<?php
// Unified DB connection: PostgreSQL (Neon) in production, MySQL locally
function createPDO(): PDO {
    $url = getenv('POSTGRES_URL') ?: getenv('DATABASE_URL') ?: getenv('NEON_DATABASE_URL');
    if ($url) {
        $p = parse_url($url);
        $host = $p['host'];
        $port = $p['port'] ?? 5432;
        $db   = ltrim($p['path'], '/');
        $user = $p['user'];
        $pass = $p['pass'];
        $dsn  = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";
        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return new PDO(
        "mysql:host=localhost;dbname=university_db;charset=utf8",
        "root", "",
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
}

try {
    $pdo = createPDO();
    $isPostgres = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'pgsql';
} catch (PDOException $e) {
    $pdo = null;
    $isPostgres = false;
    $db_error = $e->getMessage();
}
