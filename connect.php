<!-- connect.php vytvoril Jakub Sekula -->
<!-- Vytvori pripojeni k databazi --> 

<?php

$conn = mysqli_connect( "sql.endora.cz:3311" , "kubanwttest8ucz", "R9eromenen" ) or die("Connect failed");

$conn->select_db( "nwt" );

?>