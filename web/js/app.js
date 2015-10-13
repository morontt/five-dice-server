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
    var last_content_hash = '';
    $('#btn-pending').click(function () {
        var player = $('#fd-player').val().trim();

        if (player.length && !poll_started) {
            poll_started = true;
            var poll_id = setInterval(function () {
                $.ajax({
                    url: routes.pending,
                    method: 'GET',
                    headers: {
                        'FD-PLAYER-ID': player
                    },
                    success: function (data) {
                        var html;
                        console.log(data);

                        if (data.content_hash !== last_content_hash) {
                            html = '<ul>';
                            data.games.forEach(function (el) {
                                html += '<li>' + el.hash + '</li>'
                            });
                            html += '</ul>';

                            $('.pending-games').html(html);
                            last_content_hash = data.content_hash;
                        }
                    },
                    error: function () {
                        clearInterval(poll_id);
                    }
                });
            }, 2000);
        }
    });
});
