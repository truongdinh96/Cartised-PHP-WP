<?php 
    $this->metaboxes[] = array(
        
        'id' => 'thong-tin',
        'title' => 'Thông tin',
        'where_show_on' => 'post',
        'condition_to_show' => '',
        
        'fields' => array(
            
            array(
                
                'id' => 'thong-tin-link-download',
                'title' => 'Link download',
                'desc' => 'Vui lòng điền link download vào dưới đây',
                'type' => 'text'
            ),
            array(
                
                'id' => 'thong-tin-loai-tai-lieu',
                'title' => 'Thông tin các loại tài liệu',
                'desc' => 'Vui lòng chọn thông tin dưới đây',
                'type' => 'select',
                'options' => array(
                    'tai-lieu-1' => 'Tài liệu 1',
                    'tai-lieu-2' => 'Tài liệu 2',
                    'tai-lieu-3' => 'Tài liệu 3'
                 )
                 
            ),
             array(
                
                'id' => 'thong-tin-file-anh-dinh-kem',
                'title' => 'File ảnh đính kèm',
                'desc' => 'Vui lòng chọn một file ảnh đính kèm',
                'type' => 'media',
                'multiple' => true,
                
            ),
            array(
                
                'id' => 'thong-tin-file-anh-dinh-kem1',
                'title' => 'File ảnh đính kèm1',
                'desc' => 'Vui lòng chọn một file ảnh đính kèm1',
                'type' => 'text'
                
            ),
            array(
                
                'id' => 'thong-tin-editor',
                'title' => 'Thông tin soạn thảo',
                'desc' => 'Vui lòng soạn thảo văn bản',
                'type' => 'editor'
                
            ),
            
        )
        
    );
?>