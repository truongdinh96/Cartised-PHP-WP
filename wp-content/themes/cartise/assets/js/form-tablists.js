jQuery(function($) {

    $('.section-carte-grise-form .form-main li').click(function(e) {

        e.preventDefault();

        const $tab = $(this),
            layout_id = $tab.data('layout-id'),
            tabs_length = $tab.parent().find('> li').length,
            $layout = $('.cartegriseminute_layout__' + layout_id),
            $group_choice = $layout.find('.groupChoice-layout');

        $layout.find('form')[0].reset();

        $layout.find('label.neuf-choice[data-value="occasion-importee"]').trigger('click');

        if (!$tab.hasClass('active')) {

            $tab.siblings().removeClass('active');

            $tab.addClass('active');

            $layout.removeClass('none')
                .siblings('.tab_cartegriseminute_layout')
                .addClass('none');

        }

        const index = $tab.index();

        $('#msg_neuf_import').removeClass('none');

        if (index === 0 || index === 1 || index === 2) {

            $layout.find('.renseignez-layout').addClass('none');
            $layout.find('.inputbox-renseignez').removeClass('none');

            const $link = $layout.find('.immatriculation-link');

            if (index === 0) {

                $link.removeClass('none');

            } else {

                $link.addClass('none');

            }

            $link.trigger('click');

            createGroupChoiceLayout1($group_choice, true);

        } else if (index === 3) {

            $('#msg_neuf_import').addClass('none');

        }

        // last tab
        else if (index === tabs_length - 1) {

            $layout.find('.renseignez-layout').addClass('none');
            $layout.find('.inputbox-renseignez').removeClass('none');

            $layout.find('.immatriculation-link').removeClass('none')
                .trigger('click');

            createGroupChoiceLayout1($group_choice, true);

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

    function neufChoiceChanged(e) {

        e.preventDefault();

        const $this = $(this),
            v = $this.data('value').toString().trim(),
            $msg = $('#msg_neuf_import'),
            $tab = $this.closest('.tab_cartegriseminute_layout'),
            layout_id = parseInt($tab.data('layout-id')),
            $txtNeufChoice = $(`#txtNeufChoice_l${layout_id}`);

        $this.addClass('active')
            .siblings()
            .removeClass('active');

        $txtNeufChoice.val(v);

        if (v === 'occasion-fr') {

            if (!$msg.hasClass('none')) {

                $msg.addClass('none');
            }


        } else {

            if ($msg.hasClass('none')) {

                $msg.removeClass('none');
            }

        }
    }

    function emtyGroupChoice($elem) {

        $elem.html('');

    }

    function createGroupChoice($elem) {

        createGroupChoiceLayout1($elem);

    }

    function inOfEntries(set, v) {

        return set.indexOf(v) !== -1;

    }

    function removeEnergieOptions($parent) {

        $parent.find('.inputbox.energie').remove();

    }

    function showEnergieOptions($parent) {

        let html_engine_options = createEngineOptionsHTML($parent),
            html = `<div class="inputbox energie nopad">
                        <label>Energie <span class="red">*</span></label>
                        <small>colonne P.3 de la carte grise</small>
                        <select name="energie" class="form-control">` + html_engine_options + `</select>
                    </div>`;

        $parent.prepend(html);

    }

    function showPtacOptions($parent) {

        let html_ptac_options = createPTACOptionsHTML($parent),
            html = `<div class="inputbox ptac nopad">
                        <label>PTAC <span class="red">*</span></label>                    
                        <select name="ptac" class="form-control">` + html_ptac_options + `</select>
                    </div>`;

        $parent.prepend(html);

    }

    function removePtacOptions($parent) {

        $parent.find('.inputbox.ptac').remove();

    }

    function showCarrosseriesTmOptions($parent) {

        let html_carrosseries_tm_options = createCarrosseriesTmOptionsHTML($parent),
            html = `<div class="inputbox carrosseries_tm nopad">
                        <label>Carrosseries <span class="red">*</span></label>                    
                        <select name="carrosseries" class="form-control">` + html_carrosseries_tm_options + `</select>
                    </div>`;

        $parent.prepend(html);

    }

    function removeCarrosseriesTmOptions($parent) {

        $parent.find('.inputbox.carrosseries_tm').remove();

    }

    function showCarrosseriesCyclOptions($parent) {

        let html_carrosseries_cycl_options = createCarrosseriesCyclOptionsHTML($parent),
            html = `<div class="inputbox carrosseries_cycl nopad">
                        <label>Carrosseries <span class="red">*</span></label>                    
                        <select name="carrosseries" class="form-control">` + html_carrosseries_cycl_options + `</select>
                    </div>`;

        $parent.prepend(html);

    }

    function removeCarrosseriesCyclOptions($parent) {

        $parent.find('.inputbox.carrosseries_cycl').remove();

    }

    function showCarrosseriesQmOptions($parent) {

        let html_carrosseries_qm_options = createCarrosseriesQmOptionsHTML($parent),
            html = `<div class="inputbox carrosseries_qm nopad">
                        <label>Carrosseries <span class="red">*</span></label>                    
                        <select name="carrosseries" class="form-control">` + html_carrosseries_qm_options + `</select>
                    </div>`;

        $parent.prepend(html);

    }

    function removeCarrosseriesQmOptions($parent) {

        $parent.find('.inputbox.carrosseries_qm').remove();

    }

    function vehiculesSelectBoxChanged(e) {

        const vehiculesGroupsVHidden = ['velomoteur_et_cyclomoteur', 'rem', 'resp', 'engine'],
            vehiculesGroupsEnergieVHidden = ['motocyclette', 'velomoteur_et_cyclomoteur', 'rem',
                'resp', 'tricycles_tm', 'cyclomoteur', 'quadricycles', 'engin'
            ],
            vehiculesGroupsPTACVHidden = ['vp', 'vehicule_societe', 'motocyclette', 'velomoteur_et_cyclomoteur',
                'bus_tcp', 'tracteur_routier', 'rem', 'resp', 'tricycles_tm', 'cyclomoteur',
                'quadricycles', 'engin'
            ],
            vehiculesGroupsCarrosserieTmVHidden = ['vp', 'vehicule_societe', 'motocyclette', 'velomoteur_et_cyclomoteur',
                'camion', 'bus_tcp', 'tracteur_routier', 'vehicule', 'rem', 'resp', 'cyclomoteur', 'quadricycles', 'engin'
            ],
            vehiculesGroupsCarrosserieCyclVHidden = ['vp', 'vehicule_societe', 'motocyclette', 'velomoteur_et_cyclomoteur',
                'camion', 'bus_tcp', 'tracteur_routier', 'vehicule', 'rem', 'resp', 'tricycles_tm', 'quadricycles', 'engin'
            ],
            vehiculesGroupsCarrosserieQmVHidden = ['vp', 'vehicule_societe', 'motocyclette', 'velomoteur_et_cyclomoteur',
                'camion', 'bus_tcp', 'tracteur_routier', 'vehicule', 'rem', 'resp', 'cyclomoteur', 'tricycles_tm', 'engin'
            ];

        const v = $(this).val(),
            $parent = $(this).closest('form'),
            $groupChoice = $parent.find('.groupChoice-layout');

        //console.log(v);

        if (inOfEntries(vehiculesGroupsVHidden, v)) {

            emtyGroupChoice($groupChoice);

        } else {

            createGroupChoice($groupChoice);

            if (inOfEntries(vehiculesGroupsEnergieVHidden, v)) {

                removeEnergieOptions($groupChoice);

            } else {

                showEnergieOptions($groupChoice);

            }

            if (inOfEntries(vehiculesGroupsPTACVHidden, v)) {

                removePtacOptions($groupChoice);

            } else {

                showPtacOptions($groupChoice);

            }

            if (inOfEntries(vehiculesGroupsCarrosserieTmVHidden, v)) {

                removeCarrosseriesTmOptions($groupChoice);

            } else {

                showCarrosseriesTmOptions($groupChoice);

            }

            if (inOfEntries(vehiculesGroupsCarrosserieCyclVHidden, v)) {

                removeCarrosseriesCyclOptions($groupChoice);

            } else {

                showCarrosseriesCyclOptions($groupChoice);

            }

            if (inOfEntries(vehiculesGroupsCarrosserieQmVHidden, v)) {

                removeCarrosseriesQmOptions($groupChoice);

            } else {

                showCarrosseriesQmOptions($groupChoice);

            }

        }


    }

    function extractDataOptionsToHTML($elem, property_option) {

        let options = $elem.data(property_option);
        options = options.split('\n');

        html = '';

        options.forEach(option => {

            pieces = option.split('|');

            html += '<option value="' + pieces[0].trim() + '">' + pieces[1].trim() + '</option>';

        });

        return html;

    }

    function createEngineOptionsHTML($elem) {

        return extractDataOptionsToHTML($elem, 'engine-options');

    }

    function createPTACOptionsHTML($elem) {

        return extractDataOptionsToHTML($elem, 'ptac-options');

    }

    function createCarrosseriesTmOptionsHTML($elem) {

        return extractDataOptionsToHTML($elem, 'carrosserie-tm-options');

    }

    function createCarrosseriesCyclOptionsHTML($elem) {

        return extractDataOptionsToHTML($elem, 'carrosserie-cycl-options');

    }

    function createCarrosseriesQmOptionsHTML($elem) {

        return extractDataOptionsToHTML($elem, 'carrosserie-qm-options');

    }

    function createGroupChoiceLayout1($parent, isGenerateEnergie) {

        let html = `<div class="inputbox nopad">
                        <label>Puissance fiscale (cv) <span class="red">*</span></label>
                        <small>colonne P.6 de la carte grise</small>
                        <input type="text" 
                                name="chevaux_fiscaux" 
                                id="chevaux_fiscaux" 
                                class="form-control" 
                                value="" 
                                pattern="[0-9]{1,2}"
                                required>
                    </div>
                    <div class="inputbox nopad">
                        <label>Mise en circulation <span class="red">*</span></label>
                        <small>colonne B de la carte grise</small>
                        <input type="text" 
                                name="date_immatriculation"
                                class="form-control date_immatriculation" 
                                autocomplete="off" 
                                value=""
                                required>
                    </div>
                    <div class="inputbox nopad">
                        <label>Taux de CO2 (gr/Km) <span class="red">*</span></label>
                        <small>colonne V.7 de la carte grise </small>
                        <input name="co2" 
                                type="text" 
                                id="co2" 
                                class="form-control"
                                pattern="[0-9]{1,}"
                                required>
                    </div>`;

        $parent.html(html);

        if (isGenerateEnergie) {

            showEnergieOptions($parent);

        }

        const $date_immatriculation = $parent.find('.date_immatriculation');

        $date_immatriculation.datepicker();

        $date_immatriculation.datepicker("option", "dateFormat", "yy-mm-dd");

    }

    function initialize() {

        $parent = $('.tab_cartegriseminute_layout:not(.none)').find('.groupChoice-layout');

        createGroupChoiceLayout1($parent, true);

    }

    $(document).on('click',
            '.neuf-boxwrapper .neuf-choice', neufChoiceChanged)
        .on('change', 'select[name="type_vehicule"]', vehiculesSelectBoxChanged);

    initialize();

});