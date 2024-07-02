<?php

$contacts = file_get_contents('contacts.json', true);
header('Content-type: application/json');
echo $contacts;
// $json_string = file_get_contents('disci.json');

// header('Content-type: application/json');
// echo $json_string;
