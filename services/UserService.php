<?php
    namespace Services;

    require_once __DIR__ . "/../vendor/autoload.php";

    use Repositories\Interfaces\UserRepositoryInterface;
    use Entities\User;

    class UserService {
        public function __construct(
            private UserRepositoryInterface $repo
        ){}

        public function register (string $first_name, string $last_name, string $email, string $password) : array{
            $errors = [];

            if ($this->repo->findByEmail($email)) $errors["email"] = "invalid email, email allready exists !";
            if (!$first_name) $errors["first_name"] = "invalid first name";
            if (!$last_name) $errors["last_name"] = "invalid last name";
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors["email"] = "invalid email";
            if (strlen($password) < 8) $errors["password"] = "password must be at least 8 characters";

            if ($errors) return[
                "success" => false,
                "errors" => $errors
            ];

            $user = new User(
                0,
                $first_name,
                $last_name,
                $email,
                password_hash($password, PASSWORD_DEFAULT),
                false
            );

            $this->repo->save($user);

            return [
                "success" => true,
                "user" => $user
            ];
        }
    }