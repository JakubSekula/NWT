<!-- date.php vytvoril Jakub Sekula -->
<!-- Obsahuje nekolik funkci pro praci s casem -->
<?php

/*
    Funkce convert prevadi z formatu xx-xxxx
    @param $date - datum zaznamu
    @return - pole casti
*/
function convert( $date ){
    $pieces = explode( "-", $date );
    
    $year = $pieces[ 0 ];
    return $pieces;
}

/*
    Funkce convertMinutesToHours prevadi minuty na hodiny
    @param $time - minuty
    @return - hodiny a minuty
*/
function convertMinutesToHours( $time ){

    $hours = $time / 60;

    if( !strpos( $hours, "." ) ){
        return $hours . " hodin " . 0 . " minut";
    }
    
    $pos = strpos( $hours, "." );

    $minutes = round( 0 . substr( $hours, $pos ) * 60 );
    
    $hours = substr( $hours, 0, $pos );

    return $hours . " hodin " . $minutes . " minut"; 
    
}

/*
    Funkce sumHoursMonthly secte pocet vykazanych hodin za mesic
    @param $time - celkovy cas
    @return hodiny a minuty
*/
function sumHoursMonthly( $time ){

    $result = explode( ":", $time );
    
    $hours = $result[ 0 ];
    
    $minutes = $result[ 1 ];
    
    return $hours*60 + $minutes;

}

/*
    Funkce countHours spocita hodiny v kazdy den, vcetne odchodu a prichodu k lekari
    @param $prichod - prichod do prace
    @param $odchdo - odchod z prace
    @param $odlekare - pokud je, tak prichod od lekare
    @param $klekari - pokud je, tak odchod k lekari
    $return hodiny a minuty
*/
function countHours( $prichod, $odchod, $odlekare = "00:00", $klekari = "00:00" ){
    $hour1 = substr( $prichod, 0, 2 );
    $min1 = substr( $prichod, 3, 5 );
    $hour2 = substr( $odchod, 0, 2 );
    $min2 = substr( $odchod, 3 , 5 );
    
    $hour3 = substr( $odlekare, 0, 2 );
    $min3 = substr( $odlekare, 3, 5 );
    $hour4 = substr( $klekari, 0, 2 );
    $min4 = substr( $klekari, 3 , 5 );
    
    
    
    $hours = ( ( ( $hour2 - $hour1 ) - ( $hour4 - $hour3 ) ) * 60 + ( ( $min2 - $min1 ) - ( $min- $min3 ) ) ) / 60;

    if( $hours < 0 ){
        return "error";
    }
    
    if( !strpos( $hours, "." ) ){
        return $hours . ":" . 0;
    }
    
    $minutes = 0 + substr( $hours, 1 );
    $minutes = $minutes * 60;
    
    if( substr( $hours, 0, 1 ) == 0 ){
        $hours = substr( $hours, 0, 1 );
    } else {
        $hours = substr( $hours, 0 , 2 );
    }
    
    if( substr( $hours, 1, 2 ) == "." ){
        $hours = substr( $hours, 0, 1 );
    }
    
    return $hours . ":" . round( $minutes );
    
}

?>