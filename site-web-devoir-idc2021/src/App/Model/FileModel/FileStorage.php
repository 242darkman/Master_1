<?php

namespace Miniframework\App\Model\FileModel;

use Miniframework\App\DataStorage\DataStorageInterface;

/**
 * Classe faisant office de notre base de données locales
 */
class FileStorage implements DataStorageInterface
{
    private $file;
    public $db;
    private $count;

    /**
     * constructeur de FileStorage
     * @param null $file
     */
    public function __construct($file = null)
    {
        if ($file === null) {
            $this->db = glob("Resources/database/img/*.*", GLOB_BRACE);
        } else {
            $this->file = $file;
            $this->count = $this->size();
        }
    }

    /**
     * lecture du fichier d'identifiant $id dans notre base de données
     * @param $id
     * @return mixed|null
     */
    public function read($id)
    {
        if (array_key_exists($id, $this->db)) {
            return $this->db[$id];
        }
        return null;
    }

    /**
     * lecture de tous les fichiers de la base de données
     * @return array|false
     */
    public function readAll()
    {
        return $this->db;
    }

    /**
     * lecture du dernier fichier de la base de données
     * @return false|mixed
     */
    public function readLastFile()
    {
        $last = end($this->db);
        return $last;
    }

    /**
     * récupération d'un identifiant pour un fichier de notre base de données
     * @param $filename
     * @return int|string|void
     */
    public function getId($filename)
    {
        foreach ($this->db as $key => $value) {
            if ($filename === $value) {
                return $key;
            }
        }
    }

    public function setFile($new_file)
    {
        $this->file = $new_file;
    }
}
