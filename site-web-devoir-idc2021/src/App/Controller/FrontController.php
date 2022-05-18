<?php

namespace Miniframework\App\Controller;

use Miniframework\App\Router\Router;
use Miniframework\App\View\View;
use Miniframework\App\AuthManager\AuthentificationManagerException;
use Miniframework\App\Model\FileModel\FileStorage;
use Miniframework\App\Model\UserModel\UserStorage;

class FrontController
{
    private $request;
    private $response;
    private $authManager;
    private $twig;

    public function __construct(
        $request,
        $response = "",
        $authManager,
        $template
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->authManager = $authManager;
        $this->twig = $template;
    }

    /**
     * méthode s'executant comme un point d'entrée dans l'application
     */
    public function execute()
    {
        $view = new View("view/template.html.twig", [], $this->twig);

        $router = new Router($this->request);
        try {
            if (!$this->authManager->isConnected()) {
                $view->removeMenu("Deconnexion");
                $view->setMenu("se connecter", Router::getLoginPage());
                $view->setMenu("s'inscrire", Router::getSigninPage());
                if ($this->request->getPostParam("connection") !== null) {
                    $email = $this->request->getPostParam("email");
                    $pwd = $this->request->getPostParam("password");
                    if (
                        $this->authManager->verifyAuthentication($email, $pwd)
                    ) {
                        $view->removeMenu("se connecter");
                        $view->removeMenu("s'inscrire");
                        $view->setMenu("Deconnexion", Router::logout());
                    } else {
                        $this->request->setSessionItem(
                            "feedback",
                            "Identifiants incorrects"
                        );
                        $router->redirectUrl(Router::getLoginPage());
                    }
                }
            } else {
                $view->removeMenu("se connecter");
                $view->removeMenu("s'inscrire");
                $view->setMenu("Deconnexion", Router::logout());
            }

            // gestion des feedback
            $feedback = $this->request->getSessionItem("feedback");
            if ($feedback != null) {
                $view->setPart("feedback", $feedback);
                $this->request->removeSessionItem("feedback");
            } else {
                $feedback = "";
            }
            $view->setPart("user", $this->request->getSessionItem("nom"));
            $controllerClassName = $router->getControllerClassName();
            $action = $router->getAction();
            $controller = new $controllerClassName(
                $this->request,
                $this->response,
                $view,
                $this->authManager
            );
            $controller->execute($action);
        } catch (AuthenticationException $e) {
            $router->POSTredirect(
                Router::getLoginPage(),
                '<span class="error">identifiants incorrects</span>'
            );
        }

        $content = $view->render();
        $this->response->send($content);
    }
}
