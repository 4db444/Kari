<?php
    namespace Repositories\Interfaces;

    require_once __DIR__ . "/../../vendor/autoload.php";

    use Entities\Reservation;

    interface ReservationRepositoryInterface {
        public function is_available (int $house_id, string $start_date, string $end_date) : bool;
        public function find (int $reservation_id) : ?Reservation;
        public function save (Reservation $reservation);
        public function cancel (Reservation $reservation);
        public function getReservationsByUser (int $user_id) : array;
    }