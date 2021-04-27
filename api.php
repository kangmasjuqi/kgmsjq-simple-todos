<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todo";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

function create_data($conn, $array_data){
    $fields = '';
    $values = '';

    $array_data['ordering'] = 0;
    $array_data['created_at'] = date("Y-m-d H:i:s");

    foreach($array_data as $f => $v){
        if($values!=='')
            $values.= ', ';
        if($fields!=='')
            $fields.= ', ';

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

function update_data($conn, $id, $array_changed_data){
    $parsed_new_data = '';
    foreach($array_changed_data as $f => $v){
        if($parsed_new_data!=='')
            $parsed_new_data.= ', ';
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

    $sql = "select * from todos ".$where." order by id desc";

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
    $conn->close();
    return $result;
}

function jsonify($data){
    return json_encode($data);
}


if( 'retrieve' === $_GET['f'] && 'latest_entry' == $_GET['d'])
    get_latest_entry($conn);

else if( 'retrieve' === $_GET['f'] && 'all' == $_GET['d'])
    get_all($conn);

else if( 'retrieve' === $_GET['f'] && is_numeric($_GET['d'])===TRUE)
    get_one($conn, $_GET['d']);

else if( 'update' === $_GET['f'] && is_numeric($_GET['d'])===TRUE )
    update($conn, $_POST['id'], $_POST['changed_data']);

else if( 'create' === $_GET['f'] && is_numeric($_GET['d'])===TRUE ){
    create($conn, $_POST);
}

function get_latest_entry($conn){
    $sql = "select * from todos order by id desc";
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

function create($conn, $new_data){
    $result = create_data($conn, $new_data);
    echo jsonify($new_data);        
}

?>