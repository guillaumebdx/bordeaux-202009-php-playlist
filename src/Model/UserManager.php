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
        $statement = $this->pdo->prepare("INSERT INTO " . self::TABLE . "(`pseudo`, `password`, `email`) 
        VALUES (:pseudo, :password, :email)");
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

    public function selectOneByEmail(string $email)
    {
        $statement = $this->pdo->prepare("SELECT * FROM $this->table WHERE email= :email");
        $statement->bindValue(':email', $email, \PDO::PARAM_STR);
        $statement->execute();
        return $statement->fetch();
    }

    public function selectAllUsers()
    {
        $statement = $this->pdo->prepare("SELECT * FROM $this->table");
        $statement->execute();
        return $statement->fetchAll();
    }



    public function selectAllTracksByProfil($id)
    {
        $statement = $this->pdo->query("
            SELECT t.id, t.title, t.artist, t.url, t.nblike, t.user_id, u.pseudo, u.is_admin 
            FROM " . TrackManager::TABLE . " t JOIN " . self::TABLE . " u ON t.user_id=u.id 
            WHERE t.user_id = " . $id . " ORDER BY t.nblike DESC");
        return $statement->fetchAll();
    }

    public function selectUserTotalTracksById()
    {
        $statement = $this->pdo->query("
                SELECT u.id, u.pseudo, (SELECT COUNT(*) FROM " . TrackManager::TABLE . " t 
                WHERE t.user_id=u.id) AS nb_track
                FROM " . self::TABLE  . " u
                JOIN " . TrackManager::TABLE . " t ON t.user_id=u.id
                GROUP BY u.id
                ORDER BY nb_track DESC");

        return $statement->fetchAll();
    }
}
