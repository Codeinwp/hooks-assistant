;(function ($) {

    $(document).ready(function () {

        $('.ha-toggle').on('click', function () {
            $(this).siblings('.ha-editor-wrapper').toggleClass('ha-typing');
        });

        $('.ha-save-btn').on('click', function () {

            var value = $(this).siblings( '.ha-editor' ).find('textarea').val();
            var hook = $(this).data( 'hook' );

            $.ajax({
                url: wpApiSettings.root + 'hooks-assistant/v1/set_hook_value',
                method: 'POST',
                data: {
                    hook: hook,
                    value: value,
                },
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
                },
                success: function (data) {
                    // console.log(data);
                    window.location.reload();
                }
            }).done(function (response) {
                console.log(response);
            });

        });

    });


})(jQuery);