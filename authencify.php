<?php
session_id($_GET["id"]);
session_start();

#print_r($_SESSION);
#echo session_id();

function multiarray_keys($ar) {

        foreach($ar as $k => $v) {
            $keys[] = $k;
            if (is_array($ar[$k]))
                $keys = array_merge($keys, multiarray_keys($ar[$k]));
        }
        return $keys;
    }


$a = multiarray_keys($_SESSION);
#print_r($a);
#echo time()-$_SESSION[$a[0]]["auth"]["time"];

if (!in_array("grps",$a) || ((time()-$_SESSION[$a[0]]["auth"]["time"])>3600)) {echo 'Bitte (erneut) anmelden, um diese Funktion zu nutzen.';exit;}


?>
