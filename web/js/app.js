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
                        clearInterval(poll_id);

                        if (data.content_hash !== last_content_hash) {
                            html = '<ul>';
                            data.games.forEach(function (el) {
                                html += '<li class="join">' + el.hash + '</li>'
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

    $('.pending-games').on('click', '.join', function () {
        var hash = $(this).html();
        var player = $('#fd-player').val().trim();
        if (player.length) {
            $.ajax({
                url: routes.join.replace('12345678', hash),
                method: 'POST',
                headers: {
                    'FD-PLAYER-ID': player
                }
            });
        }
    });
});
