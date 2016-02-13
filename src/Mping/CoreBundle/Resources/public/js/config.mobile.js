$(document).on( "mobileinit", function() {
    $.mobile.defaultPageTransition = 'pop';
    $.mobile.pageLoadErrorMessage = 'Erreur lors du chargement de la page';
    $.mobile.toolbar.prototype.options.backBtnText = "Retour";
});