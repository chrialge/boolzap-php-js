const { createApp } = Vue;

createApp({
    data() {
        return {
            drowping: true,
            addContact: false,
            modalAddAccount: false,
            loading: false,
            url: 'api.php',
            //valore del input search
            searchContact: "",

            contactJson: [],

            // dati del nuovo contatto
            accountName: '',
            accountImage: '',
            //costante per il valore di time per decidere quando stoppare l'intervallo
            time: 0,

            contactActive: [],
            // il valore del input inserito
            newMessage: "",

            //utilizzato per selezionare il contatto
            contactNumber: 0,

            // array di emonji
            emonjeis: [
                '😊',
                '😂',
                '😘',
                '👍',
                '🤞',
                '👌',
                '😶‍🌫️',
                '⛩️',
            ],

            //array di oggetti riguardanti i contatti
            contacts: [],

        };
    },
    methods: {


        callApi() {
            this.loading = true;
            axios.get(this.url)
                .then((result) => {
                    console.log(result);
                    this.contacts = result.data
                    this.loading = false
                }).catch((err) => {
                    console.error(err.message);
                })
        },


        /**
         * funzione che prende al click l'index
         * @param {number} contactId l'index del contatto selezionato
         */
        conversation(contactId) {
            //prende l'indice del contatto selezionato che mi servira per selezionare la conversazione che vogliamo
            this.contactNumber = contactId;

        },

        /**
         * funzione che aggiunge un messaggio che abbiamo scritto nel input del messaggio
         * @param {number} index del contatto
         */
        addMessage(index) {
            // constante che prende la proprieta newMessage dove al tag ho inserito un v-model
            const message = this.newMessage;
            //constante con lo status di 'sent' perche lo invio
            const status = "sent";

            //invoco la funzione che genera un messaggio
            this.generateMessage(message, index, status);

            //invoco la funxione che dopo 1s arriva il messaggio di risposta
            this.startTimeMessageRespond(message, index, status);

            // svutot l'input
            this.newMessage = "";
        },

        /**
         * funzione che genera il messaggio
         * @param {string} message il messaggio che deve essere inserito
         * @param {number} index del contatto
         * @param {string} status se e un messaggio inviato o mandato
         */
        generateMessage(message, index, status) {

            // variabili per ottenere la data odierna
            const data = new Date();
            let gg, mm, aaaa;//dichiarazione di tre variabili
            gg = data.getDate() + "/";//prende giorno
            mm = data.getMonth() + 1 + "/";//prende mese
            aaaa = data.getFullYear();// prende l'anno
            const date = gg + mm + aaaa;// constante che unisce i tre valori in un unica stringa

            // variabili per ottenere l orario odierna
            let Hh, Mm, Ss;// dichiarazione delle tre variabili
            Hh = data.getHours() + ":"; //prende l'ora
            Mm = data.getMinutes() + ":";//prende i minuti
            Ss = data.getSeconds();// prende i secondi
            const time = Hh + Mm + Ss;//constante che unisce in un uniica stringa

            //inserisce nel contatto specifico nell'array message il messaggio generato
            this.contacts[index].messages.push({
                date: date + " " + time,//inserisce la data e l'ora 
                message: message,//il messaggio
                status: status,// lo status che in base hai casi puo essere 'sent' o 'receveid'
            });
            console.log(this.contacts[index].messages);
        },

        /**
         * funzione che parte dopo un secondo che genera il messaggio di risposta
         * @param {string} message recupero il messaggio che poi cambiera
         * @param {number} index recupero per selezionare il giusto contatto
         * @param {string} status recupero lo status che poi cambiera
         */
        startTimeMessageRespond(message, index, status) {
            this.time = setInterval(() => {
                // passo i parametri che verranno cambiati nella funzione message respond 
                this.messageRespond(message, index, status);
                // questa funzione ha un intervallo di 1 secondo
            }, 1000);
        },

        /**
         * funzione che stoppa il messaggio di risposta se no li genera all'infinito
         */
        stopTimeMessageRespond() {
            clearInterval(this.time);
        },

        /**
         * funzione per il messaggio di risposta
         * @param {string} message messaggio cambiera in ok
         * @param {number} index mi serve per selezionare il giusto contatto
         * @param {string} status status cambiera in 'received'
         */
        messageRespond(message, index, status) {
            // message viene trasformato in 'ok'
            message = "ok";
            // lo status diventa 'receveid'
            status = "received";

            // invoco la funzione che genera un messaggio passando i parametri
            this.generateMessage(message, index, status);
            console.log(this.time);

            // condizione in cui time e 4 o superiore fa stopare l intervalllo se no genera all' infinito i messaggi
            if (this.time >= 4) {
                this.stopTimeMessageRespond();
            }
        },
        /**
         * FUNZIONE CHE CERCA I POSSIBILI CONTATTI MENTRE GLI ALTRI CAMBIA IL VISIBLE
         */
        contactsSearch() {
            //CONSOLE.LOG PER LEGGERE IL VALORE DEL INPUT
            console.log(this.searchContact);
            // se la lunghezza della parola maggiore di 1
            if (this.searchContact) {
                if (this.searchContact.length > 1) {
                    // itero tra i contatti  che assomiglia al valore dell'input
                    for (const key in this.contacts) {
                        // costante per prendere i nomi dei contatti
                        const nameContant = this.contacts[key].name.toLowerCase();

                        //costasnte che cerca se i nomi dei contanti assomiglia all'input e dra valore true e false
                        const visible = nameContant.includes(
                            this.searchContact.toLowerCase()
                        );
                        if (!nameContant.includes(
                            this.searchContact.toLowerCase())) {
                            this.addContact = true;
                        }



                        //cambiare il valore di visible in base al valore della costante precedente
                        this.contacts[key].visible = visible;
                    }
                } else {
                    this.addContact = false;
                    //altrimenti se la lunghezza della parola e di uno trasforma tutti i valori visible in true
                    for (const key in this.contacts) {
                        this.contacts[key].visible = true;
                    }
                }
            }

        },

        /**
         * funzione che assegna l'ora giusta dei messaggi
         * @param {number} index l'indice del messaggio
         * @param {number} contactid l'indice del contatto
         * @returns hours example "16:30"
         */
        dataMessage(index, contactid) {
            //condizione se l'indice va sotto ci sara il vuoto fatto perche se no mi da errore dopo aver cancellato l'ultimo
            if (index < 0) {
                return data = ''
            }

            // prende la proprieta data di un messaggio e contatto specifico
            let timeMessage = this.contacts[contactid].messages[index].date;

            //divide l'elemento in diversi elementi in base al separatore " "
            timeMessage = timeMessage.split(" ");

            // prendo il secondo elemento che in questo caso e l'ora
            timeMessage = timeMessage[1];

            //divide l'elemento in diversi elementi in base al separatore ":" per togliermi i milliseccondi
            timeMessage = timeMessage.split(":");

            //unisco le ore con i minuti e aggiungo tra loro ':'
            timeMessage = timeMessage[0] + ":" + timeMessage[1];

            // ritorna l'ora giusta
            return timeMessage;
        },
        /**
         * funzione che rimuove il messaggio selezionato
         * @param {number} index l'indice del messaggio
         * @param {number} contactid l'indice del contatto
         */
        removeMessage(index, contactid) {

            // rimuove il messaggio con l'indice del contatto e l'indice del messaggio
            this.contacts[contactid].messages.splice(index, 1)

        },

        /**
         * funzione che ci da la data del messaggio
         * @param {number} index l'indice del messaggio
         * @param {number} contactid l'indice del contatto
         * @returns 
         */
        dateMessage(index, contactid) {
            //condizione se l'indice va sotto ci sara il vuoto fatto perche se no mi da errore dopo aver cancellato l'ultimo
            if (index < 0) {
                return data = ''
            }

            // prende la proprieta data di un messaggio e contatto specifico
            let dateMessage = this.contacts[contactid].messages[index].date;

            //divide l'elemento in diversi elementi in base al separatore " "
            dateMessage = dateMessage.split(" ");

            // prende il primo elemento che e la data
            dateMessage = dateMessage[0];

            // dalla funzione fa uscire la data del ultimo messaggio del contatto
            return dateMessage
        },
        /**
         * funzione per prendermi l'emonji selezionato e inserirlo nel input di scrittura di messaggio
         * @param {number} index indice del emenji
         */
        insertValue(index) {
            // prende l'emonji selezionata e la inserisce nel input di messaggistica cioe nel v-modelcon la proprieta newMessage
            this.newMessage = this.newMessage + this.emonjeis[index];
        },

        addNewAccount() {
            this.loading = true;

            const data = {
                name: this.accountName,
                avatar: '/img/icon-default.jpg',
                message: []
            }
            axios.post(this.url, data, { headers: { 'Content-type': 'multipart/form-data' } })
                .then((resp) => {
                    this.searchContact = "";
                    this.modalAddAccount = false;
                    this.addContact = false;
                    this.callApi();

                    console.log(resp);
                }).catch(err => {
                    console.error(err.message);
                })
        },
        deleteAccount(index) {
            this.loading = true;
            console.log(index)
            const data = {
                deleteAccount: index
            }
            const url = 'post/delete.php'
            axios.post(url, data, { headers: { 'Content-type': 'multipart/form-data' } })
                .then((resp) => {


                    console.log(resp);
                    this.callApi();
                }).catch(err => {
                    console.error(err.message);
                })
        }

    },
    mounted() {
        console.log(innerWidth + "x" + innerHeight)	// 1280x386


        this.callApi();


    },
}).mount("#container");
