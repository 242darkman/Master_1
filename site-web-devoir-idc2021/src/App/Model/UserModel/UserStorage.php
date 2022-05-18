<?php

namespace Miniframework\App\Model\UserModel;

use Miniframework\App\DataStorage\DataStorageInterface;

class UserStorage implements DataStorageInterface
{
    private $file;
    private $count;

    /**
     * constructeur de UserStorage
     * @param $file
     */
    public function __construct($file)
    {
        $this->file = $file;
        $this->count = $this->size();
    }

    /**
     * lecture d'un utilisateur d'identifiant $id dans la base de données
     * @param $id
     * @return false|mixed
     */
    public function read($id)
    {
        $user = false;
        $allUsers = $this->readAll();
        foreach ($allUsers as $value) {
            if (in_array($id, $value, true)) {
                $user = $value;
            }
        }
        return $user;
    }

    /**
     * lecture de tous les utilisateurs de notre base de données
     * @return mixed
     * @throws \JsonException
     */
    public function readAll()
    {
        $jsondata = file_get_contents($this->file);

        return json_decode($jsondata, true, 512, JSON_THROW_ON_ERROR);
    }

    public function create($user)
    {
        $arr_data = [];

        try {
            $array_user = [
                "id" => $this->count,
                "nom" => $user->getNom(),
                "prenom" => $user->getPrenom(),
                "tel" => $user->getTel(),
                "email" => $user->getEmail(),
                "password" => $user->getPassword(),
            ];

            $jsondata = file_get_contents($this->file);

            $arr_data = json_decode($jsondata, true);
            if ($arr_data === null) {
                $arr_data = [];
            }

            array_push($arr_data, $array_user);
            $jsondata = json_encode($arr_data, JSON_PRETTY_PRINT);

            if (file_put_contents($this->file, $jsondata)) {
                $this->count++;
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            throw $e->getMessage();
        }
    }

    /**
     * donne la taille de la base de données
     * @return int
     */
    private function size()
    {
        $jsondata = file_get_contents($this->file);
        $lenght = 0;

        $allUsers = json_decode($jsondata, true);
        if (count($allUsers) > 0) {
            $lenght = count($allUsers);
        }

        return $lenght;
    }
}
