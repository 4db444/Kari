<?php
    namespace Entities;

    class User {
        public function __construct(
            private int $id,
            public string $first_name,
            public string $last_name,
            public string $email,
            private string $password,
            public bool $is_admin
        ){}

        public static function UserFromArray (array $user){
            return new self(
                $user["id"],
                $user["first_name"],
                $user["last_name"],
                $user["email"],
                $user["password"],
                $user["is_admin"]
            );
        }

        public function set_id (int $id) : void{
            if ($id > 0) $this->id = $id;
        }

        public function get_id () : int{
            return $this->id;
        }

        public function get_password () : string{
            return $this->password;
        }

        public function verify_password(string $password) : bool
        {
            return password_verify($password, $this->password);
        }
    }