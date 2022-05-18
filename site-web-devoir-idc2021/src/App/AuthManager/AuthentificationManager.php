<?php

namespace Miniframework\App\AuthManager;

use Miniframework\App\AuthManager\AuthentificationManagerException;
use Miniframework\App\Http\Request;
use Miniframework\App\Model\UserModel\UserStorage;

class AuthentificationManager
{
    private $request;
    private $authData;
    private $db;

    /**
     * constructeur
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->db = new UserStorage(
            getcwd() .
            DIRECTORY_SEPARATOR .
            "Resources" .
            DIRECTORY_SEPARATOR .
            "userTable" .
            DIRECTORY_SEPARATOR .
            "user_table.json"
        );

        if (!empty($this->request->getSessionItem("email"))) {
            $this->authData["id"] = $this->request->getSessionItem("id");
            $this->authData["email"] = $this->request->getSessionItem("email");
            $this->authData["nom"] = $this->request->getSessionItem("nom");
        } else {
            $this->authData = [];
        }
    }

    public function getDb()
    {
        return $this->db;
    }

    public function setDb($db)
    {
        $this->db = $db;
    }

    public function getAuthData()
    {
        return $this->authData;
    }

    public function resetAuthData()
    {
        $this->authData = null;
    }

    public function isConnected()
    {
        $this->authData = array_filter($this->authData);
        if (empty($this->authData)) {
            return false;
        }

        return true;
    }
    /**
     * vérification des identifiants saisies
     * @throws \Miniframework\App\AuthManager\AuthentificationManagerException
     */
    public function verifyAuthentication($email, $pwd)
    {
        $users = $this->db->readAll();

        foreach ($users as $key => $user) {
            $data = ["email" => $email, "password" => ""];
            if (array_key_exists("email", $user) && $email == $user["email"]) {
                if (password_verify($pwd, $user["password"])) {
                    $this->authData["id"] = $user["id"];
                    $this->authData["email"] = $email;
                    $this->authData["nom"] =
                        $user["nom"] . " " . $user["prenom"];

                    $this->request->setSessionItem("email", $email);
                    $this->request->setSessionItem("id", $user["id"]);
                    $this->request->setSessionItem(
                        "nom",
                        $this->authData["nom"]
                    );

                    $this->request->removeSessionItem("userLoginFields");


                    return true;
                } else {
                    $this->request->setSessionItem("userLoginFields", $data);
                    $this->request->setSessionItem('feedback', '<span id="no_login" > Identifiants incorrects </span>');
                }
            } elseif ($key == count($users) - 1) {
                $this->request->setSessionItem('feedback', '<span id="no_login" > Identifiants incorrects </span>');
                $this->request->setSessionItem("userLoginFields", $data);
            }
        }
    }
}
