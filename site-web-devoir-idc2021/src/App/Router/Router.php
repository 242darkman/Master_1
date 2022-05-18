<?php

namespace Miniframework\App\Router;

use Miniframework\App\Http\Request;

class Router
{
    private $request;

    /**
     * constructeur
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * instancie de manière automatique le controleur d'objet auquel l'utilisateur à fait appel
     * @return string
     */
    public function getControllerClassName()
    {
        $controllerClassName = "";
        $controlerParam = $this->request->getGetParam("o");

        switch ($controlerParam) {
            case "user":
                $controllerClassName =
                    "\Miniframework\App\Controller\UserController\UserController";
                break;
            default:
                $controllerClassName =
                    "\Miniframework\App\Controller\FileController\FileController";
                break;
        }
        return $controllerClassName;
    }

    /**
     * récupère l'action à effectuer dans l'url
     * @return string
     */
    public function getAction()
    {
        $actionParam = $this->request->getGetParam("a");
        $action = "";

        switch ($actionParam) {
            case "index":
                $action = "indexAction";
                break;
            case "upload":
                $action = "uploadFile";
                break;
            case "show":
                $action = "showPictureMetadata";
                break;
            case "edit":
                $action = "editAction";
                break;
            case "update_file_uploaded":
            case "update":
                $action = "updateAction";
                break;
            case "delete":
                $action = "deleteAction";
                break;
            case "download":
                $action = "downloadFileAction";
                break;
            case "logout":
                $action = "logoutAction";
                break;
            case "signin":
                $action = "registerAction";
                break;
            case "login":
                $action = "loginAction";
                break;
            case "inconsistency":
                $action = "updateInconsistency";
                break;
            case "about":
                $action = "aboutPage";
                break;
            case "paiement":
                $action = "paymentPage";
                break;
            default:
                $action = "homePage";
        }
        return $action;
    }

    /**
     * méthode permettant la redirection vers une autre page
     * @param $url : lien vers lequel on sera redirigé
     * @param $feedback : message de retour
     */
    public function POSTredirect($url, $feedback)
    {
        $this->request->setSessionItem("feedback", $feedback);
        return header("Location: " . htmlspecialchars_decode($url), true, 303);
    }

    /**
     * méthode permettant la redirection de page sans feedback
     * @param $url
     */
    public static function redirectUrl($url)
    {
        return header("Location: " . htmlspecialchars_decode($url), true, 303);
    }

    public static function downloadFile($file)
    {
        return "index.php?o=file&a=download&file=" . $file;
    }

    public static function showFile($id)
    {
        return "index.php?o=file&a=show&id=" . $id;
    }

    public static function editFileInfos($id)
    {
        return "index.php?o=file&a=edit&id=" . $id;
    }

    public static function deleteFile($id)
    {
        return "index.php?o=file&a=delete&id=" . $id;
    }

    public static function updateFileInfos()
    {
        return "index.php?o=file&a=update";
    }

    public static function uploadFile()
    {
        return "index.php?o=file&a=upload";
    }

    public static function getSigninPage()
    {
        return "index.php?o=user&a=signin";
    }

    public static function getHomepage()
    {
        return "index.php?o=file&a=accueil";
    }

    public static function getLoginPage()
    {
        return "index.php?o=user&a=login";
    }

    public static function logout()
    {
        return "index.php?o=user&a=logout";
    }

    public static function getAboutPage()
    {
        return "index.php?o=file&a=about";
    }

    public static function htmlEsc($str)
    {
        return htmlspecialchars(
            $str,
            ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401
        );
    }

    public static function updateFileUploaded()
    {
        return "index.php?o=file&a=update_file_uploaded";
    }

    public static function getInconsistencyURL($id)
    {
        return " index.php?o=file&a=inconsistency&id=" . $id . " ";
    }

    public static function updateInconsistencyOfFile($id){
        return ' index.php?o=file&a=update&id=' .$id. ' ';
    }

    public static function getPaymentServerURL(){
        return 'https://sherlocks.lcl.fr/cgis-payment-sherlocks/demo/callpayment';
    }

    public static function getPaymentURL(){
        return ' index.php?o=file&a=paiement ';
    }

    //public static function getUploadCreationPage(){
    //    return 'index.php?o=file&a=create';
    //}
}
