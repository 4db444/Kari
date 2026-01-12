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
            private string $status,
            private ?int $rating
        ){}

        public function set_id(int $id): void {
            $this->id = $id;
        }

        public function set_status(string $status): void {
            $this->status = $status;
        }

        public function get_user_id(): int { 
            return $this->user_id; 
        }

        public function get_house_id(): int {
            return $this->house_id;
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
                $reservation["status"],
                NULL
            );
        }
    }