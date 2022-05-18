<?php

namespace Miniframework\App\Http;

class Request
{
    private $get;
    private $post;
    private $session;
    private $files;
    private $server;
    private $request;

    public function __construct($get, $post, $session, $files, $server, $request)
    {
        $this->get = $get;
        $this->post = $post;
        $this->session = $session;
        $this->files = $files;
        $this->server = $server;
        $this->request = $request;
    }

    public function isAjaxRequest()
    {
        return !empty($this->server["HTTP_X_REQUESTED_WITH"]) &&
            strtolower($this->server["HTTP_X_REQUESTED_WITH"]) ==
            "xmlhttprequest";
    }

    public function getGetParam($key, $default = null)
    {
        if (!array_key_exists($key, $this->get)) {
            return $default;
        }
        return $this->get[$key];
    }

    public function getRequestParam($key, $default = null)
    {
        if (!array_key_exists($key, $this->request)) {
            return $default;
        }

        return $this->request[$key];
    }

    public function getFilesParam($key, $default = null)
    {
        if (empty($this->files[$key])) {
            return $default;
        }
        return $this->files[$key];
    }

    public function getPostParam($key, $default = null)
    {
        if (!array_key_exists($key, $this->post)) {
            return $default;
        }
        return $this->post[$key];
    }

    public function getSessionItem($key, $default = null)
    {
        if (!array_key_exists($key, $this->session)) {
            return $default;
        }
        return $this->session[$key];
    }

    public function setSessionItem($key, $value)
    {
        $this->session[$key] = $value;
    }

    public function removeSessionItem($key)
    {
        unset($this->session[$key]);
    }

    public function getAllPostParams()
    {
        return $this->post;
    }

    public function getActionParameterName($actionName)
    {
        if (
            $actionName == "showAction" ||
            $actionName == "buySongAction" ||
            $actionName == "editAction" ||
            $actionName == "deleteAction"
        ) {
            $parameter = "id";
        } else {
            $parameter = "";
        }

        return $parameter;
    }

    public function isPostRequest()
    {
        return $this->server["REQUEST_METHOD"] == "POST";
    }

    public function __destruct()
    {
        $_SESSION = $this->session;
    }

    public function removeAllPostParams()
    {
        $_POST = [];
    }
}
