<?php
    namespace Repositories;

    require_once "/../vendor/autoload.php";

    use Repositories\Interfaces\ReservationRepositoryInterface;
    use Entities\Reservation;

    class ReservationRepository implements ReservationRepositoryInterface {

        public function __construct (
            private PDO $pdo
        ){}

        public function is_available (int $house_id, string $start_date, string $end_date) : bool
        {
            $check_availibility_statment = $this->pdo->prepare("
                SELECT *
                FROM reservations
                WHERE house_id = :house_id 
                AND ((`to` BETWEEN :start_date AND :end_date) or (`from` BETWEEN :start_date AND :end_date))
            ");



            $check_availibility_statment->execute([
                ":house_id" => $house_id,
                ":start_date" => $start_date,
                ":end_date" => $end_date
            ]);

            return $check_availibility_statment->fetch() ? false : true;
        }

        public function save (Reservation $reservation){
            $insert_statment = $this->pdo->prepare("
                INSERT INTO reservations (user_id, house_id, from, to, status)
                VALUES (:user_id, :house_id, :from, :to, :status)
            ");

            $insert_statment->execute([
                ":user_id" => $reservation->get_user_id(),
                ":house_id" => $reservation->get_house_id(),
                ":from" => $reservation->get_from_date(),
                ":to" => $reservation->get_to_date(),
                ":status" => false
            ]);

            $reservation->set_id($this->pdo->lastInsertId());
        }

        public function find (int $reservation_id) : ?Reservation{
            $find_statment = $this->pdo->prepare("
                SELECT *
                FROM reservations
                WHERE id = :id
            ");

            $find_statment->execute([
                ":id" => $reservation_id
            ]);

            $reservation = $find_statment->fetch(PDO::FETCH_ASSOC);

            return $reservation ? Reservation::ReservationFromArray($reservation) : NULL;
        }

        public function cancel (Reservation $reservation){
            $cancel_statment = $this->pdo->prepare("
                UPDATE reservations
                SET status = 'canceled'
                WHERE id = :reservation_id
            ");

            $cancel_statment->execute([
                ":reservation_id" => $reservation->get_id()
            ]);

            $reservation->set_is_canceled('canceled');
        }

        public function getReservationsByUser (int $user_id) : array{
            $statment = $this->pdo->prepare("
                SELECT *
                FROM reservations
                WHERE user_id = :user_id
            ");

            $statment->execute([
                ":user_id" => $user_id
            ]);

            $reservations = [];

            while ($reservation = $statment->fetch(PDO::FETCH_ASSOC)){
                $reservations[] = Reservation::ReservationFromArray($reservation);
            }

            return $reservations;
        }
    }