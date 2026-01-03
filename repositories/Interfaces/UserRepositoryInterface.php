<?php
    namespace Repositories\Interfaces;

    require_once __DIR__ . "/../../vendor/autoload.php";

    use Entities\User;

    interface UserRepositoryInterface {
        public function find(int $id) : ?User;
        public function findByEmail(string $email) : ?User;
        public function save(User $user);
    }