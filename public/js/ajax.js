$(document).on('click','.haveSeen', function() {
    const button = $(this);
    const url = $(this).data('url');

    $.ajax({
        url: url,
        type: "POST",

        success: function(response){
            if(response.active == true){
                $(button).addClass('btn-info');
            } else {
                $(button).removeClass('btn-info');
            }
        }
    });
})

$(document).on('click','.wishList', function() {
    const button = $(this);
    const url = $(this).data('url');

    $.ajax({
        url: url,
        type: "POST",

        success: function(response){
            if(response.active == true){
                $(button).addClass('btn-info');
            } else {
                $(button).removeClass('btn-info');
            }
        }
    });
})

$(document).on('click','.moviealea',function(){
    const url = $(this).data('url');
    modal = $(this).data('target');

    $.ajax({
        url: url,
        type: "POST",

        success: function(response){
            if (url !== undefined)
            {
                $(modal).find('.modal-dialog').html("");
                $(modal).find('.modal-dialog').append(response.render);
            }
        }
    });
})