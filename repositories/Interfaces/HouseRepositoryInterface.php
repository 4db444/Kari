<?php
    namespace Repositories\Interfaces;

    require_once __DIR__ . "/../../vendor/autoload.php";

    use Entities\House;

    interface HouserepositoryInterface {
        public function find(int $id) : ?House;
        public function save(House $house);
        public function destroy(int $house_id);
    }