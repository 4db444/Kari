<?php
    namespace Entities;

    require_once __DIR__ . "/../vendor/autoload.php";

    class House {
        public function __construct (
            private int $id,
            public string $city,
            public string $address,
            public int $room_number,
            public int $max_guests,
            public float $price,
            private ?int $user_id
        ){}

        public static function HouseFromArray(array $house) : House{
            return new self(
                $house["id"],
                $house["city"],
                $house["address"],
                $house["room_number"],
                $house["max_guests"],
                $house["price"],
                $house["user_id"],
            );
        }

        public function get_id () : int{
            return $this->id;
        }

        public function set_id (int $id) : void{
            if ($id > 0) $this->id = $id;
        }
    }

    echo House::class;