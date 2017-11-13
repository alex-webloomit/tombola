<?php
defined('TOMBOLA') or exit('Errore');
class partita extends stdClass
{
    private $id = null;
    private $db = null;
    private static $partita = null;
    public function __construct()
    {
        $this->db = database::getInstance();
        $this->id = 1;
    }
    public static function getPartitaGiocatore()
    {
        if (is_null(self::$partita))
        {
            self::$partita = new partita();
        }
        return self::$partita;
    }
    public function get($p = null)
    {
        if (!empty($p) && property_exists($this, $p))
        {
            return $this->$p;
        }
        return false;
    }
}
?>