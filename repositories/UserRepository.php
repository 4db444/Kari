<?php
    namespace Repositories;

    require_once __DIR__ . "/../vendor/autoload.php";

    use Repositories\Interfaces\UserRepositoryInterface;
    use Core\Database;
    use Entities\User;
    use \PDO;

    class UserRepository implements UserRepositoryInterface {
        public function __construct(private PDO $pdo){}

        public function find(int $id) : ?User{
            $find_statment = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");

            $find_statment->execute([
                ":id" => $id
            ]);

            $user = $find_statment->fetch(PDO::FETCH_ASSOC);

            return $user ? User::UserFromArray($user) : NULL;
        }

        public function findByEmail(string $email) : ?User{
            $find_statment = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");

            $find_statment->execute([
                ":email" => $email
            ]);

            $user = $find_statment->fetch(PDO::FETCH_ASSOC);

            return $user ? User::UserFromArray($user) : NULL;
        }

        public function save(User $user){
            // new user
            if ($user->id === 0){
                $insert_statment = $this->pdo->prepare("
                    INSERT INTO users(first_name, last_name, email, password)
                    VALUES (:first_name, :last_name, :email, :password)
                ");

                $insert_statment->execute([
                    ":first_name" => $user->first_name,
                    ":last_name" => $user->last_name,
                    ":email" => $user->email,
                    ":password" => password_hash($user->password, PASSWORD_DEFAULT),
                ]);

                $user->id = $this->pdo->lastInsertId();
            }
            // update user
            else {
                $update_statment = $this->pdo->prepare("
                    UPDATE users
                    set 
                    SEt first_name = :first_name, last_name = :last_name, email = :email, password = :password)
                ");
    
                $update_statment->execute([
                    ":first_name" => $user->first_name,
                    ":last_name" => $user->last_name,
                    ":email" => $user->email,
                    ":password" => password_hash($user->password, PASSWORD_DEFAULT),
                ]);
            }
        }
    }