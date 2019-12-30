<?php

class Database {
    protected $connection = null;
    protected $table = '';
    protected $statement = null; 

    // protected $limit = 15; //limit 15 dong dau tien;
    // protected $offset = 0; //lay ra tu ban ghi dau tien

    protected $host = '';
    protected $user = '';
    protected $pass = '';
    protected $name = '';

    public function __construct($config) {
        $this->host = $config['host'];
        $this->user = $config['user'];
        $this->pass = $config['pass'];
        $this->name = $config['name'];
        
        $this->connect();
    }

    protected function connect() {
        $this->connection = new mysqli($this->host, $this->user, $this->pass, $this->name);
        if($this->connection->connect_errno) {
            exit($this->connection->connect_error);
        }
    }

    public function table($tableName) {
        $this->table = $tableName;
        return $this;
    }

    public function limit($limit) {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset) {
        $this->offset = $offset;
        return $this;
    }

    //cu moi lan execute xong -> reset table 
    public function resetQuery() {
        $this->table = '';
        $this->limit = 15; //limit 15 dong dau tien;
        $this->offset = 0; //lay ra tu ban ghi dau tien
    }

    public function get() {
        $sql = "SELECT * FROM $this->table LIMIT ? OFFSET ?";
        $this->statement = $this->connection->prepare($sql);
        $this->statement->bind_param('ii', $this->limit, $this->offset);
        $this->statement->execute();
        $this->resetQuery(); //execute xong -> reset

        $result = $this->statement->get_result();
        
        $returnData = [];
        while($row = $result->fetch_object()) {
            $returnData[] = $row;
        }
         return $returnData;
    }
    //function insert
    public function insert($data = []) {
        $fields = implode(',', array_keys($data));
        $questionMarkArr = array_fill(0, count($data), '?');
        $valueStr = implode(',', $questionMarkArr);

        $values = array_values($data);

        $sql = "INSERT INTO $this->table($fields) VALUES($valueStr)";
        
        $this->statement = $this->connection->prepare($sql);
        $this->statement->bind_param(str_repeat('s', count($data)), ...$values);
        $this->statement->execute();
        $this->resetQuery(); //execute xong -> reset

        return $this->statement->affected_rows;
    
    }

    public function updateRow($id, $data = []) {
        //create set fields string
        $keyValues = [];
        foreach($data as $key => $value) {
            $keyValues[] = $key .'=?';
        }
        $setFields = implode(',', $keyValues);

        //get values;
        $values = array_values($data);
        $values[] = $id; //phan tu values cuoi cung = id

        $sql = "UPDATE $this->table SET $setFields WHERE id = ?";

        $this->statement = $this->connection->prepare($sql);
        $dataTypes = str_repeat('s', count($data)). 'i';
        $this->statement->bind_param($dataTypes, ...$values);

        $this->statement->execute();
        $this->resetQuery();

        return $this->statement->affected_rows; //return so luong dong

    }

    public function deleteId($id) {
        $sql = "DELETE FROM $this->table WHERE id = ?";
        $this->statement = $this->connection->prepare($sql);
        $this->statement->bind_param('i', $id);
        $this->statement->execute();
        $this->resetQuery(); //execute xong -> reset

        return $this->statement->affected_rows;
    }
}

?>