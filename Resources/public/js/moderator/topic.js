 $(document).ready(function () {
 
    var $topicContent = '';
    var editAction = true; // Status d'édition
    var isTouchDevice = 'ontouchstart' in document.documentElement; // Détection des ecrans tactile
    
    // show & hide si ce n'est pas un ecran tactile
    if (isTouchDevice === false) { // Cet ecran n'est pas tactile
        $('div.admin').hide();
        $('div.topics').hover(function() {
            $('div.admin', this).show();      
        }, function() { 
            $('div.admin', this).hide(); 
        });
    };

    /*
     * Suppresion d'un topic  
     */
    
    // Création de la modale pur confirmation
    $('#deleteTopic').on('show.bs.modal', function (e) {
        var link = $(e.relatedTarget).data('link');
        $("a.btn-danger").attr('href',link);
    });
});
