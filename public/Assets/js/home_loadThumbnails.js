"use strict";

/* Ce module JQuery charge les miniatures des figures  de manière asynchrone
lorsque l'utilisateur clique sur le bouton 'load_more'. */
(($) => {

    //Installation de l'écouteur d'événement sur le bouton
    $('#load_more').on("click",event => {
        let message = "";

        // Récupération du conteneur devant recevoir les miniatures
        let container = $("#tricks_container");
        // Initialisation des attributs data
        let ajaxUrl = container.data("path"); // Chemin du fichier à récupérer
        let page = parseInt(container.data("page")); // Numéro de page
        let maxPages = parseInt(container.data("max-pages")); // Nombre de pages existantes
        // Initialisation du numéro de page suivant la page courante
        let nextPage = page + 1;

        //Fonction chargée d'envoyer un message d'erreur si la requête Ajax échoue.
        const sendErrorMessage = (xhr, status, error) => {
            let errorBag = $('#load_error');

            // Si le conteneur d'erreur n'existe pas encore, on l'ajoute.
            if(!errorBag.length) {
                message = "<p id=\"load_error\" class=\"col-12 text-center mt-5\">Les miniatures n'ont pas pu être chargées. " +
                    "Soit parce que le serveur ne réponds pas, " +
                    "soit parce qu'une erreur est survenue au moment de la requête.</p>";
                container.append(message);
            }
            console.log(`Error: ${error}`);
        };

        //Fonction chargée d'afficher la page de figures suivante.
        const displayNextPage = (data) => {
            // Mise à jour du numéro de page pour le prochain appel
             container.attr("data-page", nextPage);

            // Intégration dans le DOM des miniatures récupérées
            let thumbnails = $(data.template);
            /*thumbnails.hide();*/
            container.append(thumbnails);
            /*thumbnails.fadeIn("slow");*/
            console.log("maxpages : " + nextPage);
            // Désactivation du bouton lorsque la dernière page a été atteinte
            if (nextPage >= maxPages) {
                let button = $(event.currentTarget);
                button.textContent = "fini !"
                message = "<p>Toutes les figures ont été chargées.</p>";
                button.hide();
                button.replaceWith(message).fadeTo(4000, );
            }

            //Défilement jusqu'au nouveau contenu ajouté
            $('html, body').animate({scrollTop: $(document).height()}, 100);
        }

        // Préparation de la requête.
        let jsonRequest = {
            url: ajaxUrl,
            type: "GET",
            dataType: "json",
            data: { "page": nextPage },
            success: displayNextPage,
            error: sendErrorMessage,
        };

        // Envoi de la Requête.
        $.ajax(jsonRequest);
    });
})(jQuery);