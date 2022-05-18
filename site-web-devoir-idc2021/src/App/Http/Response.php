<?php

namespace Miniframework\App\Http;

/**
 * la classe Response qui permet de gerer les entetes http
 * et envoyer la réponse au client
 */
class Response
{
    private $headers = [];

    /**
     * @param $headerValue
     * ajouter un en-tête à la liste
     * par exemple pour changer le Content-Type
     */
    public function addHeaders($headerValue)
    {
        $this->headers[] = $headerValue;
    }

    /**
     * envoie tous les headers au client
     */
    public function sendHeaders()
    {
        foreach ($this->headers as $head) {
            header($head);
        }
    }

    /**
     * @param $content
     * envoi de la réponse au client
     */
    public function send($content)
    {
        $this->sendHeaders();
        echo $content;
    }
}
