<?php
    namespace Entities;

    require_once __DIR__ . "/../vendor/autoload.php";

    class Reservation {
        public function __construct (
            private int $id,
            private int $user_id,
            private int $house_id,
            private string $from, 
            private string $to,
            private bool $is_canceled,
            private ?int $rating
        ){}

        public function set_id(int $id): void {
            $this->id = $id;
        }

        public function set_is_canceled(bool $status): void {
            $this->is_canceled = $status;
        }

        public function get_user_id(): int { 
            return $this->userId; 
        }

        public function get_house_id(): int {
            return $this->houseId;
        }

        public function get_from_date(): string {
            return $this->from; 
        }

        public function get_to_date(): string {
            return $this->to;
        }

        public static function ReservationFromArray(array $reservation){
            return new self(
                $reservation["id"],
                $reservation["user_id"],
                $reservation["house_id"],
                $reservation["from"],
                $reservation["to"],
                $reservation["is_canceled"],
                NULL
            );
        }
    }