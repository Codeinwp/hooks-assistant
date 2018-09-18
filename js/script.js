;(function ($) {

    $(document).ready(function () {

        // $( '.ha-power' ).on( 'click', function() {
        //     // $( this ).preventDefault();
        //     $( this ).addClass( 'ha-power-enable' );
        //     $( '.ha-toggle' ).css( 'display', 'inline-block' );
        // } );
        //
        // $( '.ha-power .ha-power-enable' ).on( 'click', function() {
        //     // $( this ).preventDefault();
        //     $( this ).removeClass( 'ha-power-enable' );
        //     $( '.ha-toggle' ).css( 'display', 'none' );
        // });

        $( '.ha-power' ).on( 'click', function() {
            $( this ).toggleClass( 'ha-power-enable' );
            $( '.ha-toggle' ).toggleClass( 'ha-toggle-on' );
        } );

        // if ( $( '.ha-power' ).hasClass( 'ha-power-enable' ) ) {
        //     $( '.ha-toggle' ).css( 'display', 'inline-block' );
        // } else {
        //     $( '.ha-toggle' ).css( 'display', 'none' );
        // }




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