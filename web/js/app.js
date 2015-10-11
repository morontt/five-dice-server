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

    var poll_started = false;
    $('#btn-pending').click(function () {
        var player = $('#fd-player').val().trim();

        if (!poll_started) {
            poll_started = true;
            var poll_id = setInterval(function () {
                $.ajax({
                    url: routes.pending,
                    method: 'GET',
                    headers: {
                        'FD-PLAYER-ID': player
                    },
                    success: function (data) {
                        console.log(data);
                        clearInterval(poll_id);

                        var html = '<ul>';
                        data.games.forEach(function (el) {
                            html += '<li>' + el.hash + '</li>'
                        });
                        html += '</ul>';

                        $('.pending-games').html(html);
                    }
                });
            }, 4000);
        }
    });
});
