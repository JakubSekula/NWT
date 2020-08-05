<!-- insert.php vytvoril Jakub Sekula -->
<!-- vlozi zaznam o zamestnanci do databaze --> 
<?php

include "connect.php";

$jmeno = $_POST[ 'jmeno' ];
$prijmeni = $_POST[ 'prijmeni' ];
$titul = $_POST[ 'titul' ];
$dnaroz = $_POST[ 'dnarozeni' ];
$zod = $_POST[ 'zod' ];

if( !preg_match( '/(\d)*/', $id ) ){
    header( "Location: index.php" );
    exit();
} elseif( !preg_match( '/^[a-zA-Z]*$/', $jmeno ) ){
    header( "Location: index.php" );
    exit();
} elseif( !preg_match( '/^[a-zA-Z]*$/', $prijmeni ) ){
    header( "Location: index.php" );
    exit();
}

$sql = "INSERT INTO OSOBA ( jmeno,prijmeni,titul,dnarozeni, zamestnan ) VALUE ( '$jmeno','$prijmeni','$titul', '$dnaroz','$zod' )";

$conn->query( $sql );

$conn->close();

header( "Location: index.php" );

?>