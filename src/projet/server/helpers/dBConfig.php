<?php
/**
 * Classe contenant les informations liées à la BD afin de pouvoir s'y connecter
 * 
 * @author Lexkalli
 */

class DBConfig
{
    private $type;
    private $host;
    private $name;
    private $user;
    private $pass;

    public function __construct(
        $type = 'mysql',
        $host = 'clercd.emf-informatique.ch',
        $name = 'clercd_gestionnaireTaches',
        $user = 'clercd_root',
        $pass = 'BBE5Sq0&cSIr'
    ) {
        $this->type = $type;
        $this->host = $host;
        $this->name = $name;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPass()
    {
        return $this->pass;
    }
}
?>