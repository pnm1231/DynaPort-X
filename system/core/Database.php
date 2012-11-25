<?php

/*
 * This file is part of the DynaPort X package.
 *
 * (c) Prasad Nayanajith <prasad.n@dynamiccodes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 * 
 */

class Database extends PDO {
    
    private $_lastInsertId;
    private $_affectedRows;

    public function __construct($DB_TYPE,$DB_HOST,$DB_NAME,$DB_USER,$DB_PASS){
        try{
            parent::__construct($DB_TYPE.':host='.$DB_HOST.';dbname='.$DB_NAME,$DB_USER,$DB_PASS);
        }catch(Exception $e){
            new Error_Controller('Error with the database connection',500,$e);
        }
    }

    /**
    * select - extended way
    * 
    * @param string $sql An SQL string
    * @param array $array Paramters to bind
    * @param constant $fetchMode A PDO Fetch mode
    * @return mixed An array with results
    */
    public function selectExtend($sql,$array=array(),$fetchMode=PDO::FETCH_ASSOC){
        $sth = $this->prepare($sql);
        if(is_array($array) && count($array)>0){
            foreach ($array as $key => $value) {
                $sth->bindValue($key, $value);
            }
        }

        $sth->execute();
        return $sth->fetchAll($fetchMode);
    }
    
    /**
     * select
     * 
     * @param string $fields Fields to return (* or field1,field2)
     * @param string $table The name of the table
     * @param array $array An associative array of data for WHERE
     * @param array $order Order by (field name, type)
     * @param mixed $limit Limit
     * @param constant $fetchMode A PDO Fetch mode
     * @return mixed An array with results
     */
    public function select($fields,$table,$array=array(),$group=array(),$order=array(),$limit=0,$fetchMode=PDO::FETCH_ASSOC){
        $sql = "SELECT {$fields} FROM {$table}";
        
        if(is_array($array) && count($array)>0){
            $sql.= ' WHERE ';
            foreach($array as $key=>$value){
                if(preg_match('/%/',$value)){
                    $equal = ' LIKE ';
                }else{
                    $equal = '=';
                }
                $sql.= "`{$key}`{$equal}:{$key} AND ";
            }
            $sql = rtrim($sql,' AND ');
        }
        
        if(is_array($group) && count($group)>0){
            $group = implode(',',$group);
            $group = preg_replace('/[^a-z0-9_\(\),]+/i','',$group);
            $sql.= " GROUP BY {$group}";
        }
        
        if(is_array($order) && count($order)==1){
            $orderArrKeys = array_keys($order);
            $orderArr[0] = $orderArrKeys[0];
            if($orderArr[0]=='0'){
                $orderArr[0] = $order[0];
                $orderArr[0] = preg_replace('/[^a-z0-9_\(\)]+/i','',$orderArr[0]);
            }else{
                $orderArr[1] = $order[$orderArr[0]];
                $orderArr[0] = preg_replace('/[^a-z0-9_\(\),]+/i','',$orderArr[0]);
                $orderArr[1] = preg_replace('/[^a-z0-9_\(\)]+/i','',$orderArr[1]);
            }
            $order = (count($orderArr)>1)?implode(' ',$orderArr):$orderArr[0];
            $sql.= " ORDER BY {$order}";
        }
        
        if(is_array($limit) && count($limit)==2){
            $sql.= " LIMIT {$limit[0]},{$limit[1]}";
        }else if(!is_array($limit) && $limit>0){
            $sql.= " LIMIT 0,{$limit}";
        }
        
        $sth = $this->prepare($sql);
        if(is_array($array) && count($array)>0){
            foreach($array as $key=>$value){
                $sth->bindValue($key,$value);
            }
        }

        $sth->execute();
        return $sth->fetchAll($fetchMode);
    }

    /**
    * insert
    * 
    * @param string $table A name of table to insert into
    * @param array $data An associative array
    * @return bool true/false
    */
    public function insert($table,$data){
        $fieldNames = implode('`, `',array_keys($data));
        $fieldValues = ':'.implode(', :',array_keys($data));

        $sth = $this->prepare("INSERT INTO $table (`{$fieldNames}`) VALUES ({$fieldValues})");

        foreach ($data as $key=>$value) {
            $value = htmlspecialchars(stripslashes($value),ENT_QUOTES);
            $sth->bindValue(":$key", $value);
        }

        $return = $sth->execute();
        $this->_lastInsertId = $this->lastInsertId();
        return $return;
    }

    /**
    * update
    * 
    * @param string $table Name of the table to insert into
    * @param array $data An associative array of data to be modified
    * @param mixed $where An associative array or the WHERE query part
    * @return bool true/false
    */
    public function update($table,$data,$where=array()){
        
        $fieldDetails = NULL;
        if(is_array($data) && count($data)>0){
            foreach($data as $key=>$value) {
                $fieldDetails .= "`{$key}`=:{$key},";
            }
            $fieldDetails = rtrim($fieldDetails,',');
        }
        
        if(is_array($where) && count($where)>0){
            $whereSQL = 'WHERE ';
            if(is_array($where) && count($where)>0){
                foreach($where AS $key=>$value){
                    $whereSQL.= "`{$key}`=:where_{$key},";
                }
                $whereSQL = rtrim($whereSQL,',');
            }else if(!is_array($where)){
                $whereSQL.= $where;
            }
        }
        
        $sth = $this->prepare("UPDATE {$table} SET {$fieldDetails} {$whereSQL}");

        if(is_array($data) && count($data)>0){
            foreach ($data as $key=>$value) {
                $value = htmlspecialchars(stripslashes($value),ENT_QUOTES);
                $sth->bindValue(":{$key}",$value);
            }
        }

        if(is_array($where) && count($where)>0){
            foreach ($where as $key => $value) {
                $value = htmlspecialchars(stripslashes($value),ENT_QUOTES);
                $sth->bindValue(":where_{$key}", $value);
            }
        }

        $return = $sth->execute();
        $this->_affectedRows = $sth->rowCount();
        return $return;
    }

    /**
    * delete
    * 
    * @param string $table Name of the table to delete from
    * @param string $where An associative array of data for WHERE
    * @param integer $limit Limit results
    * @return integer Affected rows
    */
    public function delete($table,$where,$limit=1){
        
        if(is_array($where) && count($where)>0){
            $whereSQL = 'WHERE ';
            if(is_array($where) && count($where)>0){
                foreach($where AS $key=>$value){
                    $whereSQL.= "`{$key}`=:{$key},";
                }
                $whereSQL = rtrim($whereSQL,',');
            }else if(!is_array($where)){
                $whereSQL.= $where;
            }
        }

        $sth = $this->prepare("DELETE FROM {$table} {$whereSQL} LIMIT {$limit}");

        if(is_array($where) && count($where)>0){
            foreach ($where as $key => $value) {
                $value = htmlspecialchars(stripslashes($value),ENT_QUOTES);
                $sth->bindValue(":{$key}", $value);
            }
        }

        $sth->execute();
        $this->_affectedRows = $sth->rowCount();
        return $this->_affectedRows;
    }
    
    /**
     * last inserted row ID
     * 
     * @return integer ID
     */
    function getLastId(){
        return $this->_lastInsertId;
    }
    
    /**
     * affected rows
     * 
     * @return integer No. of rows
     */
    function getAffectedRows(){
        return $this->_affectedRows;
    }

}

?>