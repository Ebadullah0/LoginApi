<?php
$con = mysqli_connect("localhost","root","","LOGINEMP");
$response - array();
if($con){
    $sql - "select * from data ";
    $result = mysqli_query($con,$sql);
    if($result){
        $i-0;
        while($row = mysqli_fetch_assoc($result)){
            $response[$i]['employee_name'] = $row['employee_name'];
            $response[$i]['employee_id'] = $row['employee_id'];
            $response[$i]['password'] = $row['password'];
            $i++;
        }
        echo json_encode($response,JSON_PRETTY_PRINT);
    }
}
else{
    echo "  NOT CONNECTED";
}
?>