<!-- datepick.php vytvoril Jakub Sekula -->
<!-- Vraci informace z databaze o pracovnim dni -->
<?php

include "connect.php";

$date = $_GET[ 'date' ];
$id = $_GET[ 'id' ];

// rozdeli format xx-xx-xxxx na casti xx xx xxxx
$pieces = explode( "-", $date );

$year = $pieces[ 0 ];
$month = $pieces[ 1 ];
$day = $pieces[ 2 ];

echo $year;
echo $month;
echo $day;

$date = $day.$month.$year;

$sql = "SELECT * FROM DOCHAZKA WHERE id_osoby = $id AND den = $date";

$result = $conn->query( $sql );


if( $result->num_rows != 0 ){
    while( $row = $result->fetch_assoc() ){
        
        $id = $row[ 'id_osoby' ];
        $den = $row[ 'den' ];
        $prichod = $row[ 'prichod' ];
        $odchod = $row[ 'odchod' ];
        
        header( "Location: person.php?id=$id&prichod=$prichod&odchod=$odchod" );
    }
} else {
    header( "Location: person.php?id=$id" );
}

?>