<?php
    namespace Repositories;

    require_once __DIR__ . "/../vendor/autoload.php";

    use Repositories\Interfaces\HouseRepositoryInterface;
    use Entities\House;
    use Entities\User;
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

            $house_images = array_column($images_statment->fetchAll(PDO::FETCH_ASSOC), "src");

            $house["images"] = $house_images;

            return House::HouseFromArray($house);
        }

        public function save (House $house, array $existing_images) {
            if ($house->get_id() === 0){
                // new house
                $insert_house_statment = $this->pdo->prepare ("
                    INSERT INTO houses (title, city, address, description, total_rooms, max_guests, price, owner)
                    VALUES (:title, :city, :address, :description, :total_rooms, :max_guests, :price, :owner)
                ");

                $insert_house_statment->execute([
                    ":title" => $house->title,
                    ":city" => $house->city,
                    ":address" => $house->address,
                    ":description" => $house->description,
                    ":total_rooms" => $house->total_rooms,
                    ":max_guests" => $house->max_guests,
                    ":price" => $house->price,
                    ":owner" => $house->get_user_id(),
                ]);

                $house->set_id($this->pdo->lastInsertId());

                foreach ($house->images as $img){
                    $insert_img_statment = $this->pdo->prepare("INSERT INTO images(src, house_id) VALUES (:src, :house_id)");

                    $insert_img_statment->execute([
                        ":src" => $img,
                        ":house_id" => $house->get_id()
                    ]);
                }
            }else {
                // update existing house;
                $update_statment = $this->pdo->prepare("
                    UPDATE houses
                    SET title = :title, city = :city, address = :address, description = :description, total_rooms = :total_rooms, max_guests = :max_guests, price = :price
                    WHERE id = :id
                ");

                $update_statment->execute([
                    ":id" => $house->get_id(),
                    ":title" => $house->title,
                    ":city" => $house->city,
                    ":address" => $house->address,
                    ":description" => $house->description,
                    ":total_rooms" => $house->total_rooms,
                    ":max_guests" => $house->max_guests,
                    ":price" => $house->price
                ]);

                // delete all existing images :
                foreach($existing_images as $existing_image){
                    $delete_image_statment = $this->pdo->prepare("DELETE FROM images WHERE house_id = :house_id AND src = :src");
    
                    $delete_image_statment->execute([
                        ":house_id" => $house->get_id(),
                        ":src" => $existing_image
                    ]);
                }

                foreach ($house->images as $img){
                    $insert_img_statment = $this->pdo->prepare("INSERT INTO images(src, house_id) VALUES (:src, :house_id)");

                    $insert_img_statment->execute([
                        ":src" => $img,
                        ":house_id" => $house->get_id()
                    ]);
                }
            }
        }

        public function owner (int $house_id) : User{
            $owner_statment = $this->pdo->prepare("
                SELECT *
                FROM users
                WHERE id = (SELECT owner FROM houses WHERE id = :id)
            ");

            $owner_statment->execute([
                ":id" => $house_id
            ]);

            return User::UserFromArray($owner_statment->fetch(PDO::FETCH_ASSOC));
        }

        public function getHouseByUser(int $user_id){
            $houses_statment = $this->pdo->prepare("
                SELECT *
                FROM houses
                WHERE owner = :owner
                ORDER BY id DESC
            ");

            $houses_statment->execute([
                ":owner" => $user_id
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

        public function getAllHouses(int $user_id, int $page){
            $houses_statment = $this->pdo->prepare("
                SELECT *
                FROM houses
                WHERE owner != :owner
                ORDER BY id DESC
                LIMIT 12
                OFFSET :offset
            ");

            $offset = ($page - 1) * 12;

            $houses_statment->bindParam(":owner", $user_id, PDO::PARAM_INT);
            $houses_statment->bindParam(":offset", $offset, PDO::PARAM_INT);


            $houses_statment->execute();

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