<!-- insertInfo.php vytvoril Jakub Sekula -->
<!-- k zamestnanci prida informace do databaze --> 
<?php

include "connect.php";
include "date.php";

/*
    Funkce vrati informaci o prichodu nebo odchodu pokud je
    @param $id - identifikator zamestnance
    @param $date - datum pro zaznam
    @param $conn - pripojeni k databazi
    @param $type - 1 je odchod, 0 prichod
*/
function getOdchodPrichod( $id, $date, $conn, $type ){
    if( $type == 1 ){
        $sql = "SELECT odchod FROM DOCHAZKA WHERE id_osoby = $id AND den = $date";
    } else {
        $sql = "SELECT prichod FROM DOCHAZKA WHERE id_osoby = $id AND den = $date";
    }
    
    $res = $conn->query( $sql );
   
    if( $res->num_rows != 0 ){
        while( $row = $res->fetch_assoc() ){
            if( $type == 1 ){
                return $row[ 'odchod' ];
            } else {
                return $row[ 'prichod' ];
            }
        }
    } else {
        return "";
    }
    
}

$date = $_POST[ 'date' ];
$time = $_POST[ 'cas' ];
$id = $_POST[ 'id' ];
$type = $_POST[ 'type' ];

echo $type;

if( !$type ){
    header( "Location: person.php?id=$id&error=1" );
    exit();
}


$date = convert( $date );

// poskladani data pro databazovy format
$date = $date[ 2 ] . $date[ 1 ] . $date[ 0 ];

$sql = "SELECT * FROM DOCHAZKA WHERE id_osoby = $id AND den = $date";
$res = $conn->query( $sql );

/*
    $type == 1 - prichod
    $type == 2 - odchod
    $type == 3 - k lekare
    $type == 4 - od lekare
*/
if( $type == 1 ){
    
    if( $res->num_rows != 0 ){
        $odchod = getOdchodPrichod( $id, $date, $conn, 1 );
        // jestlize byl zadan spatne odchod a prichod, tj. prichod az po odchodu
        if( countHours( $time, $odchod ) == "error" ){
            header( "Location: person.php?id=$id&error=10" );
            exit();
        }
        $sql = "UPDATE DOCHAZKA SET prichod = '$time' WHERE id_osoby = $id AND den = '$date'";
        $conn->query( $sql );
    } else {
        $sql = "INSERT INTO DOCHAZKA ( id_osoby, den, prichod ) VALUES ( $id, '$date', '$time' )";
        $conn->query( $sql );
    }
} elseif( $type == 2 ) {
    
    if( $res->num_rows != 0 ){
        $prichod = getOdchodPrichod( $id, $date, $conn, 0 );
        // jestlize byl zadan spatne odchod a prichod, tj. prichod az po odchodu
        if( countHours( $prichod, $time ) == "error" ){
            header( "Location: person.php?id=$id&error=11" );
            
            exit();
        }
        $sql = "UPDATE DOCHAZKA SET odchod = '$time' WHERE id_osoby = $id AND den = '$date'";
        $conn->query( $sql );
    } else {
        $sql = "INSERT INTO DOCHAZKA ( id_osoby, den, odchod ) VALUES ( $id, '$date', '$time' )";
        $conn->query( $sql );
    }
} elseif( $type == 3 ){
    $sql = "UPDATE DOCHAZKA SET klekari = '$time' WHERE id_osoby = $id AND den = '$date'";
    $conn->query( $sql );
} elseif( $type == 4 ){
    $sql = "UPDATE DOCHAZKA SET odlekare = '$time' WHERE id_osoby = $id AND den = '$date'";
    $conn->query( $sql );
}

header( "Location: person.php?id=$id" );


?>