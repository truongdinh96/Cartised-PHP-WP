jQuery( function($) {
    
   var media = {

        index : 0,
        
        ready: function() {
            
            $(document).on('click', '.field_media_widget_remove', this.removeCustomFieldMedia)
                       .on('click', '.media_upload', this.uploadMedia)
                       .on('click', '.field_media_widget_add', this.addCustomFieldMedia);
                                       
        },
        
        addCustomFieldMedia: function(e) {

            e.preventDefault();
        
            var $fields_container = $(this).closest('.metabox_field'),
                $new_field = $fields_container.find('.field:nth-child(5)')
                                              .clone(),
                remove_widget_src = $(this).attr('data-image-path') + '/widget_remove.png',
                html_field = '';

            $new_field.find('.media_field_metabox')
                      .val('')
                      .attr('src', '');

            $new_field.find('> div:last-child')
                       .append('<img src="' + remove_widget_src  + '" class="field_media_widget_remove vmiddle cpointer" />');

            $new_field.find('input[type=text]')
                      .each(function(i, e) {

                var n = $(e).attr('name').toString(),
                    n_splices = n.split('[0]');

                $(e).attr('name', n.replace( n, 
                                             n_splices[0] + '[' + ( media.index + 1 ) + ']' + n_splices[1] ) );

            });

            html_field = '<div class="field mtop10" data-index="' + ( media.index + 1 ) + '">' + $new_field.html() + '</div>';
            
            $fields_container.append( html_field );   

            media.index++;         
            
        },
        
        removeCustomFieldMedia: function(e) {
            
            e.preventDefault();

            var $fields_container = $(this).closest('.metabox_field'),
                index = 0;

            $(this).closest( '.field' )
                   .remove();

             media.index--;

            $fields_container.find('.field')
                             .each(function(i, e) {

                var $field = $(e),
                    field_index = $field.attr('data-index');

                $field.find('input[type=text]')
                      .each(function(i, e) {

                    var $inputbox = $(e),
                        n = $inputbox.attr('name').toString(),
                        n_splices = n.split('[' + field_index  + ']');         

                    $inputbox.attr('name', n.replace( n, 
                                                      n_splices[0] + '[' + index + ']' + n_splices[1] ) );

                });

                $field.attr('data-index', index);

                index++;

            });           
            
        },
        
        uploadMedia: function(e) {
            
            e.preventDefault();
        
            var $field =  $(this).closest('.field'),
                $attachment_thumbnail = $field.find('.thumbnail_media_field_metabox'),
                $attachment_url = $field.find('.media_field_metabox.thumbnail'),
                
                custom_uploader = wp.media({
                    title: 'Select Media',
                    button: {
                        text: 'Upload Image'
                    },
                    multiple: false  // Set this to true to allow multiple files to be selected
                })
                .on('select', function() {
                    
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    
                    if ( $attachment_thumbnail.length > 0 ) {
                        $attachment_thumbnail.attr('src', attachment['url'] );
                    }
                    
                    $attachment_url.val( attachment['url'] );
                   
                })
                .open();
                
            }
    }  
    
    media.ready();
    
});