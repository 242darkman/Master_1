<?php

namespace Miniframework\App\Metadata;

/**
 * classe permettant l'extraction de métadonnées d'un fichier
 */
class ExtractMetadata
{
    private $file;
    private $json;

    /**
     * constructeur de ExtractMetadata
     * @param null $file
     */
    public function __construct($file = null)
    {
        $this->file = $file;
    }

    public function writeJsonFile()
    {
        exec(
            'exiftool -g -json "' .
            $this->file .
            '" > Resources/lib/fileMetadata.json'
        );
        $j = file_get_contents("Resources/lib/fileMetadata.json");
        $json = json_decode($j, false);
        $this->json = $json[0];
    }

    public function getAllMetaData()
    {
        $json = file_get_contents("Resources/lib/fileMetadata.json");
        $json = json_decode($json, false);
        $this->json = $json[0];
        return $this->json;
    }

    public function setMetaData($str)
    {
        exec("exiftool " . $str . " " . $this->file);
    }

    public function makeDataCommand($key, $name, $value)
    {
        return "-" . $key . ":" . $name . "=\"" . $value . "\" ";
    }

    public function makeArrayCommand($key, $name, $value)
    {
        return "-" . $key . ":" . $name . "=\"" . $value . "\" ";
    }

    public function setDescription($desc)
    {
        exec("exiftool -XMP:Description=\"" . $desc . "\" " . $this->file);
    }

    public function getWritableTags()
    {
        $file = explode(" ", file_get_contents("Resources/lib/editable.txt"));

        return $file;
    }

    public function getTitleFile()
    {
        if (isset($this->json->{'XMP'}->{'Title'})) {
            return $this->json->{'XMP'}->{'Title'};
        }
        return null;
    }

    public function getConsistentTag()
    {
        $list = [];
        $file = explode(
            PHP_EOL,
            file_get_contents("Resources/lib/tags_coherent.txt")
        );
        for ($i = 0, $iMax = count($file); $i < $iMax; $i++) {
            $array = explode(" ", $file[$i]);
            array_push($list, $array);
        }

        return $list;
    }

    public function getFileType()
    {
        if (isset($this->json->{"File"}->{"FileTypeExtension"})) {
            return $this->json->{"File"}->{"FileTypeExtension"};
        }
        return null;
    }

    public function getDate()
    {
        if (isset($this->json->{"EXIF"}->{"DateTimeOriginal"})) {
            $date = $this->json->{"EXIF"}->{"DateTimeOriginal"};
            $date = substr($date, 0, -3);
            $date = preg_replace("/:/", "-", $date, 1);
            $date = preg_replace("/:/", "-", $date, 1);
            $date = str_replace(" ", "T", $date);

            return $date;
        }
        return null;
    }

    public function setDate($date)
    {
        exec(
            "exiftool -EXIF:DateTimeOriginal=\"" . $date . "\" " . $this->file
        );
    }
}
