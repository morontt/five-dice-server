$(function () {
    $('#btn-create').click(function () {
        var player = $('#fd-player').val().trim();

        if (player.length) {
            $.ajax({
                url: routes.create,
                method: 'POST',
                headers: {
                    'FD-PLAYER-ID': player
                },
                success: function (data) {
                    console.log(data);
                }
            });
        }
    });
});
