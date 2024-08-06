<?php

switch ($request) {
    case $_POST["accountid"]:
}

function deleteMessage($account_id, $message_id)
{
    $contacts = file_get_contents('../contacts.json', true);
    $contacts = json_decode($contacts, true);

    unset($contacts[$account_id]['messages'][$message_id]);
    $contacts = array_values($contacts);

    $contacts = json_encode($contacts);
    // file_put_contents('../contacts.json', $contacts);
    header('Content-type: application/json');
    file_put_contents('../contacts.json', $contacts);
    echo ($contacts);
};



$account_id = $_POST['accountid'];
$message_id = $_POST['messageid'];

deleteMessage($account_id, $message_id);
// header('Content-type: application/json');
