jQuery( function($) {
   
    $('form').on( 'submit', function(e) {                

        if ( typeof( tinyMCE ) != "undefined") {

            $wp_editors = tinyMCE.editors;

            if ( $wp_editors.length > 0 ) {

                $('.wp-switch-editor.switch-tmce').click();

                for ( var i = 0; i < $wp_editors.length; i++ ) {

                    var id = $wp_editors[i].id,
                        $editor_field = $('#' + id + '-editor'),
                        contents = '';

                    if ( $editor_field.length > 0 ) {

                        $wp_editors[i].save();

                        contents = $wp_editors[i].getContent();

                        $editor_field.val( contents );

                    }

                }

            }

        }
        
    });
    
    
});