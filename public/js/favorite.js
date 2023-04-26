// public/js/favorite.js

$(document).ready(function () {
    // handle favorite button click
    $('.favorite-button').click(function () {
        var placeId = $(this).data('place-id');
        var button = $(this);

        // send ajax request to add/remove favorite
        $.ajax({
            url: '/favorites/toggle/' + placeId,
            type: 'POST',
            success: function (data) {
                if (data.status == 'added') {
                    button.addClass('active');
                } else {
                    button.removeClass('active');
                }
            }
        });
    });
});
