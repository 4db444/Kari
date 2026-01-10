<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] != "POST") header("Location: ./../../views/auth/login.php");

    require_once __DIR__ . "/../../vendor/autoload.php";

    use Core\Database;
    use Services\UserService;
    use Repositories\UserRepository;

    $User = new UserService(new UserRepository(Database::get_instance()));

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $result = $User->login($email, $password);

    if ($result["success"]){
        $_SESSION["user"] = $result["user"];
        header("location: ./../../views/house/houses.php");
    }else {
        $_SESSION["error"] = $result["error"];
        header("location: ./../../views/auth/login.php");
    }