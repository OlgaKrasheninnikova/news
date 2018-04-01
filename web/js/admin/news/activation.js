function onOffClick() {
    var value = $(this).attr('rel')
    var id = $(this).attr('data')
    $.ajax({
        type: "GET",
        url: '/admin/news/activation',
        context: this,
        data: {'value': value, 'id': id},
        success: function(data) {
            if ('OK' == data) {
                $(this).html( (0 == value) ? 'Нет' : 'Да' ).attr('rel',(0 == value) ? 1 : 0 )
            }
        }
    });
}

function initActivation() {
    $('.activation').click( onOffClick)
}
$(document).on('pjax:success', function() {
    initActivation()
});

$(
    initActivation()
)