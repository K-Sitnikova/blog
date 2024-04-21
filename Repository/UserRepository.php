<?php
include_once("../Models/User.php");
class UserRepository
{
    protected $connection = null;

    function __construct() {
        $this->connectDb();
    }
    private function connectDb()
    {
        try {
            $this->connection = new mysqli("localhost", "root", "", "blog");
            if(mysqli_connect_errno()) {
                throw new PDOException('cannot connect to MySQL: ' . mysqli_connect_error());
            }
        } catch(PDOException $e) {
            throw new PDOException('cannot connect to MySQL: ' . $e->getMessage());
        }
    }


    private function closeConnection($mysqli)
    {
        $mysqli->close();
    }

    public function getUsers()
    {
        $connection = $this->connection;
        $query = $connection->prepare("SELECT * FROM users");
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_all();
    }

    public function getUser($id)
    {
        $connection = $this->connection;
        $query = $connection->prepare("SELECT * FROM users WHERE id = ?");
        $query->bind_param("s", $id);
        $query->execute();
        $result = $query->get_result();
        $result_map = $result->fetch_assoc();
        if($result_map) {
            return $this->mapToUser($result_map);
        }
        return null;
    }

    private function mapToUser($assoc)
    {
        $newUser = new User();
        $newUser->id = $assoc["id"];
        $newUser->name = $assoc["name"];
        $newUser->email = $assoc["email"];
        $newUser->surname = $assoc["surname"];
        $newUser->birthday = $assoc["birthday"];
        $newUser->nickname = $assoc["nickname"];
        return $newUser;

    }

    public function addUser($user)
    {
        $connection = $this->connection;
        $id = uniqid();
        $query = $connection->prepare("INSERT INTO users (id, name, surname, birthday, nickname, email) VALUES (?, ?, ?, ?, ?, ?)");
        $query->bind_param("ssssss", $id, $user->name, $user->surname, $user->birthday, $user->nickname, $user->email);
        $query->execute();
        $user->id = $id;
        return $user;

    }

    public function deleteUser($id)
    {
        $connection = $this->connection;
        $query = $connection->prepare("DELETE FROM users WHERE id = ?");
        $query->bind_param("s", $id);
        $query->execute();
    }

    public function updateUser($user)
    {
        $oldUser = $this->getUser($user->id);
        foreach ($user as $key => $value) {
            if(!empty($value)) {
                $oldUser->$key = $value;
            }
        }
        $connection = $this->connection;
        $query = $connection->prepare("UPDATE users SET name = ?, surname = ?, birthday = ?, nickname = ?, email=? WHERE id = ?");
        $query->bind_param("ssssss", $oldUser->name, $oldUser->surname, $oldUser->birthday, $oldUser->nickname, $oldUser->email, $oldUser->id);
        $query->execute();
        return $user;
    }
}