<?php
    namespace Repositories\Interfaces\UserRepositoryInterface;

    require_once __DIR__ . "/../vendor/autoload.php";

    use Entities\User;

    interface UserRepositoryInterface {
        public static function Find(int $id) : ?User;
        public static function FindByEmail(string $email) : ?User;
        public function save(User $user);
    }