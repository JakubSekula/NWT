<!-- insert.php vytvoril Jakub Sekula -->
<!-- vlozi zaznam o zamestnanci do databaze --> 
<?php

include "connect.php";

$jmeno = $_POST[ 'jmeno' ];
$prijmeni = $_POST[ 'prijmeni' ];
$titul = $_POST[ 'titul' ];

$sql = "INSERT INTO OSOBA ( jmeno,prijmeni,titul ) VALUE ( '$jmeno','$prijmeni','$titul' )";

$conn->query( $sql );

$conn->close();

header( "Location: index.php" );

?>