<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<?php

$mysqli = new mysqli('localhost', 'Evgen_admin', 'Enmq8PHRLS', 'Evgen_datchiki');
if (mysqli_connect_errno()) {
    echo "Подключение невозможно: " . mysqli_connect_error();
}

$str = $_GET['str'];
if(isset($_GET['str'])){

$select_number_data=mysqli_query($mysqli,"SELECT * FROM `data_receiving` ORDER BY `data_number` DESC LIMIT 1");
while( $row = $select_number_data->fetch_assoc() ){
    $number=$row['data_number'];
}

    $new_number=$number+=1;
    $now_date=date("Y.m.d");
    $now_time=date("H:i:s");

    $sql = "INSERT INTO data_receiving (data_number,date_receiving,time_receiving)
VALUES ('$new_number','$now_date','$now_time')";

    if ($mysqli->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }

    $s1=explode(",",$str); //данные приходят в виде id1=64,id2=33
    foreach ($s1 as $value){
        $id_dat=substr($value,2,strpos($value,"=")-2);
        $new_value=substr(strstr($value, '='),1,strlen($value));
        $sql = "INSERT INTO sensors (id_sensors, datas ,data_number)
VALUES ('$id_dat','$new_value','$number')";

        if ($mysqli->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
        }
        echo "<br>";
    }

}

?>
</body>

