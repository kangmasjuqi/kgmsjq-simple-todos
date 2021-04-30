<?php 

/* database configuration */
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todo";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

function create_data($conn, $array_data, $highest_ordering = 0){
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
    if ($conn->query($sql) === TRUE) {
      $id = $conn->insert_id;
    }

    $result = get_data($conn, $id);
    return $result;
}

function update_data_ordering($conn, $todo_id_array){    
    $order = 1;
    $result = [];
    $todo_id_array = array_reverse($todo_id_array);
    foreach ($todo_id_array as $id) {
        if(is_numeric($id)){
            $sql = "update todos set ordering=".$order." where id='".(int)$id."'";
            $conn->query($sql);
            $result[$id] = $order;
            $order++;
        }
    }
    return $result;
}

function update_data($conn, $id, $array_changed_data){
    $parsed_new_data = '';
    foreach($array_changed_data as $f => $v){
        if($parsed_new_data!=='')
            $parsed_new_data.= ', ';

        $v = addslashes($v);
        
        $parsed_new_data= $f."='".$v."'";
    }
    $sql = "update todos set ".$parsed_new_data." where id='".(int)$id."'";
    $conn->query($sql);
    $result = get_data($conn, $id);
    return $result;
}

function get_data($conn, $id = ''){

    $where = "";
    if($id !== '')
        $where = " WHERE id='".(int)$id."'";

    $sql = "select * from todos ".$where." order by ordering desc";

    $raw = $conn->query($sql);
    $result = [];
    if ($id !== '') {
      return $raw->fetch_assoc();
    }
    else {
      while($row = $raw->fetch_assoc()) {
        $result[] = $row;
      }
    }
    $conn->close();
    return $result;
}

function get_raw_data($conn, $sql){

    $raw = $conn->query($sql);
    $result = $raw->fetch_assoc();
    return $result;
}

function jsonify($data){
    return json_encode($data);
}


if( 'retrieve' === $_GET['f'] && 'highest_ordering' == $_GET['d'])
    get_highest_ordering_todo($conn);

else if( 'retrieve' === $_GET['f'] && 'all' == $_GET['d'])
    get_all($conn);

else if( 'retrieve' === $_GET['f'] && is_numeric($_GET['d'])===TRUE)
    get_one($conn, $_GET['d']);

else if( 'update' === $_GET['f'] && is_numeric($_GET['d'])===TRUE )
    update($conn, $_POST['id'], $_POST['changed_data']);

else if( 'sort' === $_GET['f'] && 'all' == $_GET['d'])
    update_ordering($conn, $_POST['todo_id_array']);

else if( 'create' === $_GET['f'] && is_numeric($_GET['d'])===TRUE ){
    create($conn, $_POST);
}

function get_highest_ordering_todo($conn){
    $sql = "select * from todos order by ordering desc";
    $result = get_raw_data($conn, $sql);
    echo jsonify($result);        
}

function get_all($conn){
    $result = get_data($conn);
    echo jsonify($result);
}

function get_one($conn, $id){
    $result = get_data($conn, (int)$id);
    echo jsonify($result);        
}

function update($conn, $id, $changed_data){
    $result = update_data($conn, (int)$id, $changed_data);
    echo jsonify($result);
}

function update_ordering($conn, $todo_id_array){
    $result = update_data_ordering($conn, $todo_id_array);
    echo jsonify($result);
}

function create($conn, $new_data){
    $result = [];
    if($new_data['title']!=='' && $new_data['content']!==''){

        $sql_order = "select max(ordering) as max_ordering from todos";
        $raw_highest_ordering = get_raw_data($conn, $sql_order);
        $highest_ordering = 0;
        if(!empty($raw_highest_ordering) && $raw_highest_ordering['max_ordering']!==NULL){
            $highest_ordering = $raw_highest_ordering['max_ordering'];
        }

        $result = create_data($conn, $new_data, $highest_ordering);
    }
    echo jsonify($result);        
}

?>