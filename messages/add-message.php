<?php
header('Content-type: application/json');
function addMessageReceived($newmessage, $index)
{
    $contacts = file_get_contents('../contacts.json', true);
    $contacts = json_decode($contacts, true);

    array_push($contacts[$index]['messages'], $newmessage);


    $contacts = json_encode($contacts);
    file_put_contents('../contacts.json', $contacts);
    echo ($contacts);
};


$newmessage = $_POST['message'];
$index = $_POST['index'];

addMessageReceived($newmessage, $index);
