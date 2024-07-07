<?php

// $newAccount['name'] = $_POST['name'];
// $newAccount['avatar'] = '/img/icon-default.jpg';
// $newAccount['visible'] = true;
// $newAccount['messages'] = [];
// var_dump($newAccount);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Boolzap</title>
</head>

<body>
    <div id="container">

        <!-- loading for page -->
        <div class="container_loader" v-show="loading">
            <div class="loader"></div>
        </div>



        <!-- modal for create new account -->
        <div id="new_contact" v-show="modalAddAccount">
            <div class="container_create">
                <div class="header_new_contact">
                    <h1>Crea un contatto</h1>
                    <a href="#" @click="modalAddAccount = !modalAddAccount">
                        <i class="fa-solid fa-xmark"></i>
                    </a>

                </div>
                <form action="" method="post" enctype="multipart/form-data" @submit.prevent="addNewAccount()">
                    <div class="input_create">
                        <label for="name">Nome</label>
                        <input type="text" name="name" id="name" v-model="accountName">
                    </div>


                    <div class="container_input_file">
                        <label for="image" class="label_file">Scegli il file
                            <i class="fa-solid fa-download"></i>
                        </label>
                        <input type="file" name="image" id="image" v-model="accountImage" />

                    </div>

                    <div class="btn_create">
                        <button type="submit">
                            crea contatto
                        </button>
                    </div>

                </form>
            </div>

        </div>
        <!-- modal for create new account -->



        <!--left container for tablet and mobile  -->
        <div class="left_drowping" v-show="drowpingActive">
            <div class="my_account">
                <div class="image_my_account">
                    <img src="./assets/img/avatar_io.jpg" alt="">
                </div>
                <div class="option_my_account">
                    <a href="#"><i class="fa-solid fa-ellipsis-vertical"></i>
                    </a>
                    <a href="#" class="close_drowping" @click="drowpingActive = !drowpingActive">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                </div>
            </div>

            <div class="search">
                <div class="container-search">
                    <div class="text-search d-flex" action="search" method="get">
                        <div class="btn-search">
                            <button><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                        <input type="search" placeholder="Cerca o inizia una nuova chat" v-model="searchContact" @keyup="contactsSearch()" @keydown="contactsSearch()">
                    </div>
                </div>
            </div>

            <div class="contacts">
                <ul class="list-unstyled">
                    <!-- contact -->
                    <li v-for="(contact, index) in contacts" @click="conversation(index)" :class="{'visible-hidden': contact.visible == false}">
                        <div class="contact d-flex posi-rela">
                            <div class="contact-image">
                                <span></span>
                                <img id="account_img" :src="'./assets' + contact.avatar" alt="">
                            </div>
                            <div class="contact-info w-100">
                                <h5>{{contact.name}}</h5>

                            </div>
                            <div id="delete_contact" class="flex-shrink-1">



                                <!-- Modal trigger button -->
                                <button type="button" class="p-0 border-0 fs-5 bg-white delete_modal" data-bs-toggle="modal" :data-bs-target="`#modalId-drop-${index}`">
                                    <i class="fa fa-trash text-danger" aria-hidden="true"></i>
                                </button>

                                <!-- Modal Body -->
                                <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                                <div class="modal fade" :id="`modalId-drop-${index}`" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" :aria-labelledby="'modalTitleId-drop-'+ index" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" :id="'modalTitleId-drop-'+ index">
                                                    Eliminazione del account
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Sei sicuro di voler eliminare {{contact.name}} tra i tuoi contatti?
                                            </div>
                                            <div class="modal-footer d-flex justify-content-center">

                                                <form action="" method="post" @submit.prevent="deleteAccount(index)">
                                                    <button type="submit" data-bs-dismiss="modal" class="btn btn-danger">
                                                        Elimina
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </li>
                    <!-- contact -->

                    <!-- add contact -->
                    <li v-show="addContact">
                        <div class="contact add_contact d-flex posi-rela " @click="modalAddAccount = !modalAddAccount">
                            <div class="add_image">
                                <img src="assets/img/add-user.png" alt="" width="50px">
                            </div>

                            <div id="add_contact_text">
                                <h3>Aggiungi contatto</h3>
                            </div>
                        </div>
                    </li>
                    <!-- add contact -->
                </ul>


            </div>

        </div>
        <!--left container for tablet and mobile  -->


        <!-- left-container for sreen with min-width=990px -->
        <div class="left" id="display_left">
            <div class="main-menu d-flex">
                <div class="image-main-menu">
                    <img src="./assets/img/avatar_io.jpg" alt="">
                </div>
                <div class="option-main-menu">

                    <a href="#"><i class="fa-solid fa-ellipsis-vertical"></i></a>
                </div>
            </div>
            <div id="notifications" class=" d-flex">
                <div class="symbol-no-notification">
                    <a href="#"><i class="fa-solid fa-bell-slash"></i></a>
                </div>
                <div class="info-notification">
                    <h4>Ricevi notifiche di nuovi messagi</h4>
                    <a href="#">Attiva notifiche destktop</a>
                </div>
            </div>
            <div class="search">
                <div class="container-search">
                    <div class="text-search d-flex" action="search" method="get">
                        <div class="btn-search">
                            <button><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                        <input class="input_disabled" type="search" placeholder="Cerca o inizia una nuova chat" v-model="searchContact" @keyup="contactsSearch()" @keydown="contactsSearch()">
                    </div>
                </div>
            </div>

            <div class="contacts">
                <ul class="list-unstyled">
                    <!-- contact -->
                    <li v-for="(contact, index) in contacts" @click="conversation(index)" :class="{'visible-hidden': contact.visible == false}">
                        <div class="contact d-flex posi-rela">
                            <div class="contact-image">
                                <span></span>
                                <img id="account_img" :src="'./assets' + contact.avatar" alt="">
                            </div>
                            <div class="contact-info w-100">
                                <h5>{{contact.name}}</h5>
                                <span class="text_disabled">{{dateMessage((contact.messages.length -1), index)}}</span>
                                <div class="time">
                                    <span class="text_disabled">{{ dataMessage(contact.messages.length - 1, index) }}</span>
                                </div>
                            </div>
                            <div id="delete_contact" class="flex-shrink-1">



                                <!-- Modal trigger button -->
                                <button type="button" class="p-0 border-0 fs-5 bg-white delete_modal" data-bs-toggle="modal" :data-bs-target="`#modalId-${index}`">
                                    <i class="fa fa-trash text-danger" aria-hidden="true"></i>
                                </button>

                                <!-- Modal Body -->
                                <!-- if you want to close by clicking outside the modal, delete the last endpoint:data-bs-backdrop and data-bs-keyboard -->
                                <div class="modal fade" :id="`modalId-${index}`" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" :aria-labelledby="'modalTitleId-'+ index" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" :id="'modalTitleId-'+ index">
                                                    Eliminazione del account
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Sei sicuro di voler eliminare {{contact.name}} tra i tuoi contatti?
                                            </div>
                                            <div class="modal-footer d-flex justify-content-center">

                                                <form action="" method="post" @submit.prevent="deleteAccount(index)">
                                                    <button type="submit" data-bs-dismiss="modal" class="btn btn-danger">
                                                        Elimina
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>
                        </div>
                    </li>
                    <!-- contact -->

                    <!-- add contact -->
                    <li v-show="addContact">
                        <div class="contact add_contact d-flex posi-rela " @click="modalAddAccount = !modalAddAccount">
                            <div class="add_image">
                                <img src="assets/img/add-user.png" alt="" width="50px">
                            </div>

                            <div id="add_contact_text">
                                <h3>Aggiungi contatto</h3>
                            </div>
                        </div>
                    </li>
                    <!-- add contact -->
                </ul>


            </div>
        </div>
        <!-- left-container -->

        <!-- right-container -->
        <div class="right posi-rela" v-if="contactActive = contacts[contactNumber]">

            <!-- /#site-header -->
            <header id="site-header">
                <div class="container-header d-flex">
                    <div class="left-header d-flex grow-1">
                        <div class="image-header" @click="drowping()">
                            <img :src="'./assets' + contactActive['avatar']" alt="">
                        </div>
                        <div class="info header">
                            <h4>{{contactActive['name']}}</h4>
                            <span>Ultimo accesso oggi alle {{dataMessage(contactActive['messages'].length - 1, contactNumber)}}</span>
                        </div>
                    </div>

                    <div class="right-header grow-1">
                        <a href="#"><i class="fa-solid fa-magnifying-glass"></i></a>
                        <a href="#"><i class="fa-solid fa-paperclip"></i></a>
                        <a href="#"><i class="fa-solid fa-ellipsis-vertical"></i></a>
                    </div>
                </div>
            </header>
            <!-- /#site-header -->

            <!-- /#site-main -->
            <main id="site-main">
                <div class="container-messages">
                    <div v-for=" (messageData, index) in contactActive['messages']" class="message posi-rela" :class="{'message-sent': messageData.status === 'sent',  'message-arrived': messageData.status === 'received'}">
                        <p>
                            {{messageData.message}}
                        </p>
                        <div id="drowdown_message">
                            <a href="#"><i class="fa-solid fa-chevron-down"></i></a>
                            <ul class="option-message posi-abso">
                                <li><a href="#">Info messaggio</a></li>
                                <li><a href="#" @click="removeMessage(index,contactNumber)">
                                        Cancella messaggio</a></li>
                            </ul>
                        </div>
                        <div class="time-message posi-abso">
                            <span>{{dateMessage(index, contactNumber)}}</span>
                            <span>{{dataMessage(index, contactNumber)}}</span>
                        </div>
                    </div>
            </main>
            <!-- /#site-main -->


            <!-- /#site-footer -->
            <footer id="site-footer">
                <div class="container-metodology-message d-flex">
                    <div class="emonji">
                        <a href="#"><i class="fa-regular fa-face-smile"></i></a>
                        <div class="dropdown_emonji">
                            <ul class="posi-abso d-flex">
                                <li v-for="(emonji, index) in emonjeis"><a href="#" @click="insertValue(index)">{{emonji}}</a></li>
                            </ul>
                        </div>

                    </div>
                    <div class="write-message grow-1">
                        <input type="text" placeholder="Scrivi un messaggio" v-model="newMessage" @keyup.enter="addMessage(contactNumber)">
                    </div>
                    <div class="vocal-message">
                        <a href="#"><i class="fa-solid fa-microphone"></i></a>
                    </div>

                </div>

            </footer>
            <!-- /#site-footer -->
        </div>
        <!-- end of container -->

        <!-- cdn bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
        <!-- cdn axios -->
        <script src='https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js' integrity='sha512-PJa3oQSLWRB7wHZ7GQ/g+qyv6r4mbuhmiDb8BjSFZ8NZ2a42oTtAq5n0ucWAwcQDlikAtkub+tPVCw4np27WCg==' crossorigin='anonymous' referrerpolicy='no-referrer'></script>
        <!-- cdn vue -->
        <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
        <!-- js -->
        <script src="./assets/js/script.js"></script>
</body>

</html>