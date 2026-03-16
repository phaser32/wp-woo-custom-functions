jQuery(document).ready(function($) {
    var $form = $('#wpwoo-cf-form');
    var $feedback = $('#wpwoo-feedback');
    var $spinner = $('.spinner');

    // --- AZIONE SALVA ---
    $form.on('submit', function(e) {
        e.preventDefault();
        
        // Sincronizziamo il contenuto di CodeMirror con la textarea originale
        if (window.wpwoo_cm_editor) {
            window.wpwoo_cm_editor.save();
        }

        var codeContent = $('#wpwoo_editor').val();

        $spinner.addClass('is-active');
        $feedback.hide().removeClass('success error');

        $.ajax({
            url: wpwoo_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wpwoo_save_action',
                code: codeContent
            },
            success: function(response) {
                $spinner.removeClass('is-active');
                if (response.success) {
                    $feedback.addClass('success').html(response.data).fadeIn();
                } else {
                    $feedback.addClass('error').html(response.data).fadeIn();
                }
            },
            error: function() {
                $spinner.removeClass('is-active');
                $feedback.addClass('error').html('Errore di connessione al server. Il test di loopback potrebbe aver impiegato troppo tempo.').fadeIn();
            }
        });
    });

    // --- AZIONE IMPORTA DA DB ---
    $('#btn-import').on('click', function() {
        if (confirm('Sei sicuro? Questo sovrascriverà il codice attuale nell\'editor con l\'ultima versione salvata nel Database.')) {
            location.reload(); // Il caricamento della pagina ripesca automaticamente i dati dal DB
        }
    });
});