$(document).ready(function($){

    var alert = $('#alert');
    if(alert.length > 0){
        alert.hide().slideDown(800).delay(2000).slideUp();
    }

});

$(document).ready(function() {
    $('a[data-confirm]').click(function(ev) {
        var href = $(this).attr('href');

        if (!$('#dataConfirmModal').length) {
            $('body').append('' +
                '<div ' +
                'id="dataConfirmModal" ' +
                'class="modal" ' +
                'role="dialog" ' +
                'aria-labelledby="dataConfirmLabel" ' +
                'aria-hidden="true">' +
                '<div ' +
                'class="modal-dialog">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<button type="button" ' + 'class="close" ' + 'data-dismiss="modal" ' +
                '<h3 ' +
                'id="dataConfirmLabel">Merci de confirmer</h3>' +
                '</div><div class="modal-body"></div>' +
                '<div class="modal-footer">' +
                '<button class="btn" ' +
                'data-dismiss="modal" ' +
                'aria-hidden="true">Non</button>' +
                '<a class="btn btn-danger" ' +
                'id="dataConfirmOK">Oui</a>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>');
        }
        $('#dataConfirmModal').find('.modal-body').text($(this).attr('data-confirm'));
        $('#dataConfirmOK').attr('href', href);
        $('#dataConfirmModal').modal({show:true});

        return false;
    });
});






