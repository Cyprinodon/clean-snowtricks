/* Module JQuery permettant d'afficher une fenêtre modale de confirmation
de suppresion */
( ($) => {
  const modalShown = "show.bs.modal"; //Type d'événement Jquery "Affichage d'une modale"

  const appendHref = function(event) {
    let button = $(event.relatedTarget)
    let path = button.data('controller-path');
    let modal = $(event.currentTarget);
    console.log(modal);
    modal.find('#confirmDelete').attr('href', path);
  }

  // Installation des écouteurs d'événement sur les modales.
  $('#modal_delete').on(modalShown, appendHref);
  $('#modal_category_delete').on(modalShown, appendHref);

})(jQuery);