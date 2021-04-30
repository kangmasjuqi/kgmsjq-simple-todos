<?php 
/*
    File : api.php
    Project : kgmsjq-simple-todos

    Copyright (c) 2021 kangmasjuqi

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all
    copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
    SOFTWARE.
 */

class Api{

    /* database configuration */
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "todo";
    private $conn = null;

    public function __construct(){
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    private function create_data($array_data, $highest_ordering = 0){
        $fields = '';
        $values = '';
        $array_data['ordering'] = $highest_ordering + 1;
        $array_data['created_at'] = date("Y-m-d H:i:s");
        foreach($array_data as $f => $v){
            if($values!=='')
                $values.= ', ';
            if($fields!=='')
                $fields.= ', ';
            $v = addslashes($v);
            $fields .= "`".$f."`";
            $values .= "'".$v."'";
        }
        $sql = "insert into todos  (".$fields.") values (".$values.")";
        $id = 0;
        if ($this->conn->query($sql) === TRUE) {
          $id = $this->conn->insert_id;
        }
        $result = $this->get_data($id);
        return $result;
    }

    private function update_data_ordering($todo_id_array){    
        $order = 1;
        $result = [];
        $todo_id_array = array_reverse($todo_id_array);
        foreach ($todo_id_array as $id) {
            if(is_numeric($id)){
                $sql = "update todos set ordering=".$order." where id='".(int)$id."'";
                $this->conn->query($sql);
                $result[$id] = $order;
                $order++;
            }
        }
        return $result;
    }

    private function update_data($id, $array_changed_data){
        $parsed_new_data = '';
        foreach($array_changed_data as $f => $v){
            if($parsed_new_data!=='')
                $parsed_new_data.= ', ';
            $v = addslashes($v);            
            $parsed_new_data= $f."='".$v."'";
        }
        $sql = "update todos set ".$parsed_new_data." where id='".(int)$id."'";
        $this->conn->query($sql);
        $result = $this->get_data($id);
        return $result;
    }

    private function get_data($id = ''){
        $where = "";
        if($id !== ''){
            $where = " WHERE id='".(int)$id."'";
        }
        $sql = "select * from todos ".$where." order by ordering desc";
        $raw = $this->conn->query($sql);
        $result = [];
        if ($id !== '') {
          return $raw->fetch_assoc();
        }
        else {
          while($row = $raw->fetch_assoc()) {
            $result[] = $row;
          }
        }
        return $result;
    }

    private function delete_data($id){
        $sql = "delete from todos where id='".(int)$id."'";
        if($this->conn->query($sql)===true)
            return TRUE;
        else 
            return FALSE;
    }

    private function get_raw_data($sql){
        $raw = $this->conn->query($sql);
        $result = $raw->fetch_assoc();
        return $result;
    }

    private function jsonify($data){
        return json_encode($data);
    }

    public function get_highest_ordering_todo(){
        $sql = "select * from todos order by ordering desc";
        $result = $this->get_raw_data($sql);
        echo $this->jsonify($result);        
    }

    public function get_all(){
        $result = $this->get_data();
        echo $this->jsonify($result);
    }

    public function get_one($id){
        $result = $this->get_data((int)$id);
        echo $this->jsonify($result);        
    }

    public function delete($id){
        $result = $this->delete_data((int)$id);
        echo $this->jsonify($result);        
    }

    public function update($id, $changed_data){
        $result = $this->update_data((int)$id, $changed_data);
        echo $this->jsonify($result);
    }

    public function update_ordering($todo_id_array){
        $result = $this->update_data_ordering($todo_id_array);
        echo $this->jsonify($result);
    }

    public function create($new_data){
        $result = [];
        if($new_data['title']!=='' && $new_data['content']!==''){
            $sql_order = "select max(ordering) as max_ordering from todos";
            $raw_highest_ordering = $this->get_raw_data($sql_order);
            $highest_ordering = 0;
            if(!empty($raw_highest_ordering) && $raw_highest_ordering['max_ordering']!==NULL){
                $highest_ordering = $raw_highest_ordering['max_ordering'];
            }
            $result = $this->create_data($new_data, $highest_ordering);
        }
        echo $this->jsonify($result);        
    }

}

/////////////////////////////////
///// routes

if(array_key_exists('f', $_GET)===false
    || array_key_exists('d', $_GET)===false)
    die("you are not authorized to access this api");

$api = new Api();

if( 'retrieve' === $_GET['f'] && 'highest_ordering' == $_GET['d'])
    $api->get_highest_ordering_todo();

else if( 'retrieve' === $_GET['f'] && 'all' == $_GET['d'])
    $api->get_all();

else if( 'retrieve' === $_GET['f'] && is_numeric($_GET['d'])===TRUE)
    $api->get_one($_GET['d']);

else if( 'create' === $_GET['f'] && is_numeric($_GET['d'])===TRUE )
    $api->create($_POST);

else if( 'delete' === $_GET['f'] && is_numeric($_GET['d'])===TRUE )
    $api->delete($_POST['todo_id']);

else if( 'update' === $_GET['f'] && is_numeric($_GET['d'])===TRUE )
    $api->update($_POST['id'], $_POST['changed_data']);

else if( 'sort' === $_GET['f'] && 'all' == $_GET['d'])
    $api->update_ordering($_POST['todo_id_array']);


?>