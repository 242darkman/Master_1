<?php

namespace Miniframework\App\Model\UserModel;

class UserBuilder
{
    private $data;
    private $errors;
    const PRENOM_REF = "prenom";
    const NOM_REF = "nom";
    const EMAIL_REF = "email";
    const PASSWORD_REF = "password";
    const PHONENUMBER_REF = "tel";

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->errors = [];
    }

    public function getData()
    {
        return $this->data;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setErrors($item, $error)
    {
        return $this->errors[$item] = $error;
    }

    public function createUser()
    {
        $user = new User(
            $this->data["nom"],
            $this->data["prenom"],
            $this->data["tel"],
            $this->data["email"],
            $this->data["password"]
        );

        return $user;
    }

    public function isValid()
    {
        if (
            array_key_exists("nom", $this->data) &&
            array_key_exists("prenom", $this->data) &&
            array_key_exists("tel", $this->data) &&
            key_exists("email", $this->data) &&
            key_exists("password", $this->data)
        ) {
            if (
                $this->data["nom"] != "" &&
                $this->data["prenom"] != "" &&
                $this->checkEmail() &&
                $this->checkPhoneNumeber() &&
                $this->data["password"] != ""
            ) {
                return true;
            } else {
                if ($this->data["nom"] == "") {
                    $this->errors["nom"] = "Le nom est obligatoire";
                }

                if ($this->data["prenom"] == "") {
                    $this->errors["prenom"] = "Le prenom est obligatoire";
                }

                if ($this->data["tel"] == "") {
                    $this->errors["tel"] =
                        "Le numero de telephone est obligatoire";
                } elseif (!$this->checkPhoneNumeber()) {
                    $this->errors["tel"] =
                        'Le numero de telephone n\'est pas valide';
                }

                if ($this->data["email"] == "") {
                    $this->errors["email"] = 'L\'adresse mail est obligatoire';
                } elseif (!$this->checkEmail()) {
                    $this->errors["email"] = 'L\'adresse mail est invalide ';
                }

                if ($this->data["password"] == "") {
                    $this->errors["password"] =
                        "Le mot de passe est obligatoire";
                } elseif (strlen($this->data["password"]) < 3) {
                    $this->errors["password"] =
                        "Le mot de passe doit contenir au moins 3 caractères";
                }
            }
        } else {
            $this->error["pirate"] = "Alert pirate données invalides !!!";
        }
        return false;
    }

    private function checkPhoneNumeber()
    {
        $rawTel = preg_replace("#\D#", "", $this->data["tel"]);
        $tel = preg_replace(["#^33#", "#^0033#"], "", $rawTel);
        $regex = '#^0[6-7]{1}\d{8}$#';
        return preg_match($regex, $tel);
    }

    private function checkEmail()
    {
        return filter_var($this->data["email"], FILTER_VALIDATE_EMAIL);
    }
}
