<?php
    namespace Entities;

    require_once __DIR__ . "/../vendor/autoload.php";

    class House {
        public function __construct (
            private int $id,
            public string $title,
            public string $city,
            public string $address,
            public string $description,
            public int $total_rooms,
            public int $max_guests,
            public float $price,
            public array $images,
            private int $user_id
        ){}

        public static function HouseFromArray(array $house) : House{
            return new self(
                $house["id"],
                $house["title"],
                $house["city"],
                $house["address"],
                $house["description"],
                $house["total_rooms"],
                $house["max_guests"],
                $house["price"],
                $house["images"],
                $house["owner"],
            );
        }

        public function get_id () : int{
            return $this->id;
        }

        public function set_id (int $id) : void{
            if ($id > 0) $this->id = $id;
        }

        public function get_user_id () : int {
            return $this->user_id;
        }
    }