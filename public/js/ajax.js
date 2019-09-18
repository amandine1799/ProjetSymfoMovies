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

$(document).on('click','.wishListDisappear', function() {
    const button = $(this);
    const url = $(this).data('url');
    let counter = parseInt($('.wish-counter').html());

    $.ajax({
        url: url,
        type: "POST",

        success: function(response){
            $(button).parent().parent().fadeOut(200);
            counter--;
            $('.wish-counter').html(counter);
        }
    });
})

$(document).on('click','.haveSeenDisappear', function(){
    const button = $(this);
    const url = $(this).data('url');
    let counter = parseInt($('.seen-counter').html());

    $.ajax({
        url: url,
        type: "POST",

        success: function(response){
            $(button).parent().parent().fadeOut(200);
            counter--;
            $('.seen-counter').html(counter);
        }
    });
})

$(document).on('click','.getContent', function() {
    const button = $(this);
    const url = $(this).data('url');
    const status = parseInt($(this).data('content'));

    if(status == 1){
        $(button).parent().find('.dislike').removeClass('btn-danger');
        if($(button).hasClass('btn-success')){
            $(button).removeClass('btn-success');
        } else {
            $(button).addClass('btn-success');
        }
    } else {
        $(button).parent().find('.like').removeClass('btn-success');
        if($(button).hasClass('btn-danger')){
            $(button).removeClass('btn-danger');
        } else {
            $(button).addClass('btn-danger');
        }
    }

    $.ajax({
        url: url,
        data: {
            content: status,
        },
        type: "POST",
        success: function(){

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