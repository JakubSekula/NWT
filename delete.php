<!-- delete.php vytvoril Jakub Sekula -->
<!-- smaze zaznam z databaze o uzivateli --> 

<?php

include "connect.php";

$id = $_GET[ 'id' ];

$sql = "DELETE FROM OSOBA WHERE id = $id";

$conn->query( $sql );

$conn->close();

header( "Location: index.php" );

?>