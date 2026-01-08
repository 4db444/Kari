<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] != "POST") header("Location: ./../../views/auth/signup.php");

    require_once __DIR__ . "/../../vendor/autoload.php";

    use Core\Database;
    use Services\UserService;
    use Repositories\UserRepository;

    $User = new UserService(new UserRepository(Database::get_instance()));

    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $password_confirmation = $_POST["password_confirmation"];

    $result = $User->register($first_name, $last_name, $email, $password, $password_confirmation);

    if (!$result["success"]){
        $_SESSION["errors"] = $result["errors"];
        header("location: ./../../views/auth/signup.php");
    }else {
        header("location: ./../../views/auth/login.php");
    }