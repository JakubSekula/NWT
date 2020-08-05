<!-- delete.php vytvoril Jakub Sekula -->
<!-- smaze zaznam z databaze o uzivateli --> 

<?php

include "connect.php";

$id = $_GET[ 'id' ];

if( !preg_match( '/(\d)*/', $id ) ){
    header( "Location: index.php?error=sqlattack" );
    exit();
}

$sql = "DELETE FROM OSOBA WHERE id = $id";

$conn->query( $sql );

$sql = "DELETE FROM DOCHAZKA WHERE id_osoby = $id";

$conn->query( $sql );

$conn->close();

header( "Location: index.php" );

?>