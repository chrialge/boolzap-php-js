<?php
header('Content-type: application/json');
function deleteAccount($delete)
{
    $contacts = file_get_contents('../contacts.json', true);
    $contacts = json_decode($contacts, true);

    unset($contacts[$delete]);
    $contacts = array_values($contacts);

    $contacts = json_encode($contacts);
    file_put_contents('../contacts.json', $contacts);
    echo ($contacts);
};


$delete = $_POST['deleteAccount'];
deleteAccount($delete);
