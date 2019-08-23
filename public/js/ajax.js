$(document).on('click','.haveSeen', function() {
    const button = $(this);
    const url = $(this).data('url');

    $.ajax({
        url: url,
        type: "POST",

        success: function(response){
            if(response.active == true){
                $(button).addClass('bg-danger');
            } else {
                $(button).removeClass('bg-danger');
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
                $(button).addClass('bg-danger');
            } else {
                $(button).removeClass('bg-danger');
            }
        }
    });
})