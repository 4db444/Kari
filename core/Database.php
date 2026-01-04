<?php
    namespace Core;

    use \PDO;

    class Database {
        private static ?Database $instance = NULL;
        private static ?PDO $connection = NULL;
        private function __construct(){
            global $db_config;
            self::$connection = new PDO("mysql:host=localhost;dbname=kari", "root", "Brahim@444");
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