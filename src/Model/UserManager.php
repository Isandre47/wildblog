<?php

namespace Model;

class UserManager extends AbstractManager
{
    const TABLE = 'user';

    public function __construct(\PDO $pdo)

    {
        parent::__construct(self::TABLE, $pdo);
    }



    public function selectAllUsers(): array
    {
        $this->pdo->query("SET lc_time_names = 'fr_FR'");
        return $this->pdo->query('SELECT id, firstname, lastname, email, DATE_FORMAT(registered, "%e %M %Y") AS registered, status FROM user ORDER BY lastname', \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }

    public function userDelete(int $id): int
    {
        $statement = $this->pdo->prepare("DELETE FROM user WHERE id=:id");
        $statement->bindValue('id', $id, \PDO::PARAM_INT);
        $statement->execute();
        return header('Location: /admin/users');
    }


    public function suscribe(User $user)
    {
        $addUser = $this->pdo->prepare("INSERT INTO $this->table (firstname, lastname, email, pass, registered, status) VALUES (:firstname,:lastname, :email, :password, NOW(),'user')");
        $addUser->bindValue(':firstname', $user->getFirstname(), \PDO::PARAM_STR);
        $addUser->bindValue(':lastname', $user->getLastname(), \PDO::PARAM_STR);
        $addUser->bindValue(':email', $user->getEmail(), \PDO::PARAM_STR);
        $addUser->bindValue(':password', password_hash($user->getPass(), PASSWORD_DEFAULT), \PDO::PARAM_STR);
        return $addUser->execute();
    }
    public function userAdd(User $user)
    {
        $statement = $this->pdo->prepare("INSERT INTO $this->table (firstname, lastname, email, pass, registered, status)
VALUES (:firstname, :lastname, :email, :pass, DATE(NOW()), :status)");
        $statement->bindValue(':firstname', $user->getFirstname(),\PDO::PARAM_STR);
        $statement->bindValue(':lastname', $user->getLastname(), \PDO::PARAM_STR);
        $statement->bindValue(':email', $user->getEmail(), \PDO::PARAM_STR);
        $statement->bindValue(':pass', password_hash($user->getPass(), PASSWORD_DEFAULT), \PDO::PARAM_STR);
        $statement->bindValue(':status', $user->getStatus(), \PDO::PARAM_STR);

        return $statement->execute();


    }
    public function count()
    {
        $numbersUsers = $this->pdo->query("SELECT COUNT(id) AS Numbers FROM $this->table")->fetchColumn();
        return $numbersUsers;

    }

    public function existUser($email) {
        $query = $this->pdo->prepare("SELECT * FROM $this->table WHERE email = :email");
        $query->execute(array(':email' => $email));
        $query->setFetchMode(\PDO::FETCH_CLASS, 'Model\User');
        $res =  $query->fetch();
        return $res;
    }


    public function loginUser($email)
    {
        $reqUser = $this->pdo->prepare("SELECT * FROM $this->table WHERE email = :email");
        $reqUser->execute(array(':email' => $email));
        $reqUser->setFetchMode(\PDO::FETCH_CLASS, 'Model\User');
        $res =  $reqUser->fetch();
        return $res;
    }

    public function selectUsersForIndex(): array
    {
        return $this->pdo->query('SELECT * FROM user ORDER BY registered DESC LIMIT 3', \PDO::FETCH_CLASS, $this->className)->fetchAll();
    }

}
