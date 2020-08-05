<!-- correction.php vytvoril Jakub Sekula -->
<!-- upravi zaznam o zamestnanci v databazi -->
<?php

include "connect.php";
include "date.php";

$id = $_POST[ 'id' ];
$jmeno = $_POST[ 'jmeno' ];
$prijmeni = $_POST[ 'prijmeni' ];
$titul = $_POST[ 'titul' ];
$daroz = $_POST[ 'dnarozeni' ];
$zod = $_POST[ 'zod' ];

if( !preg_match( '/(\d)*/', $id ) ){
    header( "Location: person.php?id=$id&error=sqlattack" );
    exit();
} elseif( !preg_match( '/^[a-zA-Z]*$/', $jmeno ) ){
    header( "Location: person.php?id=$id&error=sqlattack" );
    exit();
} elseif( !preg_match( '/^[a-zA-Z]*$/', $prijmeni ) ){
    header( "Location: person.php?id=$id&error=sqlattack" );
    exit();
}

if( strpos( $daroz, "." ) ){
    $daroz = tosqlformat( $daroz );
    $daroz = $daroz[ 2 ] . "-" . $daroz[ 1 ] . "-" . $daroz[ 0 ];
}

if( strpos( $zod, "." ) ){
    $zod = tosqlformat( $zod );
    $zod = $zod[ 2 ] . "-" . $zod[ 1 ] . "-" . $zod[ 0 ];
}

$sql = "UPDATE OSOBA set jmeno='$jmeno', prijmeni='$prijmeni', titul='$titul', dnarozeni='$daroz', zamestnan='$zod' WHERE id='$id'";

$conn->query( $sql );

$conn->close();


header( "Location: person.php?id=$id" );

?>