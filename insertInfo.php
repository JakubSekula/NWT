<!-- insertInfo.php vytvoril Jakub Sekula -->
<!-- k zaměstnanci prida informace do databáze --> 
<?php

include "connect.php";
include "date.php";

$dateenter = $_POST[ 'date' ];
$time = $_POST[ 'cas' ];
$id = $_POST[ 'id' ];
$timeleave = $_POST[ 'casleave' ];
$dateleave = $_POST[ 'dateleave' ];
$type = $_POST[ 'type' ];

if( !preg_match( '/^(\d)+$/', $id ) ){
    header( "Location: person.php?id=$id&error=sqlattack" );
    exit();
}

if( !preg_match( '/^[0][1-9]\:[0-5][0-9]$/', $time ) ){
    if( !preg_match( '/^[1-2][0-9]\:[0-5][0-9]$/', $time ) ){
        header( "Location: person.php?id=$id&error=sqlattack" );
        exit();
    }
}

if( !preg_match( '/^[0][1-9]\:[0-5][0-9]$/', $timeleave ) ){
    if( !preg_match( '/^[1-2][0-9]\:[0-5][0-9]$/', $timeleave ) ){
        header( "Location: person.php?id=$id&error=sqlattack" );
        exit();
    }
}

$dateenter = convert( $dateenter );
$dateleave = convert( $dateleave );

if( $dateenter[ 1 ] != $dateleave[ 1 ] && $dateleave[ 2 ] != 1  ){
    header( "Location: person.php?id=$id&error=2" );
    exit();
}

if( ( $dateenter[ 2 ] != $dateleave[ 2 ] - 1 ) ){
    if( ( $dateenter[ 2 ] != $dateleave[ 2 ] ) and $dateleave[ 2 ] != 1 ){
        header( "Location: person.php?id=$id&error=2" );
        exit();
    }
}

// poskladani data pro databazovy format
$dateenter = $dateenter[ 2 ] . $dateenter[ 1 ] . $dateenter[ 0 ];
$dateleave = $dateleave[ 2 ] . $dateleave[ 1 ] . $dateleave[ 0 ];

$sql = "SELECT * FROM DOCHAZKA WHERE id_osoby = $id AND den = $dateenter";
$res = $conn->query( $sql );
    
if( $res->num_rows != 0 ){
    // jestlize byl zadan spatne odchod a prichod, tj. prichod az po odchodu
    if( countHours( $dateenter, $dateleave, $time, $timeleave ) == "error" ){
        header( "Location: person.php?id=$id&error=10" );
        exit();
    }
    if( $type == 1 ){
        $sql = "UPDATE DOCHAZKA SET prichod = '$time', odchod = '$timeleave' WHERE id_osoby = $id AND den = '$dateenter'";
        $conn->query( $sql );
    } else {
        $sql = "UPDATE DOCHAZKA SET klekari = '$time', odlekare = '$timeleave' WHERE id_osoby = $id AND den = '$dateenter'";
        $conn->query( $sql );
    }
} else {
    if( countHours( $dateenter, $dateleave, $time, $timeleave ) == "error" ){
        header( "Location: person.php?id=$id&error=10" );
        exit();
    }

    if( $type == 1 ){
        $sql = "INSERT INTO DOCHAZKA ( id_osoby, den, denodchod, prichod, odchod ) VALUES ( $id, '$dateenter', '$dateleave', '$time', '$timeleave' )";
        $conn->query( $sql );
    } else {
        $sql = "INSERT INTO DOCHAZKA ( id_osoby, den, denodchod, klekari, odlekare ) VALUES ( $id, '$dateenter', '$dateleave', '$time', '$timeleave' )";
        $conn->query( $sql );
    }
}

header( "Location: person.php?id=$id" );


?>