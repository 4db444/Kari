<?php
    namespace Repositories\Interfaces;

    require_once __DIR__ . "/../../vendor/autoload.php";

    use Entities\House;
    use Entities\User;

    interface HouseRepositoryInterface {
        public function find(int $id) : ?House;
        public function save(House $house, array $existing_images);
        public function owner (int $house_id) : User;
        public function getHouseByUser(int $user_id);
        public function getAllHouses(int $user_id, int $page);
        public function destroy(int $house_id);
    }