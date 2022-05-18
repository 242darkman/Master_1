<?php

require_once "configTwig/bootstrap.php";

use Miniframework\App\Metadata\ExtractMetadata;
use Miniframework\App\Model\FileModel\FileStorage;
use \Miniframework\App\Router\Router;

new FileUploading();

class FileUploading
{
    private ExtractMetadata $metadata;

    /**
     * constructeur
     */
    public function __construct()
    {
        $this->execute();
    }

    public function execute()
    {
        if ($_FILES["file"]["name"] != "") {
            $form = "";
            $file_up = explode(".", $_FILES["file"]["name"]);
            $ext = end($file_up);

            $name = $file_up[0] . "." . $ext;
            $location = getcwd() . "/Resources/upload/" . $name;
            $database = getcwd() . "/Resources/database/img/" . $name;
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $location) && copy($location, $database)) {

                $this->metadata = new ExtractMetadata($location);

                $file = glob($location, GLOB_BRACE);

                $form .= $this->showMetadata($file, $name);

                $fileStorage = new FileStorage();
                $id = $fileStorage->getId("Resources/upload/" . $name);

                $form .= '<div id="validerEffacer">';
                $form .=
                    '<input type="submit" name="connection" class="btn" id="btn" value="Valider">';
                $form .= "</form>";

                $form .=
                    '<form method="POST" action=" '.Router::deleteFile($id).' . &download=on">
              <input type="submit" class="btn" value="Effacer">
             </form></div>';
            }
            $form .= "<script> 
                    var spinner = $('.loader-wrapper');
                    $(function() {
                      $('#form').submit(function(e) {
                       // e.preventDefault();

                        spinner.show();
                        }).done(function(resp) {
                          spinner.hide();
          
                        });
                      });
                

          </script>";

            echo $form;
        }
    }

    public function showMetadata($file, $name)
    {
        $metadata = new ExtractMetadata($file[0]);
        $writableTags = $metadata->getWritableTags();
        $metadata->writeJsonFile();
        $fileType = $metadata->getFileType();
        $all = $metadata->getAllMetaData();

        $coherentTags = $this->joinCoherentTags($all, $metadata);
        $inconsistency = $this->detectInconsistency($coherentTags);
        $form = "";
        if ($inconsistency != null) {
            $form = $this->makeModal($inconsistency, $name);
        }

        $form .=
            '<h2 style="color:#6F2232"> Changez les métadonnées de votre fichier </h2>';

        $form .=
            '<form method="POST" id="form" class="form" action="index.php?o=file&a=update1" id="upload">';
        $keys = [];
        $hideKeys = ["File", "SourceFile", "ExifTool", "JFIF", "Photoshop"];
        foreach ($all as $key1 => $jsons) {
            if (!in_array($key1, $hideKeys, true)) {
                $form .= '<div id="articleParts"> <h2>' . $key1 . "</h2>";
                foreach ($jsons as $key => $value) {
                    $s = "";
                    if (is_array($value)) {
                        foreach ($value as $val) {
                            $s .= $val . " ";
                        }
                        $value = $s;
                    }
                    if (
                        in_array($key, $writableTags, true) &&
                        !in_array($key, $keys, true)
                    ) {
                        $form .=
                            '<div class="divArticlePart"> <label>' .
                            $key .
                            ':</label><input type="text" id="' .
                            $key .
                            '" name="' .
                            $key .
                            '" class="formItem" value="' .
                            $value .
                            '" /> </div>';
                        array_push($keys, $key);
                    } else {
                        $form .=
                            '<div class="divArticlePart"> <label>' .
                            $key .
                            ":</label> <label >" .
                            $value .
                            " </label> </div>";
                    }
                }
            }

            $form .= "</div>";
        }

        return $form;
    }

    public function makeModal($inconsistency, $name)
    {
        $fileStorage = new FileStorage();
        $form = "<script> 

        var modal = document.getElementById('myModal');
    
        // Get the button that opens the modal
        var btn = document.getElementById('myBtn');
    
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName('close')[0];
    
        let submit = document.getElementById('submit');
    
        // When the user clicks the button, open the modal 
        btn.onclick = function() {
          modal.style.display = 'block';
        }
    
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
          modal.style.display = 'none';
    
        }
    
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
    
          if (event.target == modal) {
            modal.style.display = 'none';
          }


    }


    </script>
    <button id='myBtn'>Show Inconsistency</button><div id='myModal' class='modal'>
               <div class='modal-content'>
               <span class='close'>&times;</span>
               <h2> Warning !!<h2>
               <h3> Inconsistency found </h3>";
        $id = $fileStorage->getId("files/files/" . $name);
        $form .=
            "<form   method='POST' action=' " .
            Router::getInconsistencyURL($id) .
            "'>";

        foreach ($inconsistency as $key1 => $jsons) {
            $form .= '<div class="inconsistency">';
            foreach ($jsons as $key => $value) {
                $s = "";
                if (is_array($value)) {
                    foreach ($value as $val) {
                        $s .= $val . " ";
                    }
                    $value = $s;
                }

                $form .=
                    '<input type="radio" name="' .
                    $key1 .
                    '" value="' .
                    $key .
                    ";" .
                    $value .
                    '" /> <label for="' .
                    $value .
                    '"><strong>' .
                    $key .
                    "</strong> :" .
                    $value .
                    "</label> <br>";
            }
            $form .= "</div>";
        }

        $form .= " <button id='submit'> Submit </button> </form>
         
        </div> 

    </div>";
        return $form;
    }

    public function joinCoherentTags($all, $metadata)
    {
        $result = [];
        $keysCoherent = $metadata->getConsistentTag();
        foreach ($keysCoherent as $key1 => $tags) {
            $duplicates = [];
            foreach ($all as $key => $jsons) {
                if ($key != "SourceFile") {
                    foreach ($jsons as $key2 => $value) {
                        if (in_array($key2, $tags, true)) {
                            $duplicates[$key2] = $value;
                        }
                    }
                }
            }
            if ($duplicates != null) {
                array_push($result, $duplicates);
            }
        }

        return $result;
    }

    public function detectInconsistency($tags)
    {
        $result = [];
        foreach ($tags as $k => $v) {
            $lastValue = " ";
            foreach ($v as $key => $value) {
                if ($lastValue != " ") {
                    if ($value != $lastValue) {
                        array_push($result, $v);
                        break;
                    }
                } else {
                    $lastValue = $value;
                }
            }
        }

        return $result;
    }
}
