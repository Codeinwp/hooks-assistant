;( function ($) {

    $(document).ready( function () {

        $( '.ha-toggle' ).on( 'click', function() {
            $( this ).siblings( '.ha-editor-wrapper' ).toggleClass( 'ha-typing' );
        } );

    } );


} )( jQuery );