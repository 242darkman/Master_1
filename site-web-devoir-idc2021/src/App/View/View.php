<?php

namespace Miniframework\App\View;

use Miniframework\App\Router\Router;

class View
{
    private $template;
    private $parts;
    private $menu;
    private $twig;

    /**
     * constructeur de la vue
     * @param $template
     * @param array $parts
     * @param $twig
     */
    public function __construct($template, $parts = [], $twig)
    {
        $this->template = $template;
        $this->parts = $parts;
        $this->menu = [
            "Accueil" => Router::getHomepage(),
            "Charger votre image" => Router::uploadFile(),
            "s'inscrire" => Router::getSigninPage(),
            "A propos" => Router::getAboutPage(),
        ];
        $this->twig = $twig;
    }

    public function getPart($key)
    {
        if (!array_key_exists($key, $this->parts)) {
            return null;
        }
        return $this->parts[$key];
    }

    public function setPart($key, $content)
    {
        return $this->parts[$key] = $content;
    }

    public function getMenu($key = null)
    {
        if (!array_key_exists($key, $this->menu)) {
            return null;
        }
        return $this->menu[$key];
    }

    public function setMenu($key, $content)
    {
        $this->menu[$key] = $content;
    }
    public function removeMenu($key)
    {
        unset($this->menu[$key]);
    }

    /**
     * méthode qui nous permet de generer les vues de nos pages
     * @return mixed
     */
    public function render()
    {
        $menu = $this->menu;
        $openGraph = [
            "ogType" => "<meta property=\"og:type\" content=\"\" />",
            "ogTitre" => "<meta property=\"og:title\" content=\"\" />",
            "ogNom_site" =>
                "<meta property=\"og:site_name\" content=\"Microdata, Images et Métadonnées\" />",
            "ogDescription" =>
                "<meta property=\"og:description\" content=\"us\"/>",
            "ogFile" =>
                "<meta property=\"og:file\" content=\"http://hangingtogether.org/wp-content/uploads/2017/10/Word-cloud-metadata-advocacy-discussion-HT-2017-10.png\" />",
            "ogWidth" => "<meta property=\"og:file:width\" content=\"394\" />",
            "ogHeight" =>
                "<meta property=\"og:file:height\" content=\"200\" />",
        ];

        $twitterCard = [
            "tcType" => "<meta name=\"twitter:card\" content=\"summary\" />",
            "tcTitre" =>
                "<meta name=\"twitter:title\" content=\"Vente de chansons en ligne\"/>",

            "tcDescription" =>
                "<meta name=\"twitter:description\" content=\"\">",
            "tcFile" =>
                "<meta name=\"twitter:file\" content=\"http://hangingtogether.org/wp-content/uploads/2017/10/Word-cloud-metadata-advocacy-discussion-HT-2017-10.png\" />",
            "tcWidth" =>
                "<meta property=\"twitter:file:width\" content=\"394\" />",
            "tcHeight" =>
                "<meta property=\"twitter:file:height\" content=\"200\" />",
        ];

        $dataSocialNetwork = array_merge($openGraph, $twitterCard);

        $title = $this->getPart("title");
        $feedback = $this->getPart("feedback");

        $content = $this->getPart("content");
        $user = $this->getPart("user");
        $footer = $this->getPart("footer");

        if ($footer === null) {
            $c = $this->auto_copyright() .
                " Master 1 Internet, Données, Connaissances " .
                " ";
            $c .=
                'Projet de conception d\'applications web sur la création de miniframework. Tous droits Réservés';
            $footer = $c;
        }

        return $this->twig->render("template.html.twig", [
            "metaInfo" => $dataSocialNetwork,
            "menu" => $menu,
            "title" => $title,
            "user" => $user,
            "feedback" => $feedback,
            "content" => $content,
            "footer" => $footer,
        ]);
    }

    /**
     *
     * @param string $year
     * @return false|int|mixed|string
     */
    public function auto_copyright($year = "auto")
    {
        if ($year == "auto") {
            $year = date("Y");
        } elseif ((int) $year == date("Y")) {
            $year = intval($year);
        } elseif ((int) $year < date("Y")) {
            $year = (int) $year . " - " . date("Y");
        } elseif ((int) $year > date("Y")) {
            $year = date("Y");
        }
        return date("F") ." ". $year;
    }
}
