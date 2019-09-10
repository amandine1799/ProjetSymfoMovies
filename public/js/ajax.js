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

$(document).on('click','.getContent', function() {
    const button = $(this);
    const url = $(this).data('url');
    const status = parseInt($(this).data('content'));

    $.ajax({
        url: url,
        data: {
            content: status,
        },
        type: "POST",
        success: function(){
            if(status == 1){
                $('.dislike').removeClass('btn-danger');
                if($(button).hasClass('btn-success')){
                    $(button).removeClass('btn-success');
                } else {
                    $(button).addClass('btn-success');
                }
            } else {
                $('.like').removeClass('btn-success');
                if($(button).hasClass('btn-danger')){
                    $(button).removeClass('btn-danger');
                } else {
                    $(button).addClass('btn-danger');
                }
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