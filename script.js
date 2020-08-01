// funkce po zvoleni na element, ktery ji vola bud ukaze nebo zavre div add-item
function pop(){
    var add = document.getElementById( "add-item" );
    
    if( add.style.display === "block" ){
        add.style.display = "none";
    } else {
        add.style.display = "block"
    }
    
}

// funkce kontroluje spravnost udaju zadanych ve formu
function validateform(){  
    var time = document.dochazka.cas.value;

    if( document.dochazka.date.value === null || document.dochazka.date.value === "" ){
        alert( "Prosím zadejte datum" )
        return false;
    }

    if( time === null || time === "" ){  
        alert( "Prosím zadejte cas" );  
        return false;  
    } else if ( time.length < 5 ){  
        alert( "Prosím zadejte čas ve formátu mm:hh. Pro příklad 07:00." );  
        return false;
    }  
}  

// funkce kontroluje format mesice a roku ve formu
function checkForm(){
    
    var month = document.person.month.value;
    
    if( !month.match( /^(\d){2}\-(\d){4}/ ) ){
        alert( "Prosím zadejte měsíc a rok ve formatu mm-yyyy. Například 07-2020." );  
        return false;
    }
    
}
