<?php

function is_permalink_structure_a_query(){
    $permalink_structure = get_option('permalink_structure');
    if (empty($permalink_structure)) return true;
    if (strpos($permalink_structure, '?')!==false) return true;
    return strpos($permalink_structure, 'index.php')!==false;
}