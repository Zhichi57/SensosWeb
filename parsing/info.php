<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vlazhno</title>
    <link rel="stylesheet" type="text/css" href="../styles/style.css">
</head>
<body>
<?php
$mysqli = new mysqli('localhost', 'Evgen_admin', 'Enmq8PHRLS', 'Evgen_datchiki');
if (mysqli_connect_errno()) {
echo "Подключение невозможно: " . mysqli_connect_error();
}


$json = file_get_contents("https://api.openweathermap.org/data/2.5/onecall?lat=52.9658&lon=36.0803&units=metric&lang=ru&exclude=minutely,hourly&appid=7cd47daeeed484fb4808e74de1cd34ee");
$content = json_decode($json, true);
?>
<div class="weather">
    <?php
/*$temp=$content['current']['temp']; //текущая температура
$humidity=$content['current']['humidity']; //текущая влажность
$dew_point=$content['current']['dew_point']; //текущая точка росы
$rain=$content['current']['rain']['1h']; //текущий объем дождя за последний час, мм
$icon=$content['current']['weather']['0']['icon']; //текущая иконка погоды

echo "<span class='temp'>".$temp."</span>
<span class='humidity'>".$humidity."</span>
<span class='dew_point'>".$dew_point."</span>
<span class='rain'>".$rain."</span>
<span class='icon'>".$icon."</span>";
*/
for ( $i=0; $i<=7; $i++) {
    $daily_temp_day = $content['daily'][$i]['temp']['day']; //средняя температура днём
    $daily_temp_night = $content['daily'][$i]['temp']['night']; //средняя температура ночью
    $daily_humidity = $content['daily'][$i]['humidity']; //влажность
    $daily_dew_point = $content['daily'][$i]['dew_point']; //точка росы
    $daily_rain = $content['daily'][$i]['rain']; //Объем осадков
    $daily_weather_main = $content['daily'][$i]['weather']['0']['main']; //главное тег погоды
    $daily_weather_description = $content['daily'][$i]['weather']['0']['description']; //главное описание погоды
    $daily_weather_icon = $content['daily'][$i]['weather']['0']['icon']; //иконка прогноза
    print ("<span class='daily_temp_day'>".$daily_temp_day."</span>
            <span class='daily_temp_night'>".$daily_temp_night."</span>
            <span class='daily_humidity'>".$daily_humidity."</span>
            <span class='daily_dew_point'>".$daily_dew_point."</span>
            <span class='daily_rain'>".$daily_rain."</span>
            <span class='daily_weather_main'>".$daily_weather_main."</span>
            <span class='daily_weather_description'>".$daily_weather_description."</span>
            <span class='daily_weather_icon'>".$daily_weather_icon."</span>    
            ");
}
?>
</div>

<?php
$select_number_data=mysqli_query($mysqli,"SELECT * FROM `data_receiving` ORDER BY `data_number` DESC LIMIT 1");
while( $row = $select_number_data->fetch_assoc() ){
    $number=$row['data_number'];
    $last_date=$row['date_receiving'];
}

?>

<div class="number">
    <?php
    echo $number;
    ?>
</div>
<div class="last_data">
    <?php
    echo $last_date;
    ?>
</div>
<br>
<?php


    $now_date=date("Y-m-d");
    echo $now_date;
    $geo=$_GET['geo'];
    $select_sensor_geo=mysqli_query($mysqli,"SELECT * FROM `loacation` 
    inner JOIN sensors s on loacation.id_sensors = s.id_sensors 
    INNER join data_receiving dr on s.data_number = dr.data_number WHERE date_receiving='$now_date'");
    while( $row = $select_sensor_geo->fetch_assoc() ) {
        $id_sensor_loc = $row['id_sensors'];
        $location =$row['id_place'];
        $datas=$row['datas'];
        ?>
        <div class="geo">
            <?php
            echo "<span class='id_loc'>".$id_sensor_loc."</span><span class='location'> ".$location."</span><span class='data_loc'> ".$datas."</span>";
            ?>
        </div>
        <?php
    }

if(isset($_GET['date_start']) AND isset($_GET['date_end']) AND isset($_GET['id'])){
    $date_start=$_GET['date_start'];
    $date_end=$_GET['date_end'];
    $id=$_GET['id'];

    
    $select_date=mysqli_query($mysqli,"SELECT * FROM `sensors` INNER JOIN data_receiving dr on sensors.data_number = dr.data_number 
WHERE date_receiving>='$date_start' AND date_receiving<='$date_end' AND $id=id_sensors");
    while( $row = $select_date->fetch_assoc() ) {
        $id_sensor = $row['id_sensors'];
        $data = $row['datas'];
        $date = $row['date_receiving'];
?>
<div class="period">
    <?php
        echo "<span class='data'> ".$data."</span>";
        echo "<span class='date'> ".$date."</span>";
        ?>
</div>
<?php
    }
}

if(isset($_GET['id'])){
    $id=$_GET['id'];
    $select_sensor_stat=mysqli_query($mysqli,"SELECT * FROM `loacation` WHERE id_sensors=$id");
    while( $row = $select_sensor_stat->fetch_assoc() ) {
    $id_sensor = $row['id_sensors'];
    $location=$row['id_place'];
    ?>
<div class="location">
    <?php
    echo "id=".$id_sensor."id_place= ".$location;
    ?>
</div>
    <?php
}
}

if(isset($_GET['stat_id'])){
    $id=$_GET['stat_id'];
    $select_sensor_stat=mysqli_query($mysqli,"SELECT * FROM `sensors` INNER JOIN data_receiving dr on sensors.data_number = dr.data_number 
WHERE id_sensors=$id");
    while( $row = $select_sensor_stat->fetch_assoc() ) {
        $date = $row['date_receiving'];
        $data=$row['datas'];
        ?>
        <div class="stat_id">
            <?php
            echo "<span class='date'>".$date."</span><span class='data'> ".$data."</span>";
            ?>
        </div>
        <?php
    }
}


if(isset($_GET['day']) OR isset($_GET['id'])){
    $day=$_GET['day'];
    $id=$_GET['id'];
    if($id!=NULL){
        $select_day=mysqli_query($mysqli,"SELECT * FROM `sensors` INNER JOIN data_receiving dr on sensors.data_number = dr.data_number 
WHERE date_receiving='$day' AND id_sensors=$id");
        while( $row = $select_day->fetch_assoc() ) {
            $id_sensor = $row['id_sensors'];
            $data = $row['datas'];
            ?>
            <div class="day_id">
                <?php
                echo "id=".$id_sensor."<br>";
                echo "Data=".$data."<br>";
                ?>
            </div>
            <?php
            }
        return;
    }

$select_day=mysqli_query($mysqli,"SELECT * FROM `sensors` INNER JOIN data_receiving dr on sensors.data_number = dr.data_number 
WHERE date_receiving='$day'");
while( $row = $select_day->fetch_assoc() ) {
    $id_sensor = $row['id_sensors'];
    $data = $row['datas'];
?>
<div class="days">
    <?php
    echo "<span class=\"id\">".$id_sensor."</span><br>";
    echo "<span class=\"data\">".$data."</span><br>";
    }
    ?>
</div>
<?php
}
?>
</body>