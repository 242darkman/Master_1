<?php

namespace Miniframework\App\Model\UserModel;

class User
{
    private $id;
    private $nom;
    private $prenom;
    private $phone_number;
    private $email;
    private $password;

    public function __construct(
        $nom = "",
        $prenom = "",
        $tel = "",
        $email = "",
        $password = ""
    ) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        $this->phone_number = $tel;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getNom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getPrenom()
    {
        return $this->prenom;
    }

    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->nom = $password;
    }

    public function getPhonenumber()
    {
        return $this->phone_number;
    }

    public function setPhonenumber($phone_number)
    {
        $this->phone_number = $phone_number;
    }
}
