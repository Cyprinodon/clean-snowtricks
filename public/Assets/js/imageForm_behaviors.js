"use strict";

// Fonction auto-invoqué permettant d'ajouter les éléments pour ajouter ou retirer un formulaire d'image 'prototypé'.
(($, formBuilder, BehavioralManager) => {
    //Attente du chargement du DOM.
     $(document).ready(() => {
         let entities = ['image', 'video'];

         for(let i = 0; i < entities.length; i++) {
             let collection = $(`.${entities[i]}s .form-collection`);

             let manager = new BehavioralManager(collection, formBuilder, entities[i]);

             manager.addForm();
             manager.removeForm();
         }
     });
})(jQuery, FormBuilderModule, BehavioralManager);