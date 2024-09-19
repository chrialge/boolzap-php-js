<?php


// var_dump($_SERVER['REQUEST_METHOD']);
$newcontact = [];
$contacts = '';
$contacts = file_get_contents('contacts.json', true);

if (count($_POST) > 1) {
    echo ($_SERVER['REQUEST_METHOD']);
    echo ($_SERVER['REQUEST_URI']);

    $name = $_POST['name'];
    $avatar = $_POST['avatar'];
    $newcontact = [
        'name' => $name,
        'avatar' => $avatar,
        'visibility' => true,
        'messages' => [],
    ];
    addAccount($newcontact);
    // var_dump($contacts);
}

function addAccount($newcontact)
{
    $contacts = file_get_contents('contacts.json', true);
    $contacts = json_decode($contacts, true);
    array_unshift($contacts, $newcontact);
    $contacts = json_encode($contacts);
    file_put_contents('contacts.json', $contacts);
};

header('Content-type: application/json');

echo $contacts;
