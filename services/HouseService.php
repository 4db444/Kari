<?php
    namespace Services;

    require_once __DIR__ . "/../vendor/autoload.php";

    use Repositories\Interfaces\HouseRepositoryInterface;
    use Repositories\Interfaces\UserRepositoryInterface;
    use Entities\House;
    use Entities\User;


    class HouseService {
        public function __construct(
            private HouseRpositoryInterface $house_repo,
            private UserRepositoryInterface $user_repo
        ){}

        public function create (
            string $city,
            string $address,
            int $total_rooms,
            int $max_guests,
            float $price,
            array $images,
            int $user_id
            ) : array
        {
            $errors = [];

            if (!strlen($city)) $errors["city"] = "The City Is Required !";
            if (!strlen($address)) $errors["address"] = "The Address Is Required !";
            if ($total_rooms <= 0) $errors["total_rooms"] = "invalid total_rooms !";
            if ($max_guests <= 0)$errors["max_guests"] = "Invalid max guests !";
            if ($price <= 0) $errors["price"] = "Invalide price !";

            if ($errors) return [
                "success" => false,
                "errors" => $errors
            ];

            if (!$this->user_repo->find($user_id)) return [
                "success" => false,
                "errors" => [
                    "user" => "Invalid user for association !"
                ]
            ];

            $house = new House(
                0,
                $city,
                $address,
                $total_rooms,
                $max_guests,
                $price,
                $images,
                $user_id
            );

            $this->house_repo->save($house);

            return [
                "success" => true,
                "house" => $house
            ];
        }

        public function delete (User $user, House $house){
            if($user->is_admin || $house->get_user_id() === $user->get_id()){
                $this->house_repo->destroy($house->get_id());
            };
        }
    }