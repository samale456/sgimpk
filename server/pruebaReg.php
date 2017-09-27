<?php  
$array = array("prueba", ":arg1", ":arg2", ":arg3", "otro");

var_dump(array_keys($array, `^[a-z]`));
?>