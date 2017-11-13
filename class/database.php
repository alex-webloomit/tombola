<?php
defined('TOMBOLA') or exit('Errore');
class database extends mysqli
{
    private static $conn = null;
    public function __construct($host = '', $user = '', $pass = '', $db = '')
    {
        $esb = parent::__construct($host, $user, $pass, $db);
        if ($this->connect_error)
        {
            exit('Errore di connessione al database: ' . $this->connect_error);
        }
    }
    public function __destruct()
    {
        if (is_a($this->conn, 'database'))
        {
            $this->conn->close();
        }
    }
    public static function getInstance()
    {
        if (is_null(self::$conn))
        {
            self::$conn = new database('127.0.0.1', 'sviluppo', 'sviluppo', 'dati');
        }
        return self::$conn;
    }
    public function query($sql)
    {
        $esito = parent::query($sql);
        if ($esito === false)
        {
            var_dump($this->error);
            throw new Exception('Errore query DB', 3);
        }
        return $esito;
    }
}