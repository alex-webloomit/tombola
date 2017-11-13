<?php
defined('TOMBOLA') or exit('Errore');
class cartella
{
    private $db = null;
    private $instance = null;
    private $id_partita = null;
    public function __construct($num = null)
    {
        $this->db = database::getInstance();
        $partita = partita::getPartitaGiocatore();
        if (is_a($partita, 'partita'))
        {
            $this->id_partita = $partita->get('id');
        }
        if (empty($this->id_partita))
        {
            throw new Exception('Partita non definita', 1);
        }
    }
    /**
     * Restituisce una cartella nuova nel caso in cui il parametro sia null, oppure un numero di una cartella non in gioco
     * Nel caso in cui siano finite le catelle, o la cartella richiesta tramite numero non sia del giocatore connesso, ritorna false.
     */
    public static function getInstance($num = null)
    {
        if (is_null(self::$instance))
        {
            self::$instance = array();
        }
        /* Vuole una nuova cartella */
        if (empty($num))
        {
            $cartella = self::getProssimaCertellaDisponibile();
        }
        else if (is_numeric($num))
        {
            $cartella = self::getCartellaDaNumero($num);
        }
        if (empty(self::$instance))
        {
            $c = new cartella();
        }
    }
    private static function getProssimaCartellaDisponibile()
    {
        $db = database::getInstance();
        $partita = partita::getPartitaGiocatore();
        if (is_a($partita, 'partita'))
        {
            $id_partita = $partita->get('id');
        }
        if (empty($id_partita))
        {
            throw new Exception('Partita non definita', 1);
        }
        $sql = "SELECT * FROM cartelle 
                WHERE id NOT IN (
                SELECT id_cartella FROM partite_giocatori
                WHERE id_partita = $id_partita 
                LIMIT 1
                )";
        $res = $db->query($sql);
        if ($res === false)
        {
            return false;
        }
        $nuovaCartella = $res->fetch_object();
        return $nuovaCartella;
    }
    private static function getCartellaDaNumero($num = null)
    {
        $db = database::getInstance();
        $partita = partita::getPartitaGiocatore();
        if (is_a($partita, 'partita'))
        {
            $id_partita = $partita->get('id');
        }
        if (empty($id_partita))
        {
            throw new Exception('Partita non definita', 1);
        }
        $sql = "SELECT * FROM cartelle AS c 
                LEFT JOIN partite_giocatori AS g ON (g.id_cartella = c.id AND g.id_partita = $id_partita)
                WHERE c.id = $num";
        $res = $db->query($sql);
        if ($res === false)
        {
            return false;
        }
        $cartella = $res->fetch_object();
        if (empty($cartella))
        {
            return false;
        }
        $giocatore = giocatori::getInstance();
        if (is_a($giocatore, 'giocatori'))
        {
            $id_giocatore = $giocatore->get('id');
        }
        if (empty($id_giocatore))
        {
            throw new Exception('Giocatore non definito', 4);
            return false;
        }
        if (!empty($cartella->id_giocatore) && $cartella->id_giocatore != $id_giocatore)
        {
            return false;
        }
        if (empty($cartella->id_giocstore))
        {
            $sql = "INSERT INTO partite_giocatori (id_partita, id_cartella, id_giocatore) 
            ($id_partita, $num, $id_giocatore)";
            $esito = $db->query($sql);
            if ($esito === false)
            {
                return false;
            }
        }
        return $cartella;
    }
    public function getCartelleDisponibili()
    {
        $sql = "SELECT * FROM cartelle 
                WHERE id NOT IN (
                    SELECT id_cartella FROM partite_giocatori
                    WHERE id_partita = $this->id_partita
                    )";
    }
}