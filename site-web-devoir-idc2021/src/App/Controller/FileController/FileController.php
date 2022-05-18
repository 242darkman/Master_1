<?php

namespace Miniframework\App\Controller\FileController;

use Miniframework\App\CB\SherlockPay;
use Miniframework\App\Router\Router;
use Miniframework\App\Http\Request;
use Miniframework\App\Http\Response;
use Miniframework\App\View\View;
use Miniframework\App\Model\FileModel\FileStorage;
use Miniframework\App\Metadata\ExtractMetadata;
use Miniframework\App\AuthManager\AuthentificationManager;

class FileController
{
    private $request;
    private $response;
    private $view;
    private $fileStorage;
    private $authManager;
    private $hideKeys;

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
    )
    {
        $this->request = $request;
        $this->response = $response;
        $this->view = $view;
        $this->authManager = $authManager;
        $this->fileStorage = new FileStorage();
        $this->hideKeys = [
            "File",
            "SourceFile",
            "ExifTool",
            "JFIF",
            "Photoshop"
        ];
    }

    /**
     * appel et execution des méthodes (actions) demandées par l'utilisateur
     * @param $action
     * @return mixed
     * @throws \Exception
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
            throw new \RuntimeException("Action {$action} non trouvée");
        }
    }

    /**
     * méthode générant la page de téléchargement d'un fichier
     */
    public function uploadFile()
    {
        // lorsque l'utilisateur est connecté on affiche pour le nouvel onglet "upload" le contenu de sa page
        if ($this->authManager->isConnected()) {
            $title = "Upload fichier ";
            $content = "<h2> Charger votre fichier afin d'extraire ses métadonnées </h2>";
            $content .= $this->uploadFormPage();

            $this->view->setPart("title", $title);
            $this->view->setPart("content", $content);
        } else {
            $this->request->setSessionItem(
                "feedback",
                '<span class="error">Veuiller vous authentifier pour avoir accès</span>'
            );
            Router::redirectUrl(Router::getHomepage());
        }
    }

    /**
     * page de génération du formulaire d'ajax d'upload
     * @return string
     */
    private function uploadFormPage()
    {
        $form = "<div id='fileUpload'> <input type='file' id='file' name='file' >  
                        <label for='file'>Charger votre fichier</label>
                        <span id='uploaded_file'> </span>
                        <img id='files' /> </div>

                        <script>
                        $(document).ready(function(){
                                 $(document).on('change', '#file', function(){ 
                                     // Obtention les fichiers à partir de la saisie du formulaire
                                      var files = document.getElementById('file').files[0];
                                      var name = document.getElementById('file').files[0].name;
                                      
                                      // Créer un objet FormData
                                      var form_data = new FormData();
                                      var ext = name.split('.').pop().toLowerCase();
                                 
                                      var reader = new FileReader();
                                      reader.readAsDataURL(files);
                                      //var fsize = file.size || file.fileSize;
                                
                                   form_data.append('file', files);
                                   $.ajax({
                                        url:'FileUploading.php',
                                        method:'POST',
                                        data: form_data,
                                        contentType: false,
                                        cache: false,
                                        processData: false,
                                    
                                        success:function(data)
                                        {
                                            $('#uploaded_file').html(data);
                                        }
                               });
                             });
                            });
                </script>";
        return $form;
    }

    /**
     * affichage des métadonnées de l'image
     */
    public function showPictureMetadata()
    {
        $id = $this->request->getGetParam("id");
        $longitutde = null;
        $latitude = null;
        $file = $this->fileStorage->read($id);

        if ($file !== null) {
            $metadata = new ExtractMetadata($file);
            $metadata->writeJsonFile();
            $all = $metadata->getAllMetaData();
            $title = "Metadonnées de l'image";
            $content = "";

            if ($this->authManager->isConnected()) {
                $content .= "<div id='edit'> 
            <li id='ed'> <a href=' "
                    . Router::editFileInfos($id)
                    . " '> <img width=40 height=40 src='Resources/skin/edit.png' alt='edit file' /></a> </li>
            <li id='down'> <a href='"
                    . $file
                    . "' download='"
                    . $file
                    . "'><img width=40 height=40 src='Resources/skin/down.jpg' alt='download file' /></a>  </li> 
            <li id='delete'> <a onclick=\"return  confirm(' 😢 Voulez-vous vraiment supprimé ce fichier ? 😢 ')\" href=' "
                    . Router::deleteFile($id)
                    . " '>
            <img width=40 height=40 src='Resources/skin/delete.png' alt='delete file' /></a>  </li> 
            <li id='ed'> <a href=' "
                    . Router::getPaymentURL()
                    . " '> <img width=40 height=40 src='Resources/skin/buy.png' alt='acheter' /></a> </li>
            </div>";
            }
            $content .= "<article id='article'>";
            $content .= "<div id='fileArticle'> ";
            $content .= "<img src='" . $file . "'/>";
            $content .= "</div>";

            foreach ($all as $key1 => $jsons) {
                if (!in_array($key1, $this->hideKeys)) {
                    $content .= '<div id="articleParts"> <h2>' . $key1 . "</h2>";
                    foreach ($jsons as $key => $value) {
                        if (is_array($value)) {
                            $value = $this->makeString($value);
                        }

                        $content .= '<div class="labels" ><label>'
                            . $key
                            . '</label>: <label id="'
                            . $key
                            . '">'
                            . $value
                            . "</label></div>";
                    }
                    $content .= "</div>";
                }
            }
            $content .= "</article>";

            $this->view->setPart("title", $title);
            $this->view->setPart("content", $content);
        } else {
            $this->unknownfile();
        }
    }

    /**
     * concatenation des valeur d'une array dans un String
     * @param $tab
     * @return string
     */
    public function makeString($tab)
    {
        $s = "";

        foreach ($tab as $value) {
            $s .= $value . " ";
        }
        return $s;
    }

    public function unknownfile()
    {
        $this->request->setSessionItem(
            "feedback",
            '<span class="error">Désolé ce fichier n\'existe pas</span>'
        );
        Router::redirectUrl(Router::getHomepage());
    }

    /**
     * changement des données lorsqu'il y a certaines incohérences
     */
    public function updateInconsistency()
    {
        $a = [];

        $id = $this->request->getGetParam("id");
        $file = $this->fileStorage->read($id);
        $metadata = new ExtractMetadata($file);
        $metadata->writeJsonFile();
        $tags = $metadata->getConsistentTag();
        $all = $metadata->getAllMetaData();
        $writableTags = $metadata->getWritableTags();
        $incoherentTags = $this->request->getAllPostParams();
        $array = $this->inconsistencyResolution($incoherentTags);
        $a = $this->mergeArrays($tags, $array);

        $keys = [];

        $form = "<form id='editForm' class='form' method='POST' action=' ".Router::updateInconsistencyOfFile($id)." '>";
        foreach ($all as $key => $jsons) {
            if (!in_array($key, $this->hideKeys, true)) {
                $form .= '<div id="articleParts"> <h2> ' . $key . "</h2>";

                foreach ($jsons as $clef => $value) {
                    if (is_array($value)) {
                        $value = $this->makeString($value);
                    }

                    if (in_array($clef, $writableTags, true)
                        && !in_array($clef, $keys, true)) {
                        if (array_key_exists($clef, $a)) {
                            $form .= '<div class="divArticlePart"> <label>'
                                . $clef
                                . ':</label><input type="text" id="'
                                . $clef
                                . '" name="'
                                . $clef
                                . '" class="formItem" value="'
                                . $a[$clef]
                                . '" /> </div>';
                        } else {
                            $form .= '<div class="divArticlePart"> <label>'
                                . $clef
                                . ':</label><input type="text" id="'
                                . $clef
                                . '" name="'
                                . $clef
                                . '" class="formItem" value="'
                                . $value
                                . '" /> </div>';
                            array_push($keys, $clef);
                        }
                    } else if (array_key_exists($clef, $a)) {
                        $form .= '<div class="divArticlePart"> <label>'
                            . $clef
                            . ":</label> <label >"
                            . $a[$clef]
                            . " </label> </div>";
                    } else {
                        $form .= '<div class="divArticlePart"> <label>'
                            . $clef
                            . ":</label> <label >"
                            . $value
                            . " </label> </div>";
                    }
                }
                $form .= "</div>";
            }
        }
        $form .= '<input type="submit" name="connection" class="btn" id="btn" value="Valider"> </form>';

        $this->view->setPart("content", $form);
    }

    public function inconsistencyResolution($incoherentTags)
    {
        $result = [];

        foreach ($incoherentTags as $key => $value) {
            $string = explode(";", $value);
            $result[$string[0]] = $string[1];
        }
        return $result;
    }

    public function mergeArrays($tags, $array)
    {
        $a = [];

        foreach ($tags as $key => $value) {
            foreach ($array as $key1 => $value1) {
                if (in_array($key1, $value, true)) {
                    $a = array_merge($a, $this->makeArray($value, $value1));
                    break;
                }
            }
        }
        return $a;
    }


    public function makeArray($array, $value)
    {
        $result = [];
        for ($i = 0; $i < sizeof($array); $i++) {
            $result[$array[$i]] = $value;
        }
        return $result;
    }


    public function editAction()
    {
        if ($this->authManager->isConnected()) {
            $id = $this->request->getGetParam("id");

            $file = $this->fileStorage->read($id);
            $metadata = new ExtractMetadata($file);
            $metadata->writeJsonFile();

            $all = $metadata->getAllMetaData();

            $writableTags = $metadata->getWritableTags();

            $keys = [];

            $form = "<form id='editForm' class='form' method='POST' action=' ".Router::updateInconsistencyOfFile($id)." '>";
            foreach ($all as $key => $jsons) {
                if (!in_array($key, $this->hideKeys, true)) {
                    $form .= '<div id="articleParts"> <h2> ' . $key . "</h2>";

                    foreach ($jsons as $clef => $value) {
                        if (is_array($value)) {
                            $value = $this->makeString($value);
                        }

                        if (in_array($clef, $writableTags, true)
                            && !in_array($clef, $keys, true)) {
                            $form .= '<div class="divArticlePart"> <label>'
                                . $clef
                                . ':</label><input type="text" id="'
                                . $clef
                                . '" name="'
                                . $clef
                                . '" class="formItem" value="'
                                . $value
                                . '" /> </div>';
                            array_push($keys, $clef);
                        } else {
                            $form .= '<div class="divArticlePart"> <label>'
                                . $clef
                                . ":</label> <label >"
                                . $value
                                . " </label> </div>";
                        }
                    }
                    $form .= "</div>";
                }
            }
            $form .= '<input type="submit" name="connection" class="btn" id="btn" value="Valider"> </form>';
            $form .= "<script> 
                    var spinner = $('.loader-wrapper');
                    $(function() {
                      $('#editForm').submit(function(e) {
                       // e.preventDefault();

                        spinner.show();
                        }).done(function(resp) {
                          spinner.hide();
          
                        });
                      });
                

                             </script>";

            $this->view->setPart("content", $form);
        } else {
            header("Location:index.php");
        }
    }

    /**
     * mise à jour des métadonnées d'un fichier
     * @throws \JsonException
     */
    public function updateAction()
    {
        if ($this->authManager->isConnected()) {
            $action = $this->request->getGetParam("a");
            $get_json_content = file_get_contents(
                "Resources/lib/fileMetadata.json"
            );
            $get_json_content = json_decode($get_json_content, true, 512, JSON_THROW_ON_ERROR);
            $j = $get_json_content[0];

            $metadata = new ExtractMetadata($j["SourceFile"]);
            $formData = $this->request->getAllPostParams();

            $all = $metadata->getAllMetaData();
            $str = "";

            foreach ($all as $key1 => $array) {
                if (!in_array($key1, $this->hideKeys, true)) {
                    foreach ($array as $key2 => $value) {
                        if (array_key_exists($key2, $formData) && $value != $formData[$key2]) {
                            if (is_array($value)) {
                                $value = $this->makeString($value);
                            }
                            $str .= $metadata->makeDataCommand(
                                $key1,
                                $key2,
                                $formData[$key2]
                            );
                        }
                    }
                }
            }
            if ($str != " ") {
                $metadata->setMetaData($str);
            }

            if ($action === "update_file_uploaded") {
                $this->request->setSessionItem(
                    "feedback",
                    '<span class="error">Vous avez bien ajouter un fichier.</span>'
                );
            } else {
                $this->request->setSessionItem(
                    "feedback",
                    '<span class="error">Vos métadonnées ont bien été modifiées.</span>'
                );
            }

        } else {
            $this->request->setSessionItem(
                "feedback",
                '<span class="error">Accès refusé</span>'
            );
        }
        Router::redirectUrl(Router::getHomepage());
    }

    /**
     * suppression de fichier
     * @param $id
     */
    public function deleteAction($id)
    {
        if ($this->authManager->isConnected()) {
            $userSession = $this->authManager->getAuthData();
            $userSession["id"] = $this->request->getSessionItem("id");
            $download = $this->request->getGetParam("download");
            $fileArray = $this->fileStorage->read($id);
            if (!$fileArray) {
                $this->request->setSessionItem(
                    "feedback",
                    '<span class="error">Désolé ce fichier n\'existe pas</span>'
                );
                Router::redirectUrl(Router::getHomepage());
            }
            try {
                $this->deleteFileFromServer($fileArray);

                if ($download === "on") {
                    Router::redirectUrl(Router::uploadFile());
                } else {
                    Router::redirectUrl("index.php");
                    $this->request->setSessionItem(
                        "feedback",
                        "<span>Votre image a été supprimé avec succès</span>"
                    );
                }
            } catch (\Exception $e) {
                $this->request->setSessionItem(
                    "feedback",
                    '<span class="error">Désolé votre fichier n\'a pas été supprimé</span>'
                );

                Router::redirectUrl("index.php");
            }
        } else {
            Router::redirectUrl("index.php");
        }
    }

    /**
     * méthode permettant d'effacer le fichier dans le dossier
     * @param $sourceFile
     */
    private function deleteFileFromServer($sourceFile)
    {
        $file = glob($sourceFile, GLOB_BRACE);
        try {
            unlink($file[0]);
        } catch (\Exception $e) {
            throw $e;
        }
    }



    /**
     * génération de la page à propos
     */
    public function aboutPage()
    {
        $title = "Metadata";

        $content = '<div>
        
        <div >
            <table id="author">
                <thead> <tr > <th colspan="2"> Auteur du projet </th> </tr> 
                        <tr> <th> N° étudiant </th>  <th> Nom & Prénoms </th></tr>
                </thead>
                
                <tbody>
                    <tr> 
                        <th> 21812350 </th>
                        <th> VOUVOU Brandon </th>
                    </tr>
                </tbody>
            </table>

        </div>
            
            
        <div>
            <table id="author">
                <thead> <tr > <th colspan="2"> Fonctionnalités implémentées </th> </tr> 
                </thead>
                
                <tbody>
                    <tr> 
                        <th>
                            <ul> 
                                <li> Utilisation de Twig pour les templates du site </li>
                                <li> Gestion de la cohérence des métadonnées </li>
                                <li> Stockage de la meme information à divers endroits (EXITF, IPTC ou XMP) </li>
                                <li> Utilisation de CSS Grid et Flexbox</li>
                                <li> Système d\'authentification </li>
                                <li> Extraction de métadonnées d\'une image </li>
                                <li> Inclusion de Microdata, des données Open Graph et Twitter Cards</li>
                                <li> Upload d\'image réalisé en AJAX </li>
                                <li> Système de paiement (pas totalement fonctionnel) </li>
                                <li> Responsive Design </li>
                            </ul>
                        </th>
                    </tr>
                </tbody>
            </table>
        </div>    
            
        

        <div>
        
            <table id="author">
                <thead> <tr > <th colspan="2"> Identifiant de connexion </th> </tr> 
                        <tr> <th> Login </th>  <th> Password </th></tr>
                </thead>
                
                <tbody>
                    <tr> 
                        <th>  jean-marc.lecarpentier@unicaen.fr </th>
                        <th> toto </th>
                    </tr>
                    
                    <tr> 
                        <th> alexandre.niveau@unicaen.fr </th>
                        <th> toto </th>
                    </tr>
                </tbody>
            </table>

        </div>


        <div>';

        $this->view->setPart("title", $title);
        $this->view->setPart("content", $content);
    }

    /**
     * génération de la page d'accueil
     */
    public function homePage()
    {
        $files = $this->fileStorage->readAll();

        $title = Router::htmlEsc("Microdata, Images et Métadonnées");
        $content = "<div class= wrapper>";
        foreach ($files as $id => $address_src) {
            $content .= "<div class='box'> <a href=' "
                . Router::showFile($id)
                . " '><img src='"
                . $address_src
                . "'/><div class='overlay'></div></a></div>";
        }

        $content .= "</div>";
        $this->view->setPart("title", $title);
        $this->view->setPart("content", $content);
    }


    public function paymentPage(){
        $paiementCB = new SherlockPay();
        $title = " Achat de l'image ";
        $content = " <form id='connect' method='post' action= ' ".Router::getPaymentServerURL()." '> ";
        //$content .= $paiementCB->returnInputs();
        //$content .= " <input type='image' value='Payer' src='systempay/logo_cb.jpg' width='163' height='35' name='payer' /> ";
        $content .= "<input type='hidden' name='data' VALUE='2020363236603028502c2360562c4338532d2360522d2360502c3360502c2331302d433c555c224360542c3360502c2340522c2360562c3360512e3048502c2328502c2324552c2324542c4344552c5360532e3324512c3324515c224360522e3360502c2329463c4048502c232c502c2360532c3328535c224360502d3360502c2338512d332c572d33342a2c2360562c2360512d2328502c4324512c4324572c3330532d5334555c224360502e2360502c232c592d53402a2c2328582c2360502c4639525c224360502e3360502c2329463c4048502c3324502c233c593a2731543c272c5a2b525d443937384d2c4324562c2324522d33444e3d372d453c472c4e3a3659463b5259553b4645433836354e2b4639522b5454512b5531302d355d50383645453b36354e3d255d42383659433836455239325d533d362d4339372d532b4721483c6048502c5324502c2340563a2731543c272c5a2b525d443937384d2c4324562c2324522d33444e3d372d453c472c4e3a3659463b5259553b4645433836354e2b4639522b5454512b5531302d355d50383645453b36354e3d255d42383659433836455239325d503c464556383731452b5729453d265d553c4259503a27602a2c2324532c2360572e2641543d2721532e425c4f392635562b3328512d4360512c4334592b473553393729532b46454e39465c4e3d365949385625453b4259463c425d2d2c325d343423353f3c262549393655453b47313f3846254e385625493c46344f3856254e3856354c2b4721483c6048502c3338502c23605334552d2c5c224360512d5360502c233946394639463946382a2c2324582c2360502d4360502c2360502c6048502c3344502c2324553c5641453c46514f38564d532c56384e395645465c224360522c3360502c3339423836594e3a36355239355d4c3856504e3c2659475c224360522d4360502c5329232c4334532c4344592d543450303424502d343455305428592c3344502e2334542d4338502d542c572c4048502c5338502c2324583054284c354445333032512d30352d3431352923303529245c224360532e2360502c2344592e233c562d3330532c43242a2c233c562c2360502e2328502c332c502d4360575c224360572d5360502c232c512b43602a2c233c582c2360502c5721483c60486081bc7cc800176ec9'>";
        $content .= "<div align='center'><input type='image' name='cb' border=0 src='Resources/logos/CB.gif'>";
        $content .= "<img src='Resources/logos/INTERVAL.gif'><input type='image' name='visa' border=0 src='Resources/logos/VISA.gif'>";
        $content .= "<input type='image' name='mastercard' border=0 src='Resources/logos/MASTERCARD.gif'><br><br></DIV>";
        $content .= " </form> ";

        $this->view->setPart("title", $title);
        $this->view->setPart("content", $content);
    }
    
    
}
