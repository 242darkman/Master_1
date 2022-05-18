<?php

namespace Miniframework\App\Controller\UserController;

use Miniframework\App\Http\Request;
use Miniframework\App\Http\Response;
use Miniframework\App\View\View;
use Miniframework\App\Router\Router;
use Miniframework\App\Model\UserModel\UserBuilder;
use Miniframework\App\Model\UserModel\UserStorage;
use Miniframework\App\AuthManager\AuthentificationManager;

class UserController
{
    private $request;
    private $response;
    private $view;
    private $db;
    private $authManager;

    /**
     * constructeur
     * @param Request $request
     * @param Response $response
     * @param View $view
     * @param AuthentificationManager $authManager
     */
    public function __construct(
        Request $request,
        Response $response,
        View $view,
        AuthentificationManager $authManager
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->view = $view;
        $this->authManager = $authManager;
        $this->db = $authManager->getDb();
    }

    /**
     *
     * @param $action
     * @return mixed
     */
    public function execute($action)
    {
        if (method_exists($this, $action)) {
            $parameter = $this->request->getActionParameterName($action);
            if (!empty($parameter)) {
                return $this->$action($this->request->getGetParam($parameter));
            } else {
                return $this->$action();
            }
        } else {
            throw new Exception("Action {$action} non trouvée");
        }
    }

    /**
     * méthode d'affichage du formulaire d'inscription
     */
    public function registerAction()
    {
        $this->request->removeSessionItem("CurrentNewUser");
        if ($this->request->isPostRequest()) {
            $userPost = $this->request->getAllPostParams();

            $userBuilder = new UserBuilder($userPost);
            if ($userBuilder->isValid()) {
                $user = $userBuilder->createUser();

                $this->db->create($user);
                $this->request->removeSessionItem("CurrentNewUser");

                Router::redirectUrl("index.php");
            } else {
                $this->request->setSessionItem("CurrentNewUser", $userBuilder);
                Router::redirectUrl(Router::getSigninPage());
            }
        } else {
            $title = "Inscrivez-vous";

            $userBuilderSession = $this->request->getSessionItem(
                "CurrentNewUser"
            );
            if ($userBuilderSession != null) {
                $content = $this->registerForm($userBuilderSession);
            } else {
                $data = [
                    "nom" => "",
                    "prenom" => "",
                    "tel" => "",
                    "email" => "",
                    "password" => "",
                ];
                $uBuilder = new UserBuilder($data);
                $this->request->setSessionItem("CurrentNewUser", $uBuilder);
                $content = $this->registerForm($uBuilder);
            }

            $this->view->setPart("title", $title);
            $this->view->setPart("content", $content);
        }
    }

    /**
     * méthode d'affichage de la page de connexion
     */
    public function loginAction()
    {
        $title = "Identifiez-vous";

        $userLoginFields = $this->request->getSessionItem("userLoginFields");

        if ($userLoginFields != null) {
            $content = $this->loginForm($userLoginFields);
        } else {
            $data = ["email" => "", "password" => ""];
            $this->request->setSessionItem("userLoginFields", $data);

            $content = $this->loginForm($data);
        }

        $this->view->setPart("title", $title);

        $this->view->setPart("content", $content);
    }

    /**
     * méthode de déconnexion
     */
    public function logoutAction()
    {
        session_destroy();
        $this->authManager->resetAuthData();
        Router::redirectUrl(Router::getHomepage());
        exit();
    }

    /**
     * page de génération du formulaire d'inscription
     * @param UserBuilder $userBuilder
     * @return string
     */
    private function registerForm(UserBuilder $userBuilder)
    {
        $errors = $userBuilder->getErrors();
        $data = $userBuilder->getData();
        $form =
            '<form method="POST" action="' .
            Router::getSigninPage() .
            '" class="userForm">
                    <div><input type="text" name="' .
            UserBuilder::NOM_REF .
            '" placeholder="Nom" value="' .
            $data[UserBuilder::NOM_REF] .
            '" class="userInput" />';
        if ((count($errors) !== 0) && array_key_exists(UserBuilder::NOM_REF, $errors)) {
            $form .=
                '<br/> <span class="error">' .
                $errors[UserBuilder::NOM_REF] .
                "</span>";
        }
        $form .= "</div>";

        $form .=
            '<div><input type="text" name="' .
            UserBuilder::PRENOM_REF .
            '" value="' .
            $data[UserBuilder::PRENOM_REF] .
            '" placeholder="Prénom" class="userInput" />';
        if ((count($errors) !== 0) && array_key_exists(UserBuilder::PRENOM_REF, $errors)) {
            $form .=
                '<br/><span class="error">' .
                $errors[UserBuilder::PRENOM_REF] .
                "</span>";
        }
        $form .= "</div>";

        $form .=
            '<div><input type="text" name="' .
            UserBuilder::PHONENUMBER_REF .
            '" value="' .
            $data[UserBuilder::PHONENUMBER_REF] .
            '" placeholder="Numero de tel" class="userInput" />';
        if (($errors !== null) && array_key_exists(UserBuilder::PHONENUMBER_REF, $errors)) {
            $form .=
                '<br/><span class="error">' .
                $errors[UserBuilder::PHONENUMBER_REF] .
                "</span>";
        }
        $form .= "</div>";

        $form .=
            '<div><input type="text" name="email" value="' .
            $data[UserBuilder::EMAIL_REF] .
            '" placeholder="Adresse mail" class="userInput" />';
        if ((count($errors) !== 0) && array_key_exists(UserBuilder::EMAIL_REF, $errors)) {
            $form .=
                '<br/><span class="error">' .
                $errors[UserBuilder::EMAIL_REF] .
                "</span>";
        }
        $form .= "</div>";

        $form .=
            '<div><input type="password" name="password" value="' .
            $data[UserBuilder::PASSWORD_REF] .
            '" placeholder="Mot de passe" class="userInput" />';
        if ((count($errors) !== 0) && array_key_exists(UserBuilder::PASSWORD_REF, $errors)) {
            $form .=
                '<br/><span class="error">' .
                $errors[UserBuilder::PASSWORD_REF] .
                "</span>";
        }
        $form .=
            '</div><input type="submit"  class="btn" name="valider" value="Valider"></form>';

        return $form;
    }

    /**
     * page de génération du formulaire de connexion
     * @param $data
     * @return string
     */
    private function loginForm($data)
    {
        $form = '<form method="POST" action="index.php" class="userForm">';
        $form .=
            '<div><input type="email" id="email" name="email" class="userInput" placeholder="Email" value="' .
            $data["email"] .
            '" required /></div>';
        $form .= '<div><input type="password" id="password" name="password" value="" placeholder="Mot de passe" class="userInput"  /></div>
                  <input type="submit" name="connection" class="btn" value="Valider">
             </form>';

        return $form;
    }
}
