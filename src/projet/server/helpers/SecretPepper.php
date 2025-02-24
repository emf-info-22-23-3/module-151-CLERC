<?php
/**
 * Classe qui contient un Pepper, utilisé pour rendre le Hash des mots de passes plus robuste
 * 
 * @author Lexkalli
 */

class SecretPepper
{

    private static $secretPepper = "2b5ugvx0rY!8B5Q4@G7vZ";

    public static function getSecretPepper()
    {
        return self::$secretPepper;
    }

}
?>