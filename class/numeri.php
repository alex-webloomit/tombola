<?php
defined('TOMBOLA') or exit('Errore');
class numeri
{
    private $id_partita = null;
    private $db = null;
    public function __construct()
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
    public function getNuovoNumero()
    {
        $sql = "SELECT (FLOOR(1 + RAND() * 90)) AS nuovo 
                FROM partite
                WHERE id = $this->id_partita AND 'nuovol' NOT IN (
                        SELECT numero 
                        FROM partite_numestratti 
                        WHERE id_partita = $this->id_partita
                    ) LIMIT 1";
        $res = $this->db->query($sql);
        if (empty($res))
        {
            return false;
        }
        $numRes = $res->fetch_object();
        $numero = $numRes->nuovo;
        $res->free();
        if (!is_numeric($numero))
        {
            throw new Exception('Generato un numero non valido', 2);
            return false;
        }
        $sql = "INSERT INTO partite_numestratti (ora, id_partita, numero) 
                    VALUES (NOW(), $this->id_partita, $numero)";
        if (!$this->db->query($sql))
        {
            return false;
        }
        return $numero;
    }
    public function getNumeriEstratti()
    {
        $sql = "SELECT numero 
                FROM partite_numestratti 
                WHERE id_partita = $this->id_partita 
                ORDER BY ora ASC";
        $res = $this->db->query($sql);
        if (empty($res))
        {
            return false;
        }
        $numeri = array();
        while ($numero = $res->fetch_object())
        {
            $numeri[] = $numero;
        }
        return $numeri;
    }
}
?>