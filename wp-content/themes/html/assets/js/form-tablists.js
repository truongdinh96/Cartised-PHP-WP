jQuery(function($) {

    $('.section-carte-grise-form .form-main li').click(function(e) {

        e.preventDefault();

        const $tab = $(this),
            layout_id = $tab.data('layout-id'),
            tabs_length = $tab.parent().find('> li').length,
            $layout = $('.cartegriseminute_layout__' + layout_id);

        if (!$tab.hasClass('active')) {

            $tab.siblings().removeClass('active');

            $tab.addClass('active');

            $layout.removeClass('none')
                .siblings('.tab_cartegriseminute_layout')
                .addClass('none');

        }

        const index = $tab.index();

        if (index === 0 || index === 1 || index === 2) {

            $('#msg_neuf_import').addClass('none');

            $layout.find('.renseignez-layout').addClass('none');
            $layout.find('.inputbox-renseignez').removeClass('none');

            const $link = $layout.find('.immatriculation-link');

            if (index === 0) {

                $link.removeClass('none');

            } else {

                $link.addClass('none');

            }

        }

        // last tab
        else if (index === tabs_length - 1) {

            $layout.find('.renseignez-layout').addClass('none');
            $layout.find('.inputbox-renseignez').removeClass('none');

            $layout.find('.immatriculation-link').removeClass('none');

        }

    });

    $('.section-carte-grise-form .form-main .immatriculation-link').click(function(e) {

        e.preventDefault();

        const $layout = $(this).closest('.tab_cartegriseminute_layout'),

            l_id = parseInt($(this).data('layout-id')),

            $input_renseignez = $layout.find('.inputbox-renseignez'),
            $layout_renseignez = $layout.find('.renseignez-layout');

        $input_renseignez.addClass('none');
        $layout_renseignez.removeClass('none');

        if (l_id === 1) {

            $('#msg_neuf_import').removeClass('none');

        }


    });

    $(document).on('click',
        '.neuf-boxwrapper .neuf-choice',
        function(e) {

            e.preventDefault();

            const $this = $(this),
                v = parseInt($this.data('value')),
                $msg = $('#msg_neuf_import');

            if (v === 2) {

                if (!$msg.hasClass('none')) {

                    $msg.addClass('none');


                }


            } else {

                if ($msg.hasClass('none')) {

                    $msg.removeClass('none');


                }

            }

            $this.addClass('active')
                .siblings()
                .removeClass('active');



        });

    $(".date_immatriculation").datepicker();

    $(".date_immatriculation").datepicker("option", "dateFormat", "dd-mm-yy");

});