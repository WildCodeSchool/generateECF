init();

function init(){
    renderPromoByCity();
}

function renderPromoByCity(){
    $('.cityButton').click(function (event) {
        event.preventDefault();

        let linkRequest = $(this).data('linkrequest');
        let cityId = $(this).data('cityid');
        $.ajax({
            url: linkRequest,
            data: { cityId: cityId }
        })
            .done(function( html ) {
                $( "#boxPromo" ).html(html);
            })

            .fail(function( jqXHR, textStatus ) {
                console.log( "Request failed: " + textStatus );
            })
    })
}