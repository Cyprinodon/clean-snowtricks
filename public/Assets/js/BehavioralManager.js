"use strict";

const BehavioralManager = function(parent, formBuilder, entity) {
    this.builder = formBuilder;
    this.parent = parent;
    this.formSelector = '.' + entity + '-form';
}

// Fonction chargée d'ajouter un formulaire d'image à la collection.
BehavioralManager.prototype.addForm = function addEntityForm() {
    let addButton = this.builder.buildButton("Ajouter", "add-button");
    this.parent.append(addButton);

    this.parent.data('index', this.parent.find(this.formSelector).length);

    addButton.on('click', () => {
        let form = this.builder.buildForm(this.parent, addButton);
        this.attachRemoveButton(form);
        form.find('input[required]:first').focus();
    });
};

// Fonction chargée de retirer un formulaire de la collection.
BehavioralManager.prototype.removeForm = function removeEntityForm() {
    this.parent.find(this.formSelector).each(
        (index, element) => {
            let form = $(element);
            console.log(form);
            this.attachRemoveButton(form);
        });
};

// Fonction chargée de construire et attacher un bouton de suppression.
BehavioralManager.prototype.attachRemoveButton = function attachRemoveButton(form) {
    let removeButton = this.builder.buildButton("Retirer", "remove-button");

    form.append(removeButton);

    removeButton.on('click', () => {
        form.remove();
        this.parent.data('index', this.parent.find(this.formSelector).length);
    });
}