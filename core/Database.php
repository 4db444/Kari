<?php
    namespace Core\Database;

    use \PDO;

    $db_config = require_once __DIR__ . "/../config/db.php";

    class Database {
        private static ?Database $instance = NULL;
        private static ?PDO $connection = NULL;
        private function __construct(){
            global $db_config;
            self::$connection = new PDO("{$db_config['driver']}:host={$db_config['host']};dbname={$db_config['dbname']}", $db_config["user"], $db_config["password"]);
        }

        public static function get_instance(){
            if(!self::$instance) self::$instance = new self();
            return self::$connection;
        }

        private function __clone(){}

        public function __wakeup(){
            throw new Exception("this is singletone class ;)");
        }
    }