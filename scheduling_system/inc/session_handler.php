<?php
require_once __DIR__ . '/db_pgsql.php';

if ($pdo) {
    try {
        if ($isPostgres) {
            $pdo->exec("CREATE TABLE IF NOT EXISTS php_sessions (
                sess_id  VARCHAR(128) PRIMARY KEY,
                sess_data TEXT NOT NULL DEFAULT '',
                sess_time BIGINT NOT NULL DEFAULT 0
            )");
        } else {
            $pdo->exec("CREATE TABLE IF NOT EXISTS php_sessions (
                sess_id   VARCHAR(128) PRIMARY KEY,
                sess_data TEXT NOT NULL DEFAULT '',
                sess_time BIGINT NOT NULL DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        }
    } catch (PDOException $e) {}

    $handler = new class($pdo, $isPostgres) implements SessionHandlerInterface {
        public function __construct(private PDO $pdo, private bool $pg) {}
        public function open(string $a, string $b): bool { return true; }
        public function close(): bool { return true; }

        public function read(string $id): string|false {
            $lt = (int)(ini_get('session.gc_maxlifetime') ?: 1440);
            $s  = $this->pdo->prepare(
                "SELECT sess_data FROM php_sessions WHERE sess_id=? AND sess_time>?"
            );
            $s->execute([$id, time() - $lt]);
            $r = $s->fetch();
            return $r ? $r['sess_data'] : '';
        }

        public function write(string $id, string $data): bool {
            $sql = $this->pg
                ? "INSERT INTO php_sessions(sess_id,sess_data,sess_time) VALUES(?,?,?)
                   ON CONFLICT(sess_id) DO UPDATE SET sess_data=EXCLUDED.sess_data,sess_time=EXCLUDED.sess_time"
                : "REPLACE INTO php_sessions(sess_id,sess_data,sess_time) VALUES(?,?,?)";
            return $this->pdo->prepare($sql)->execute([$id, $data, time()]);
        }

        public function destroy(string $id): bool {
            return $this->pdo->prepare("DELETE FROM php_sessions WHERE sess_id=?")->execute([$id]);
        }

        public function gc(int $max): int|false {
            $s = $this->pdo->prepare("DELETE FROM php_sessions WHERE sess_time<?");
            $s->execute([time() - $max]);
            return $s->rowCount();
        }
    };

    session_set_save_handler($handler, true);
}

// Secure session cookies on HTTPS
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    ini_set('session.cookie_secure', '1');
}
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Lax');
