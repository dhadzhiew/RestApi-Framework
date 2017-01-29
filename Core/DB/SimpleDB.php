<?php


namespace Core\DB;

use Core\App;

class SimpleDB
{
    /**
     * @var App
     */
    private $app;
    /**
     * @var \PDO
     */
    private $db;

    public function __construct()
    {
        $this->app = App::getInstance();
        $this->db = $this->app->getDbConnection();
    }

    /**
     * @return \PDO
     */
    public function getDB()
    {
        return $this->db;
    }

    /**
     * @param $sql
     * @param array $params
     * @param array $pdoOptions
     * @return $this
     */
    public function prepare($sql, $params = [], $pdoOptions = [])
    {
        $this->stmt = $this->db->prepare($sql, $pdoOptions);
        $this->params = $params;
        $this->sql = $sql;
        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function execute($params =[])
    {
        if ($params) {
            $this->params = $params;
        }
        $this->stmt->execute($this->params);
        return $this;
    }

    /**
     * @return mixed
     */
    public function fetchAllAssoc()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @return mixed
     */
    public function fetchRowAssoc()
    {
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * @return string
     */
    public function getLastInsertId()
    {
        return $this->db->lastInsertId();
    }

    /**
     * @return mixed
     */
    public function getAffectedRows()
    {
        return $this->stmt->rowCount();
    }

    /**
     * @return mixed
     */
    public function getSTMT()
    {
        return $this->stmt;
    }
}