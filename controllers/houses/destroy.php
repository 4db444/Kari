<?php
    if ($_SERVER["REQUEST_METHOD"] != "POST") header("Location: ./../../views/house/myhouses.php");
    
    require_once __DIR__ . "/../../vendor/autoload.php";
    require_once __DIR__ . "/../../config/path.php";
    session_start();
    
    use Core\Database;
    use Services\HouseService;
    use Repositories\HouseRepository;
    use Repositories\UserRepository;

    $user = $_SESSION["user"];
    $House = new HouseService(new HouseRepository(Database::get_instance()), new UserRepository(Database::get_instance()));

    $house_id = $_POST["id"];

    

    $House->delete($user, $House->find($house_id));

    header("location: " . BASE_URL . "/views/house/myhouses.php");