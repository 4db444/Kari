<?php
    if ($_SERVER["REQUEST_METHOD"] != "POST") header("Location: ./../../views/auth/login.php");
    
    require_once __DIR__ . "/../../vendor/autoload.php";
    require_once __DIR__ . "/../../config/path.php";
    session_start();
    
    use Core\Database;
    use Services\HouseService;
    use Repositories\HouseRepository;
    use Repositories\UserRepository;
    use Entities\House;

    $user = $_SESSION["user"];
    $House = new HouseService(new HouseRepository(Database::get_instance()), new UserRepository(Database::get_instance()));

    $id = $_POST["id"] ?? 0;
    $title = trim($_POST["title"]);
    $city = trim($_POST["city"]);
    $address = trim($_POST["address"]);
    $price = $_POST["price"];
    $total_rooms = $_POST["total_rooms"];
    $max_guests = $_POST["max_guests"];
    $description = trim($_POST["description"]);
    $existing_images = $_POST["existing_images"] ?? [];

    $images = [];

    if(isset($_FILES["images"])){
        $i = 0;
        foreach ($_FILES["images"]["tmp_name"] as $temp_path){
            $file_extension = pathinfo($_FILES["images"]["name"][$i++], PATHINFO_EXTENSION);
            $file_name =  time() . bin2hex(random_bytes(4)) . "." . $file_extension;
            move_uploaded_file($temp_path, __DIR__ . "/../../src/imgs/" . $file_name);
            $images[] = $file_name;
        }
    }

    $result = $House->create(new House(
        $id,
        $title, 
        $city,
        $address,
        $description,
        $total_rooms,
        $max_guests,
        $price,
        $images,
        $user->get_id()
    ), $existing_images);

    if (!$result["success"]){
        $_SESSION["errors"] = $result["errors"];
    }

    header ("location: " . BASE_URL . "/views/house/myhouses.php");