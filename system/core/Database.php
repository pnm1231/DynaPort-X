<?php

/**
 * DynaPort X
 *
 * A simple yet powerful PHP framework for rapid application development.
 *
 * Licensed under BSD license
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    DynaPort X
 * @copyright  Copyright (c) 2012-2013 DynamicCodes.com (http://www.dynamiccodes.com/dynaportx)
 * @license    http://www.dynamiccodes.com/dynaportx/license   BSD License
 * @link       http://www.dynamiccodes.com/dynaportx
 * @since      File available since Release 0.2.0
 */

/**
 * Database Class
 *
 * The database class which handles all SQL queries.
 *
 * @package     DynaPort X
 * @subpackage  Core
 * @category    Core
 * @author      Prasad Nayanajith
 * @link        http://www.dynamiccodes.com/dynaportx/doc/core/database
 */
class Database extends PDO {
    
    /**
     * Last inserted ID
     * 
     * @var integer
     */
    private $_lastInsertId;
    
    /**
     * Affected rows count
     * 
     * @var integer
     */
    private $_affectedRows;
    
    /**
     * Whether a transaction has initiated
     * 
     * @var bool
     */
    private $_txStarted = false;
    
    /**
     * Failed transactions count
     * 
     * @var integer
     */
    private $_txFailedCount = 0;
    
    /**
     * PDO Fetch Mode
     * 
     * @var constant
     */
    private $_gbFetchMode = PDO::FETCH_ASSOC;
    
    /**
     * Query builder type
     * 
     * @var string select/update/insert/delete
     */
    private $_qbType;
    
    /**
     * Query
     * 
     * @var array
     */
    private $_qbQuery = array();
    
    /**
     * Binding values
     * 
     * @var array
     */
    private $_qbBinds = array();
    
    /**
     * Statement handler
     */
    private $_qbSTH;

    /**
     * 
     * @param string $DB_TYPE Database type (mysql/mssql/etc.)
     * @param string $DB_HOST Database location
     * @param string $DB_NAME Database name
     * @param string $DB_USER Database Username
     * @param string $DB_PASS Database Password
     */
    public function __construct($DB_TYPE,$DB_HOST,$DB_NAME,$DB_USER,$DB_PASS){
        try{
            parent::__construct($DB_TYPE.':host='.$DB_HOST.';dbname='.$DB_NAME,$DB_USER,$DB_PASS);
        }catch(Exception $e){
            new Error('Error with the database connection',500,$e);
        }
    }
    
    /**
     * SELECT statement
     * 
     * @param mixed $columns Column(s)
     * @return \Database
     */
    function select($columns='*'){
        if(is_array($columns) && count($columns)>0){
            $select = '`'.implode('`,`',$columns).'`';
        }else if($columns!='*'){
            $select = '`'.str_replace(',','`,`',$columns).'`';
        }else{
            $select = $columns;
        }
        if(preg_match('@(\(|as)@i',$select)){
            $select = preg_replace('@\ AS ([a-z0-9_-]+)`@i','` AS `$1`',$select);
            $select = preg_replace('@`([a-z]+)\(([a-z0-9_-]+)\)([^`]?)`@i','$1(`$2`)$3',$select);
        }
        $this->_qbQuery['select'] = str_replace('.','`.`',$select);
        $this->_qbType = 'select';
        return $this;
    }
    
    /**
     * FROM statement
     * 
     * @param mixed $table Table name(s)
     * @return \Database
     */
    function from($table){
        if(is_array($table) && count($table)>0){
            $from = implode(',',$table);
        }else{
            $from = $table;
        }
        $this->_qbQuery['from'][] = $from;
        return $this;
    }
    
    /**
     * WHERE statement
     * 
     * @param string $column Column name
     * @param string $value Value to find
     * @param string $operator Operator type
     * @return \Database
     */
    function where($column,$value,$operator='='){
        $column_safe = str_replace('.','_',$column);
        $column = str_replace('.','`.`',$column);
        $count = isset($this->_qbQuery['where'])?count($this->_qbQuery['where']):0;
        
        // IN operator
        if(strtolower($operator)=='in'){
            $operator = ' IN ';
            $value_key = '(';
            if(is_array($value) && count($value)>0){
                foreach($value AS $k=>$v){
                    $value_key.= ':i'.$count.'_'.$k.'_'.$column_safe.',';
                    $this->_qbBinds['i'.$count.'_'.$k.'_'.$column_safe] = $v;
                }
                $value_key = rtrim($value_key,',');
            }else{
                $value_key.= ':i'.$count.'_'.$column_safe;
                $this->_qbBinds['i'.$count.'_'.$column_safe] = $value;
            }
            $value_key.= ')';
            
        // BETWEEN operator
        }else if(strtolower($operator)=='between'){
            $operator = ' BETWEEN ';
            $value_key = ':b'.$count.'_0_'.$column_safe;
            $value_key.= ' AND ';
            $value_key.= ':b'.$count.'_1_'.$column_safe;
            $this->_qbBinds['b'.$count.'_0_'.$column_safe] = $value[0];
            $this->_qbBinds['b'.$count.'_1_'.$column_safe] = $value[1];
            
        // EQUAL operator
        }else{
            // LIKE and NOT LIKE operator
            if(strtolower($operator)=='like' || strtolower($operator)=='not like'){
                $operator = ' '.strtoupper($operator).' ';
            }
            $value_key = ':w'.$count.'_'.$column_safe;
            $this->_qbBinds['w'.$count.'_'.$column_safe] = $value;
        }
        
        $this->_qbQuery['where'][] = '`'.$column.'`'.$operator.$value_key.' AND ';
        
        return $this;
    }
    
    /**
     * Open bracket
     * 
     * @return \Database
     */
    function where_open(){
        $this->_qbQuery['where'][] = '(';
        return $this;
    }
    
    /**
     * Close bracket
     * 
     * @return \Database
     */
    function where_close(){
        $this->_qbQuery['where'][] = ') AND ';
        return $this;
    }
    
    /**
     * AND operator
     * 
     * @return \Database
     */
    function where_and(){
        $this->_qbQuery['where'][] = ' AND ';
        return $this;
    }
    
    /**
     * OR operator
     * 
     * @return \Database
     */
    function where_or(){
        $this->_qbQuery['where'][count($this->_qbQuery['where'])-1] = rtrim($this->_qbQuery['where'][count($this->_qbQuery['where'])-1],' AND');
        $this->_qbQuery['where'][] = ' OR ';
        return $this;
    }
    
    /**
     * JOIN Statement
     * 
     * @param string $column1 Column 1
     * @param string $column2 Column 2
     * @return \Database
     */
    function join($column1,$column2){
        $column1 = str_replace('.','`.`',$column1);
        $column2 = str_replace('.','`.`',$column2);
        $this->_qbQuery['where'][] = '`'.$column1.'`=`'.$column2.'` AND';
        return $this;
    }
    
    /**
     * RIGHT JOIN statement
     * 
     * @param string $table Table name
     * @param string $column1 Column 1
     * @param string $column2 Column 2
     * @return \Database
     */
    function right_join($table,$column1,$column2){
        $column1 = str_replace('.','`.`',$column1);
        $column2 = str_replace('.','`.`',$column2);
        $this->_qbQuery['from'][] = 'RIGHT JOIN `'.$table.'` ON `'.$column1.'`=`'.$column2.'`';
        return $this;
    }
    
    /**
     * LEFT JOIN statement
     * 
     * @param string $table Table name
     * @param string $column1 Column 1
     * @param string $column2 Column 2
     * @return \Database
     */
    function left_join($table,$column1,$column2){
        $column1 = str_replace('.','`.`',$column1);
        $column2 = str_replace('.','`.`',$column2);
        $this->_qbQuery['from'][] = 'LEFT JOIN `'.$table.'` ON `'.$column1.'`=`'.$column2.'`';
        return $this;
    }
    
    function inner_join($table,$column1,$column2){
        $column1 = str_replace('.','`.`',$column1);
        $column2 = str_replace('.','`.`',$column2);
        $this->_qbQuery['from'][] = 'INNER JOIN `'.$table.'` ON `'.$column1.'`=`'.$column2.'`';
        return $this;
    }
    
    /**
     * GROUP BY statement
     * 
     * @param mixed $column Column(s)
     * @return \Database
     */
    function group($column){
        if(is_array($column) && count($column)>0){
            $group = implode('`,`',$column);
        }else{
            $group = str_replace(',','`,`',$column);
        }
        $group = str_replace('.','`.`',$group);
        $this->_qbQuery['group'] = '`'.$group.'`';
        return $this;
    }
    
    /**
     * ORDER BY statement
     * 
     * @param mixed $column Column(s) or RAND
     * @param string $type ASC or DESC
     * @return \Database
     */
    function order($column,$type='ASC'){
        // Check whether the order is for random
        if(!is_array($column) && strtolower($column)=='rand'){
            $this->_qbQuery['order'][] = 'RAND()';
            
        // If not random...
        }else{
            // Check whether an array was provided (multiple fields)
            if(is_array($column) && count($column)>0){
                $orderArr = '';
                $type = '';
                foreach($column AS $col=>$ord){
                    $orderArr[] = '`'.$col.'` '.strtoupper($ord);
                }
                $order = implode(',',$orderArr);
                
            // If not, use the simple 'column TYPE' approach
            }else{
                $order = str_replace(',','`,`',$column);
                $order = '`'.$order.'` '.strtoupper($type);
            }
            
            // Break table names and fields properly
            $order = str_replace('.','`.`',$order);
            
            // Add it to the query builder
            $this->_qbQuery['order'][] = $order;
        }
        return $this;
    }
    
    /**
     * OFFSET statement
     * 
     * @param integer $num Start from
     * @return \Database
     */
    function offset($num){
        if(is_numeric($num) && $num>0){
            $this->_qbQuery['offset'] = $num;
        }
        return $this;
    }
    
    /**
     * LIMIT statement
     * 
     * @param integer $num No. of records
     * @return \Database
     */
    function limit($num){
        if(is_numeric($num) && $num>0){
            $this->_qbQuery['limit'] = $num;
        }
        return $this;
    }
    
    /**
     * UPDATE statement
     * 
     * @param string $table Table name
     * @return \Database
     */
    function update($table){
        $this->_qbQuery['update'] = $table;
        $this->_qbType = 'update';
        return $this;
    }
    
    /**
     * SET statement
     * 
     * @param string $column Column name
     * @param string $value Value to update
     * @return \Database
     */
    function set($column,$value){
        $column_safe = str_replace('.','_',$column);
        $this->_qbQuery['set'][] = '`'.str_replace('.','`.`',$column).'`=:s_'.$column_safe;
        $this->_qbBinds['s_'.$column_safe] = $value;
        return $this;
    }
    
    /**
     * SET using an Array
     * 
     * @param array $values An associative array of data
     * @return \Database
     */
    function values($values){
        if(is_array($values) && count($values)>0){
            foreach($values AS $column=>$value){
                $this->set($column,$value);
            }
        }
        return $this;
    }
    
    /**
     * DELETE statement
     * 
     * @param string $table Table name
     * @return \Database
     */
    function delete($table=null){
        $this->_qbQuery['delete'] = true;
        $this->_qbType = 'delete';
        if($table){
            $this->from($table);
        }
        return $this;
    }
    
    /**
     * INSERT INTO statement
     * 
     * @param string $table Table name
     * @return \Database
     */
    function insert($table){
        $this->_qbQuery['insert'] = $table;
        $this->_qbType = 'insert';
        return $this;
    }
    
    /**
     * Execute the query
     * 
     * @return \Database
     */
    function run(){
        switch($this->_qbType){
            case 'insert':
                $query = 'INSERT INTO '.$this->_qbQuery['insert'];
                break;
            case 'update':
                $query = 'UPDATE '.$this->_qbQuery['update'];
                break;
            case 'delete':
                $query = 'DELETE';
                break;
            default:
                $query = 'SELECT '.$this->_qbQuery['select'];
                break;
        }
        
        if(isset($this->_qbQuery['set'])){
            $query.= ' SET '.implode(',',$this->_qbQuery['set']);
        }
        
        if(isset($this->_qbQuery['from'])){
            $query.= ' FROM '.implode(' ',$this->_qbQuery['from']);
        }
        
        if(isset($this->_qbQuery['where'])){
            $query.= ' WHERE ';
            foreach($this->_qbQuery['where'] AS $v){
                $query.= $v;
            }
            $pat[] = '@(\s+)(and|or)(\s+)\)@i';
            $pat[] = '@(\s+)(and|or)(\s*)$@i';
            $rep[] = ')';
            $rep[] = '';
            $query = preg_replace($pat,$rep,$query);
        }
        
        if(isset($this->_qbQuery['group'])){
            $query.= ' GROUP BY '.$this->_qbQuery['group'];
        }
        
        if(isset($this->_qbQuery['order'])){
            $query.= ' ORDER BY ';
            $query.= implode(',',$this->_qbQuery['order']);
        }
        
        if(isset($this->_qbQuery['limit'])){
            $query.= ' LIMIT '.$this->_qbQuery['limit'];
        }
        
        if(isset($this->_qbQuery['offset'])){
            $query.= ' OFFSET '.$this->_qbQuery['offset'];
        }
        
        $this->_qbSTH = $this->prepare($query);
        if(is_array($this->_qbBinds) && count($this->_qbBinds)>0){
            foreach($this->_qbBinds as $k=>$v){
                $this->_qbSTH->bindValue($k,$v);
            }
        }

        try {
            $execute = $this->_qbSTH->execute();
        }catch(Exception $e){
            new Error('Error with the database!',500,'DPX.Database.run: '.$e);
        }
        
        if($execute==false){
            if($this->_txStarted==true){
                $this->_txFailedCount++;
            }else{
                throw new Exception('The query returned false!');
            }
        }
        
        if($this->_qbType=='select'){
            $this->reset();
            return $this;
        }else{
            if($this->_qbType=='insert'){
                $this->_lastInsertId = $this->lastInsertId();
            }else if($this->_qbType=='update' || $this->_qbType=='delete'){
                $this->_affectedRows = $this->_qbSTH->rowCount();
            }
            $this->reset();
            return $execute;
        }
    }
    
    /**
     * Fetch one row
     * 
     * @return array Results
     */
    function fetchOne(){
        $this->run();
        return $this->_qbSTH->fetch($this->_gbFetchMode);
    }
    
    /**
     * Fetch all rows
     * 
     * @return array Results
     */
    function fetch(){
        $this->run();
        return $this->_qbSTH->fetchAll($this->_gbFetchMode);
    }
    
    /**
     * Last inserted ID
     * 
     * @return integer ID
     */
    function id(){
        return $this->_lastInsertId;
    }
    
    /**
     * Affected rows
     * 
     * @return integer No. of rows
     */
    function affected(){
        return $this->_affectedRows;
    }
    
    /**
     * Reset query builder
     */
    private function reset(){
        $this->_qbBinds = null;
        $this->_qbQuery = null;
        $this->_qbType = null;
    }
    
    /**
     * Initiate a transaction
     * 
     * @return boolean
     */
    function txStart(){
        if($this->_txStarted){
            return false;
        }else{
            $this->_txStarted = $this->beginTransaction();
            return $this->_txStarted;
        }
    }
    
    /**
     * End a transaction
     * 
     * @return boolean
     */
    function txEnd(){
        $result = false;
        if($this->_txStarted){
            if($this->_txFailedCount==0){
                $this->commit();
                $result = true;
            }else{
                $this->rollBack();
                $this->_txFailedCount = 0;
            }
            $this->_txStarted = false;
        }
        return $result;
    }

    /**
    * Select - raw query with binding
    * 
    * @param string $sql An SQL string
    * @param array $array Paramters to bind
    * @param constant $fetchMode A PDO Fetch mode
    * @return mixed An array with results
    */
    public function select_raw($sql,$array=array(),$fetchMode=PDO::FETCH_ASSOC){
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
     * Select - extend using parameters for each
     * 
     * @param string $fields Fields to return (* or field1,field2)
     * @param string $table The name of the table
     * @param array $array An associative array of data for WHERE
     * @param array $order Order by (field name, type)
     * @param mixed $limit Limit
     * @param constant $fetchMode A PDO Fetch mode
     * @return mixed An array with results
     */
    public function select_extend($fields,$table,$array=array(),$group=array(),$order=array(),$limit=0,$fetchMode=PDO::FETCH_ASSOC){
        $sql = "SELECT {$fields} FROM {$table}";
        
        if(is_array($array) && count($array)>0){
            $sql.= ' WHERE ';
            foreach($array as $key=>$value){
                if(preg_match('/%%/',$value)){
                    $value = preg_replace('/%%/','',$value);
                    $sql.= "`{$key}`=`{$value}` AND ";
                }else{
                    if(preg_match('/%/',$value)){
                        $equal = ' LIKE ';
                    }else{
                        $equal = '=';
                    }
                    $sql.= "`{$key}`{$equal}:{$key} AND ";
                }
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
                if(!preg_match('/%%/',$value)){
                    $sth->bindValue($key,$value);
                }
            }
        }

        $sth->execute();
        return $sth->fetchAll($fetchMode);
    }

    /**
    * Insert - extend
    * 
    * @param string $table A name of table to insert into
    * @param array $data An associative array
    * @return bool true/false
    */
    public function insert_extend($table,$data){
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
    * Update - extend
    * 
    * @param string $table Name of the table to insert into
    * @param array $data An associative array of data to be modified
    * @param mixed $where An associative array or the WHERE query part
    * @return bool true/false
    */
    public function update_extend($table,$data,$where=array()){
        
        $fieldDetails = NULL;
        if(is_array($data) && count($data)>0){
            foreach($data as $key=>$value) {
                $fieldDetails .= "`{$key}`=:{$key},";
            }
            $fieldDetails = rtrim($fieldDetails,',');
        }
        
        $whereSQL = '';
        if(is_array($where) && count($where)>0){
            $whereSQL = 'WHERE ';
            if(is_array($where) && count($where)>0){
                foreach($where AS $key=>$value){
                    $whereSQL.= "`{$key}`=:where_{$key} AND ";
                }
                $whereSQL = rtrim($whereSQL,' AND ');
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
    * Delete - extend
    * 
    * @param string $table Name of the table to delete from
    * @param string $where An associative array of data for WHERE
    * @param integer $limit Limit results
    * @return integer Affected rows
    */
    public function delete_extend($table,$where,$limit=1){
        
        if(is_array($where) && count($where)>0){
            $whereSQL = 'WHERE ';
            if(is_array($where) && count($where)>0){
                foreach($where AS $key=>$value){
                    $whereSQL.= "`{$key}`=:{$key} AND";
                }
                $whereSQL = rtrim($whereSQL,' AND');
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
    
    public function raw($sql,$array=array()){
        $sth = $this->prepare($sql);
        if(is_array($array) && count($array)>0){
            foreach ($array as $key => $value) {
                $sth->bindValue($key, $value);
            }
        }

        $execute = $sth->execute();
        $this->_affectedRows = $sth->rowCount();
        return $execute;
    }

}

?>