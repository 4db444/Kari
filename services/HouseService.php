<?php
    namespace Services;

    require_once __DIR__ . "/../vendor/autoload.php";

    use Repositories\Interfaces\HouseRepositoryInterface;
    use Repositories\Interfaces\UserRepositoryInterface;
    use Entities\House;
    use Entities\User;


    class HouseService {
        public function __construct(
            private HouseRepositoryInterface $house_repo,
            private UserRepositoryInterface $user_repo
        ){}

        public function create (House $house, array $existing_images) : array
        {
            $errors = [];

            if (!strlen($house->title)) $errors["title"] = "The Title Is Required !";
            if (!strlen($house->city)) $errors["city"] = "The City Is Required !";
            if (!strlen($house->address)) $errors["address"] = "The Address Is Required !";
            if ($house->total_rooms <= 0) $errors["total_rooms"] = "invalid total_rooms !";
            if ($house->max_guests <= 0)$errors["max_guests"] = "Invalid max guests !";
            if ($house->price <= 0) $errors["price"] = "Invalide price !";

            if ($errors) return [
                "success" => false,
                "errors" => $errors
            ];

            if (!$this->user_repo->find($house->get_user_id())) return [
                "success" => false,
                "errors" => [
                    "user" => "Invalid user for association !"
                ]
            ];

            $this->house_repo->save($house, $existing_images);

            return [
                "success" => true,
                "house" => $house
            ];
        }

        public function find (int $house_id) : ?House {
            return $this->house_repo->find($house_id);
        }

        public function getOwner (int $house_id) : User {
            return $this->house_repo->owner($house_id);
        }

        public function getMyHouses(int $user_id){
            return $this->house_repo->getHouseByUser($user_id);
        }

        public function getAllHouses (int $user_id, int $page){
            return $this->house_repo->getAllHouses($user_id, $page);
        }

        public function delete (User $user, House $house){
            if($user->is_admin || $house->get_user_id() === $user->get_id()){
                $this->house_repo->destroy($house->get_id());
            };
        }
    }