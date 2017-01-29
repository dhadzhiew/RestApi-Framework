<?php

namespace Core;
use Core\DB\SimpleDB;

abstract class BaseModel extends SimpleDB
{
    /**
     * @var array
     */
    private $table;
    /**
     * @var array
     */
    private $fields;

    public function __construct($table, $fields = [])
    {
        parent::__construct();
        $this->setTable($table);
        $this->setFields($fields);
    }

    /**
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param string $table
     */
    public function setTable($table)
    {
        if (!$table) {
            throw new \InvalidArgumentException('Table is required.');
        }
        $this->table = $table;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function all($limit = NULL, $start = 0)
    {
        $fieldsSql = $this->getFieldsSql($this->fields);
        $sql = 'SELECT ' . $fieldsSql . ' FROM `' . $this->table . '`';

        if ($limit) {
            $sql .= ' LIMIT ' . $start . ', ' . $limit;
        }
        $data = $this->prepare($sql)
            ->execute()
            ->fetchAllAssoc();

        return $data;
    }

    /**
     * @param $data
     */
    public function add($data)
    {
        $sqlFields = $this->getFieldsSql(array_keys($data));
        $data = $this->transformForPlaceholders($data);
        $valuesPlaceholders = implode(', ', array_keys($data));
        $sql = 'INSERT INTO `' . $this->table . '`(' . $sqlFields . ') VALUES (' . $valuesPlaceholders . ')';
        $this->prepare($sql, $data)
            ->execute();

    }

    /**
     * @param $data
     */
    public function update($data)
    {
        $dataPlaceholders = $this->transformForPlaceholders($data);
        unset($data['id']);
        $fields = array_keys($data);
        $fieldsSql = implode(', ', array_map(function ($row) {
            return $row . ' = :' . $row;
        }, $fields));
        $sql = 'UPDATE `' . $this->table . '` SET ' . $fieldsSql . ' WHERE id = :id';
        $this->prepare($sql, $dataPlaceholders)
            ->execute();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        $fieldsSql = $this->getFieldsSql($this->fields);
        $sql = 'SELECT ' . $fieldsSql . ' FROM ' . $this->table . ' WHERE id = :id LIMIT 0,1';
        return $this->prepare($sql, [':id' => $id])
            ->execute()
            ->fetchRowAssoc();
    }

    /**
     * @param $id
     */
    public function delete($id){
        $sql = 'DELETE FROM `' . $this->table  . '` WHERE id = :id';
        $this->prepare($sql, [':id' => $id])
            ->execute();
    }

    /**
     * @param $data
     * @return array
     */
    private function transformForPlaceholders($data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[':' . $key] = $value;
        }

        return $result;
    }

    /**
     * @param $fields
     * @return string
     */
    private function getFieldsSql($fields)
    {
        $fieldsSql = '*';
        if (!empty($fields)) {
            $fieldsSql = implode(', ', array_map(function ($row) {
                return '`' . $row . '`';
            }, $fields));
        }

        return $fieldsSql;
    }

}