<?php

namespace Miniframework\App\CB;

class SherlockPay
{

    /**
     * tableau de variable passées à sherlock lors de l'appel
     * @var string[]
     */
    private $vads_params_array = array(
        "vads_action_mode",
        "vads_page_action",
        "vads_ctx_mode",
        "vads_amount",
        "vads_order_id",
        "vads_url_success",
        "vads_url_referral",
        "vads_url_refused",
        "vads_url_cancel",
        "vads_url_error",
        "vads_currency",
        "vads_payment_cards",
        "vads_payment_config",
        "vads_site_id",
        "vads_trans_date",
        "vads_trans_id",
        "vads_validation_mode",
        "vads_version",
        "vads_url_return",
    );

    public $vads_action_mode;
    public $vads_page_action;
    public $vads_ctx_mode;
    public $vads_amount;
    public $vads_order_id;
    public $vads_url_success;
    public $vads_url_referral;
    public $vads_url_refused;
    public $vads_url_cancel;
    public $vads_url_error;
    public $vads_currency;
    public $vads_payment_cards;
    public $vads_trans_date;
    public $vads_trans_id;
    public $vads_validation_mode;
    public $vads_version;
    public $vads_url_return;

    private $vads_payment_config;

    public $vads_site_id;

    public $merchant_key;

    public $signature;

    /*
     * type retour sherlock
     */
    private $codes_retour_sytempay = array(
        "00" => "transaction approuvée ou traitée avec succès",
        "02" => "contacter l'émetteur de carte",
        "03" => "accepteur invalide",
        "04" => "conserver la carte",
        "05" => "ne pas honorer",
        "07" => "conserver la carte, conditions spéciales",
        "08" => "approuver après identification",
        "12" => "transaction invalide",
        "13" => "montant invalide",
        "14" => "numéro de porteur invalide",
        "15" => "Emetteur de carte inconnu",
        "17" => "Annulation client",
        "19" => "Répéter la transaction ultérieurement",
        "20" => "Réponse erronée (erreur dans le domaine serveur)",
        "24" => "Mise à jour de fichier non supportée",
        "25" => "Impossible de localiser l'enregistrement dans le fichier",
        "26" => "Enregistrement dupliqué, ancien enregistrement remplacé",
        "27" => "Erreur dans l'édition sur les champs de mise à jour fichier",
        "28" => "Accès interdit au fichier",
        "29" => "Mise à jour impossible",
        "30" => "erreur de format",
        "31" => "identifiant de l'organisme acquéreur inconnu",
        "33" => "date de validité de la carte dépassée",
        "34" => "suspicion de fraude",
        "38" => "Date de validité de la carte dépassée",
        "41" => "carte perdue",
        "43" => "carte volée",
        "51" => "provision insuffisante ou crédit dépassé",
        "54" => "date de validité de la carte dépassée",
        "55" => "Code confidentiel erroné",
        "56" => "carte absente du fichier",
        "57" => "transaction non permise à ce porteur",
        "58" => "transaction interdite au terminal",
        "59" => "suspicion de fraude",
        "60" => "l'accepteur de carte doit contacter l'acquéreur",
        "61" => "montant de retrait hors limite",
        "63" => "règles de sécurité non respectées",
        "68" => "réponse non parvenue ou reçue trop tard",
        "75" => "Nombre d'essais code confidentiel dépassé",
        "76" => "Porteur déjà en opposition, ancien enregistrement conservé",
        "90" => "arret momentané du système",
        "91" => "émetteur de cartes inaccessible",
        "94" => "transaction dupliquée",
        "96" => "mauvais fonctionnement du système",
        "97" => "échéance de la temporisation de surveillance globale",
        "98" => "serveur indisponible routage réseau demandé à nouveau",
        "99" => "incident domaine initiateur",
    );


    /**
     * construteur
     */
    public function __construct() {

        foreach ($this->vads_params_array as $vads){
            $this->$vads = "";
        }

        $this->vads_currency        = "978";//euro
        $this->vads_payment_cards   = "VISA;MASTERCARD;CB";
        $this->vads_page_action     = "PAYMENT";
        $this->vads_action_mode     = "INTERACTIVE";
        $this->vads_payment_config  = "SINGLE";
        $this->vads_validation_mode = "0";
        $this->vads_version         = "V2";
        asort($this->vads_params_array);
    }

    public function __set($name, $value){
        $this->$name = $value;
    }

    /**
     * calcul de la signature de paiement
     * @return string : signature de paiement
     */
    private function Signature(){
        $signature_contents = "";
        foreach ($this->vads_params_array as $vads){
            $signature_contents .= $this->$vads . "+";
        }
        $signature_contents .= $this->merchant_key;
        return sha1($signature_contents);
    }


    /**
     * Retour de paiement de CB
     * @param array $params tableau des variables reçu par le serveur de paiement
     * @return false la signature du paiement
     */
    public function Retour($params = array()){
        if(is_array($params) && !empty($params)){
            //vérification de la signature
            if($this->verif_Signature($params)){
                //traitement du résultat du paiement
                $this->ecrire_Log("Les signatures sont identiques - Retour OK");
                return $this->paiement_Result($params);
            }else{
                $this->ecrire_Log("Les signatures ne sont pas identiques - Echec du paiement");
                return false;
            }
        }else{
            $this->ecrire_Log("Aucun param�tre re�u " . __METHOD__);
            return false;
        }
    }

    /**
     * Résultat du paiement CB
     * @param array $params tableau des variables reçu par le serveur
     * @return bool
     */
    private function paiement_Result($params = array()) {
        if(isset($params['vads_result']) && $params['vads_result'] != ""){
            $this->ecrire_Log($this->codes_retour_sytempay[$params['vads_result']]);
            //paiement OK / KO
            return ($params['vads_result'] == "00")?true:false;
        }else{
            $this->ecrire_Log(" - resultat du paiement absent ou invalide");
            return false;
        }
    }


    /**
     * Vérification de la signature au retour du paiement
     *
     * @param array $params
     * @return bool
     */
    private function verif_Signature($params = array()){
        ksort($params);
        $signature_contents = "";
        // vérification reception variable hash
        // si le hash est reçu => alors reception depuis URL Serveur ( auto réponse )
        // Attention ! vous devez renseigner l'URL serveur dans l'outil de gestion de caisse systempay.
        if (isset($params['vads_hash'])) {
            foreach ($params as $key => $value) {
                if(str_contains($key, "vads_")){
                    $signature_contents .= $value . "+";
                }
            }
            $signature_contents .= $this->merchant_key;

            return ($params['signature'] === sha1($signature_contents)) ;
        }else{
            $this->ecrire_Log("Aucun parametre reçu" . __METHOD__) ;
            return false;
        }

    }


    /**
     * renvoie des valeurs du formulaire à poster
     *
     * @return string
     */
    public function returnInputs(){
        $inputs = "";
        foreach ($this->vads_params_array as $vads){
            $inputs .= "\r\n" ."<input type='hidden' name='$vads' value='" . $this->$vads . "' />";
        }
        $inputs .= "\r\n" ."<input type='hidden' name='signature' value='" . $this->Signature() . "' />";
        return $inputs;
    }

    /**
     * ecrire un fichier log des paiements
     * @param $string message à insérer dans le fichier de log
     */
    public function ecrire_Log($string) {
        $handle=fopen("logs.systempay.txt", "a+");
        fwrite($handle,"\r\n".date("d/m/Y H:i:s")." : " . $string);
        fclose($handle);
    }
}