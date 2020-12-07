"use strict";

// Objet regroupant des fonction pour construire de nouveaux éléments de formulaire.
const FormBuilderModule = {
    // Fonction chargée d'ajouter le prototype html à la collection de formulaires.
    buildForm : function buildForm(parent, eventTarget) {
        let prototype = parent.data('prototype');
        let index = parent.data('index');
        let form = $(prototype);

        parent.data('index', index++);
        eventTarget.before(form);

        return form;
    },

    //Fonction chargée de générer l'élément DOM représentant le bouton
    buildButton : function buildButton(textContent = undefined, classes = undefined) {
        // Gestion des paramètres d'entrée
        textContent = textContent == undefined ? "Envoyer" : textContent;
        classes = classes != undefined ? classes + " " : "";

        let button = $(document.createElement('a'))
            .text(textContent)
            .attr('class', classes + "btn btn-secondary");

        let col = $(document.createElement('div'))
            .attr('class', "col")
            .append(button);

        let row = $(document.createElement('div'))
            .attr('class',"row")
            .append(col);

        return row;
    }
};
