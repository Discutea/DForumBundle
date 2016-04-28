
// Création de la modale de confirmaion
$(document).ready(function() {      
    $('#deletepost').on('show.bs.modal', function (e) {
        $('div#firstMsg', this).hide();
        var button = $(e.relatedTarget);
        var link = button.data('link');
        var first = button.data('firstpost');
        if (first === true) {
            $('div#firstMsg', this).show();
        }
        $("a.btn-danger").attr('href',link);
    }); 
});
