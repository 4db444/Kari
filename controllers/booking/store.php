<?php
    if ($_SERVER["REQUEST_METHOD"] != "POST") header("Location: ./../../views/auth/login.php");
    
    require_once __DIR__ . "/../../vendor/autoload.php";
    require_once __DIR__ . "/../../config/path.php";
    session_start();
    
    use Core\Database;
    use Services\ReservationService;
    use Repositories\HouseRepository;
    use Repositories\ReservationRepository;
    use Repositories\UserRepository;
    use Entities\House;

    $user = $_SESSION["user"];

    $ReservationSrv = new ReservationService(
        new ReservationRepository(Database::get_instance()),
        new UserRepository(Database::get_instance()),
        new HouseRepository(Database::get_instance()) 
    );

    $house_id = $_POST["house_id"];
    $start_date = $_POST["checkin"];
    $end_date = $_POST["checkout"];

    $result = $ReservationSrv->book($user->get_id(), $house_id, $start_date, $end_date);

    if(!$result["success"]){
        $_SESSION["errors"] = $result["errors"];
    }

    header("location: " . BASE_URL . "/views/house/details.php?id=" . $house_id);
