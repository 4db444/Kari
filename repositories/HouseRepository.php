<?php
    namespace Repositories;

    require_once __DIR__ . "/../vendor/autoload.php";

    use Repositories\Interfaces\HouseRepositoryInterface;
    use Entities\House;
    use \PDO;

    class HouseRepository implements HouseRepositoryInterface {
        public function __construct(
            private PDO $pdo
        ){}

        public function find (int $id) : ?House {
            $find_statment = $this->pdo->prepare("SELECT * FROM houses WHERE id = :id");
            $find_statment->execute([
                ":id" => $id
            ]);

            $house = $find_statment->fetch(PDO::FETCH_ASSOC);

            if (!$house) return NULL;

            $images_statment = $this->pdo->prepare("SELECT src FROM images WHERE house_id = :house_id");

            $images_statment->execute([
                ":house_id" => $house["id"]
            ]);

            $house_images = $images_statment->fetchAll(PDO::FETCH_ASSOC);

            $house["images"] = $house_images;

            return House::HouseFromArray($house);
        }

        public function save (House $house) {
            if ($house->get_id() === 0){
                // new house
                $insert_house_statment = $this->pdo->prepare ("
                    INSERT INTO houses (city, address, total_rooms, max_guests, price, user_id)
                    VALUES (:city, :address, :total_rooms, :max_guests, :price, :user_id)
                ");

                $insert_house_statment->execute([
                    ":city" => $house->city,
                    ":address" => $house->address,
                    ":total_rooms" => $house->total_rooms,
                    ":max_guests" => $house->max_guests,
                    ":price" => $house->price,
                    ":user_id" => $house->get_user_id(),
                ]);

                $house->set_id($this->pdo->lastInsertId());

                foreach ($house->images as $img){
                    $insert_img_statment = $this->pdo->prepare("INSERT INTO images(src, house_id) VALUES (:src, :house_id)");

                    $insert_img_statment->execute([
                        ":src" => $img,
                        ":house_id" => $house->id
                    ]);
                }
            }else {
                // update existing house;
                $update_statment = $this->pdo->prepare("
                    UPDATE houses
                    SET city = :city, address = :address, total_rooms = :total_rooms, max_guests = :max_guests, price = :price
                    WHERE id = :id
                ");

                $update_statment->execute([
                    ":id" => $house->get_id(),
                    ":city" => $house->city,
                    ":address" => $house->address,
                    ":total_rooms" => $house->total_rooms,
                    ":max_guests" => $house->max_guests,
                    ":price" => $house->price
                ]);

                // delete all existing images :
                $delete_images_statment = $this->pdo->prepare("DELETE FROM images WHERE house_id = :house_id");

                $delete_images_statment->execute([
                    ":house_id" => $house->get_id()
                ]);

                foreach ($house->images as $img){
                    $insert_img_statment = $this->pdo->prepare("INSERT INTO images(src, house_id) VALUES (:src, :house_id)");

                    $insert_img_statment->execute([
                        ":src" => $img,
                        ":house_id" => $house->id
                    ]);
                }
            }
        }

        public function getHouseByUser(int $user_id){
            $houses_statment = $this->pdo->prepare("
                SELECT *
                FROM houses
                WHERE user_id = :user_id
            ");

            $houses_statment->execute([
                ":user_id" => $user_id
            ]);

            $houses = [];

            while ($house_record = $houses_statment->fetch(PDO::FETCH_ASSOC)){
                $images_statment = $this->pdo->prepare("
                    SELECT src
                    FROM images
                    WHERE house_id = :house_id
                ");

                $images_statment->execute([
                    ":house_id" => $house_record["id"]
                ]);

                $house_record["images"] = array_column(
                    $images_statment->fetchAll(PDO::FETCH_ASSOC),
                    "src"
                );

                $houses[] = House::HouseFromArray($house_record);
            }

            return $houses;
        }

        public function destroy (int $house_id) {
            $delete_statment = $this->pdo->prepare("DELETE FROM houses WHERE id = :house_id");

            $delete_statment->execute([
                ":house_id" => $house_id
            ]);
        }
    }