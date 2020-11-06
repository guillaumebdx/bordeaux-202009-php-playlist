<?php


namespace App\Model;


class UserManager extends AbstractManager
{
    const TABLE = 'user';

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }


    public function createUser($userData)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . "(`pseudo`, `password`, `email`) VALUES (:pseudo, :email, :password)");
        $statement->bindValue(':pseudo', $userData['pseudo'], \PDO::PARAM_STR);
        $statement->bindValue(':email', $userData['email'], \PDO::PARAM_STR);
        $statement->bindValue(':password', $userData['password'], \PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function selectOneByPseudo(string $pseudo)
    {
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE pseudo= :pseudo");
        $statement->bindValue(':pseudo', $pseudo, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch();
    }
}

