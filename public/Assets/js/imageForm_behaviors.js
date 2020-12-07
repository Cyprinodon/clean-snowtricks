"use strict";

// Fonction auto-invoqué permettant d'ajouter les éléments pour ajouter ou retirer un formulaire d'image 'prototypé'.
(($, formBuilder, BehavioralManager) => {
    //Attente du chargement du DOM.
     $(document).ready(() => {
         let entities = ["image", "video"];

         entities.forEach((entity) => {
             let collection = $(`.${entity}s .form-collection`);

             let manager = new BehavioralManager(collection, formBuilder, entity);

             manager.addForm();
             manager.removeForm();
         });
     });
})(jQuery, FormBuilderModule, BehavioralManager);