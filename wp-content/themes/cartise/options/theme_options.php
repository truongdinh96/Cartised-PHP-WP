<?php

    $this->sections[] = array(

        'id' => 'section-header',

        'title'  => array(

           'vi' => 'Header',

           'en' => 'Header'

       ),

        'desc'   => array(

            'vi' => 'Tất cả thiết lập cho header',

           'en' => 'All header settings'

       ),

        'fields' => array(

            array(

                'id' => 'header-section-1',

                'type' => 'section',

                'title' => array(

                             

                               'vi' => 'Thiết lập logo và background',

                               'en' => 'Logo and background Settings'

                             

                           ),

                'desc' => '',

                'indent' => true

            ),

                array(

                    'id'    => 'logo-image',

                    'type'  => 'media',

                    'title' => array(                                    

                        'vi' => 'Logo website',

                        'en' => 'Logo website'

                    ),

                    'desc'  => array(                                 

                        'vi' => 'Mời chọn logo cho website',

                        'en' => 'Please choose logo website image'                                    

                    )

                ),            

                array(

                    'id'    => 'logo-image-mobile',

                    'type'  => 'media',

                    'title' => array(                                    

                        'vi' => 'Logo Mobile website',

                        'en' => 'Logo Mobile website'

                    ),

                    'desc'  => array(                                 

                        'vi' => 'Mời chọn logo mobile cho website',

                        'en' => 'Please choose logo mobile website image'                                    

                    )

                ),               
               

            array(

                'id' => 'header-section-2',

                'type' => 'section',

                'indent' => false 

            )

        )

    );     

    $end_section = end( $this->sections );  
    

    $this->sections[ sizeof( $this->sections ) - 1 ] = $end_section;   


    $this->sections[] = array(

        'id' => 'section-cartise-form',

        'title'  => array(
            
            'vi' => 'Cartise Form',                     
            'en' => ''
            
        ),

        'desc'   => array(
            
            'vi' => 'All Form settings',                     
            'en' => ''
            
        ),

        'fields' => array(            

            array(

                'id' => 'cartiseform-section-1',

                'type' => 'section',

                'title' => array(
                                
                    'vi' => 'Fields data settings',
                    'en' => ''
                    
                ),

                'desc' => '',

                'indent' => true

            ),

                array(

                    'id'    => 'cartiseform-genre-select',

                    'type'  => 'textarea',

                    'title' => array(
                        
                        'vi' => 'Genre options',
                        'en' => ''
                        
                    ),

                    'desc'  => array(
                                 
                        'vi' => '',
                        'en' => ''
                        
                    ),

                ),               

                array(

                    'id'    => 'cartiseform-energie-select',

                    'type'  => 'textarea',

                    'title' => array(
                        
                        'vi' => 'Energie options',
                        'en' => ''
                        
                    ),

                    'desc'  => array(
                                 
                        'vi' => '',
                        'en' => ''
                        
                    ),
                    

                ),              
                
                array(
                   
                    'id'    => 'cartiseform-selectionnez-select',

                    'type'  => 'textarea',

                    'title' => array(
                        
                        'vi' => 'Sélectionnez votre démarche options',
                        'en' => ''
                        
                    ),

                    'desc'  => array(
                                 
                        'vi' => '',
                        'en' => ''
                        
                    ),
                    

                ),     
                
                array(
                   
                    'id'    => 'cartiseform-ptac-select',

                    'type'  => 'textarea',

                    'title' => array(
                        
                        'vi' => 'PTAC options',
                        'en' => ''
                        
                    ),

                    'desc'  => array(
                                 
                        'vi' => '',
                        'en' => ''
                        
                    ),
                    

                ),     

                array(
                   
                    'id'    => 'cartiseform-carrosserie-tm-select',

                    'type'  => 'textarea',

                    'title' => array(
                        
                        'vi' => 'Carrosserie options (TM)',
                        'en' => ''
                        
                    ),

                    'desc'  => array(
                                 
                        'vi' => '',
                        'en' => ''
                        
                    ),
                    

                ),     

                array(
                   
                    'id'    => 'cartiseform-carrosserie-cycl-select',

                    'type'  => 'textarea',

                    'title' => array(
                        
                        'vi' => 'Carrosserie options (CYCL)',
                        'en' => ''
                        
                    ),

                    'desc'  => array(
                                 
                        'vi' => '',
                        'en' => ''
                        
                    ),
                    

                ),     

                array(
                   
                    'id'    => 'cartiseform-carrosserie-qm-select',

                    'type'  => 'textarea',

                    'title' => array(
                        
                        'vi' => 'Carrosserie options (QM)',
                        'en' => ''
                        
                    ),

                    'desc'  => array(
                                 
                        'vi' => '',
                        'en' => ''
                        
                    ),
                    

                ),  

            array(

                'id' => 'cartise-section-2',

                'type' => 'section',

                'indent' => false 

            ),

        )

    );  