<?php
    namespace Services;

    require_once __DIR__ . "/../vendor/autoload.php";

    use Repositories\Interfaces\ReservationRepositoryInterface;
    use Repositories\Interfaces\UserRepositoryInterface;
    use Repositories\Interfaces\HouseRepositoryInterface;

    use Entities\Reservation;
    use \DateTime;

    class ReservationService {
        public function __construct (
            private ReservationRepositoryInterface $reservation_repo,
            private UserRepositoryInterface $user_repo,
            private HouseRepositoryInterface $house_repo,
        ){} 

        public function book (int $user_id, int $house_id, string $start_date, string $end_date) :array {
            $errors = [];

            if (!$start_date || !$end_date) return [
                "success" => false,
                "errors" => ["dates are required"]
            ];
            
            $start_date_obj = new DateTime($start_date);
            $end_date_obj = new DateTime($end_date);

            if ($start_date_obj > $end_date_obj && $start_date_obj < (new DateTime())) return [
                "success" => false,
                "errors" => ["invalide dates, start date must be less than end date"]
            ];

            $user = $this->user_repo->find($user_id);

            if (!$user) return [
                "success" => false,
                "errors" => ["this user does not exists"]
            ];

            $house = $this->house_repo->find($house_id);

            if (!$house) return [
                'success' => false,
                "errors" => ["this house is no longer exists"]
            ];


            $is_available = $this->reservation_repo->is_available($house->get_id(), $start_date, $end_date);

            if (!$is_available) return [
                "success" => false,
                "errors" => ["the house is not availabe in this interval !"]
            ];

            $reservation = new Reservation(
                0,
                $user->get_id(),
                $house->get_id(),
                $start_date_obj->format("Y-m-d"),
                $end_date_obj->format("Y-m-d"),
                false,
                NULL
            );

            $this->reservation_repo->save($reservation);

            return [
                "success" => true,
                "reservation" => $reservation
            ];
        }

        public function cancel (Reservation $reservation){
            $this->reservation_repo->cancel($reservation);
        }
    }