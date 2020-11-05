<?php


namespace App\Model;


class UserManager extends AbstractManager
{
    const TABLE = 'user' ;

    public function __construct()
    {
        parent::__construct(self::TABLE);
    }

    /*Code Manu
     * public function createUser($userData)
    {
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . " (pseudo, password, email) " );
        $statement->bindValue(':pseudo', )
        ...
    }*/

    public function selectOneByPseudo(string $pseudo): array
    {
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE pseudo= :pseudo");
        $statement->bindValue(':pseudo', $pseudo, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetchAll();
    }
}

