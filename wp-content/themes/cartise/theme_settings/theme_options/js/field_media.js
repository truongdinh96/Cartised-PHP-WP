jQuery( function($) {

    $('.media_upload').click(function(e) {
        
        var id = $(this).attr('data-upload-startwiths-id'),
            $attachment_thumbnail = $('#' + id + '-id'),
            $attachment_inputbox = $('#' + id + '-input-id');
            
    
        var custom_uploader = wp.media({
            title: 'Select Image',
            button: {
                text: 'Upload Image'
            },
            multiple: false  // Set this to true to allow multiple files to be selected
        })
        .on('select', function() {
            
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            
            $attachment_thumbnail.attr('src', attachment['url']);
            $attachment_inputbox.val( attachment['url'] );
           
        })
        .open();
    });
    
});