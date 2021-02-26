<?php

class TYPE_VEHICLES
{

    const PERSONAL_VEHICLE = 1;
    const TWO_WHEEL_MOTORCYCLE_VEHICLE = 2;
    const THREE_WHEEL_MOTORCYCLE_VEHICLE = 3;
    const THREE_WHEEL_MOTORCYCLE_VEHICLE_HAS_ENGINE = 3;
    const FOUR_WHEEL_MOTORCYCLE_VEHICLE_HAS_ENGINE = 4;
    const MULTIPURPOSE_VEHICLE = 5; // xe đa dụng
    const SEMI_TRAILER_VEHICLE = 6; // xe rơ mooc
    const TRUCK_VEHICLE_LARGER_THAN_3T = 7;
    const BUS_VEHICLE_LARGER_THAN_3T = 8;
    const MOTORCYCLE_VEHICLE_SMALLER_THAN_50CC = 9;
    const TRACTOR_VEHICLE_LARGER_THAN_3T = 10; // xe đầu kéo trên 3t
    const TRAVEL_TRAILER_VEHICLE = 11; // xe du lịch có đầu kéo
    const AGRICULTURAL_MACHINES_VEHICLE = 12; // xe nông nghiệp
    const UTILITY_VEHICLE = 13; // xe tiện ích

}

function print_genre_options()
{

    $section_cartise_form = get_option('section-cartise-form_option_name');

    $genres_option = $section_cartise_form['cartiseform-genre-select-id'];
    $genres_option = preg_split("/\R/", $genres_option);

    foreach ($genres_option as $genre) :

        $option = explode('|', $genre);
        $option = array_map('trim', $option); ?>

        <option value="<?php echo $option[0] ?>"><?php echo $option[1] ?></option>

    <?php endforeach;

}

function get_label_option_by_value($field_id, $v)
{

    $section_cartise_form = get_option('section-cartise-form_option_name');

    $options = $section_cartise_form[$field_id];

    $data = preg_split("/\R/", $options);

    foreach ($data as $key => $row) :

        $pieces = explode('|', $row);
        $value = trim($pieces[0]);
        $label = trim($pieces[1]);

        if ($value === $v) return $label;

    endforeach;

    return null;

}

function get_energie_options()
{

    $section_cartise_form = get_option('section-cartise-form_option_name');

    $energies_option = $section_cartise_form['cartiseform-energie-select-id'];

    return $energies_option;

}

function get_ptac_options()
{

    $section_cartise_form = get_option('section-cartise-form_option_name');

    $ptac_option = $section_cartise_form['cartiseform-ptac-select-id'];

    return $ptac_option;

}

function print_energie_options()
{

    $energies_option = get_energie_options();

    $energies_option = preg_split("/\R/", $energies_option);

    foreach ($energies_option as $energie) :

        $option = explode('|', $energie);
        $option = array_map('trim', $option); ?>

        <option value="<?php echo $option[0] ?>"><?php echo $option[1] ?></option>

    <?php endforeach;

}

function get_carrosserie_tm_options()
{

    $section_cartise_form = get_option('section-cartise-form_option_name');

    $carrosserie_tm_options = $section_cartise_form['cartiseform-carrosserie-tm-select-id'];

    return $carrosserie_tm_options;

}

function get_carrosserie_cycl_options()
{

    $section_cartise_form = get_option('section-cartise-form_option_name');

    $carrosserie_cycl_options = $section_cartise_form['cartiseform-carrosserie-cycl-select-id'];

    return $carrosserie_cycl_options;

}

function get_carrosserie_qm_options()
{

    $section_cartise_form = get_option('section-cartise-form_option_name');

    $carrosserie_qm_options = $section_cartise_form['cartiseform-carrosserie-qm-select-id'];

    return $carrosserie_qm_options;

}

function print_demarche_options()
{

    $section_cartise_form = get_option('section-cartise-form_option_name');

    $selectionnez_option = $section_cartise_form['cartiseform-selectionnez-select-id'];
    $selectionnez_option = preg_split("/\R/", $selectionnez_option);

    foreach ($selectionnez_option as $selectionnez) :

        $option = explode('|', $selectionnez);
        $option = array_map('trim', $option); ?>

        <option value="<?php echo $option[0] ?>"><?php echo $option[1] ?></option>

    <?php endforeach;

}

function print_catersis_form_l1()
{ ?>

    <form id="frmCartise_l1" method="post" action="<?= "//{$_SERVER['SERVER_NAME']}/calculate-form" ?>">

        <div class="inputbox">
            <label>Indiquez votre Code postal <span class="red">*</span></label>
            <input type="text"
                   name="codepostal"
                   id="codepostal"
                   class="majuscules form-control"
                   value=""
                   pattern="[0-9]{5,5}"
                   required>
        </div>
        <div class="inputbox">
            <label>Renseignez les caractéristiques du véhicule</label>
            <div class="item-layout">
                <input type="text" name="immatriculation" class="majuscules form-control"
                       placeholder="1234 AB 78 ou AB-123-CD" value="">
            </div>
        </div>

        <div class="imm-link-action" style="">
            <a class="immatriculation-link" href="#">
                    <span class="icon">
                        <span class="fa fa-question-circle"></span>
                    </span>
                <span>Immatriculation inconnue / import</span>
            </a>
        </div>

        <div class="renseignez-layout mtop20">
            <div class="inputbox nopad">
                <label>Genre <span class="red">*</span></label>
                <small>colonne J.1 de la carte grise</small>
                <select name="type_vehicule" class="form-control">

                    <?php print_genre_options(); ?>

                </select>
            </div>
            <div class="inputbox nopad">
                <label>Neuf / Occasion</label>
                <small>véhicule FR ou étranger</small>
                <div class="boxwrapper neuf-boxwrapper">
                    <label class="neuf-choice" data-value="<?= OCCASION_FR ?>">
                        Occasion FR
                    </label>
                    <label class="neuf-choice active" data-value="<?= OCCASION_IMPORTEE ?>">
                        Occasion importée
                    </label>
                </div>
            </div>

            <div class="groupChoice-layout mtop10"
                 data-engine-options="<?= get_energie_options() ?>"
                 data-ptac-options="<?= get_ptac_options() ?>"
                 data-carrosserie-tm-options="<?= get_carrosserie_tm_options() ?>"
                 data-carrosserie-cycl-options="<?= get_carrosserie_cycl_options() ?>"
                 data-carrosserie-qm-options="<?= get_carrosserie_qm_options() ?>"></div>

        </div>

        <div class="inputbox submitForm">
            <input class="btn btn-primary btnCalculer" type="submit" value="Calculer">
        </div>

        <input type="hidden" id="txtNeufChoice_l1" name="neuf-choice" value="occasion-importee"/>

    </form>

<?php }

function print_catersis_form_l2()
{ ?>

    <form id="frmCartise_l2" method="post" action="<?= "//{$_SERVER['SERVER_NAME']}/calculate-form" ?>">

        <div class="inputbox">
            <label>Renseignez l'immatriculation *</label>
            <input type="text" name="immatriculation_declaration_cession" id="immatriculation_declaration_cession"
                   class="majuscules form-control" placeholder="1234 AB 78 ou AB-123-CD" value="">
        </div>
        <div class="inputbox">
            <label>Indiquez la date de la vente *</label>
            <input type="text" name="date_declaration_cession" id="l2__date_declaration_cession" class="form-control"
                   value="">
        </div>
        <div class="inputbox">
            <label>Indiquez l'heure de la vente *</label>
            <input type="time" name="heure_declaration_cession" class="form-control" placeholder="HH:MM" value="">
        </div>

        <div class="inputbox submitForm">
            <input class="btn btn-primary btnCalculer" type="submit" value="Calculer">
        </div>

    </form>


<?php }

function print_catersis_form_l3()
{ ?>

    <form id="frmCartise_l3" method="post" action="<?= "//{$_SERVER['SERVER_NAME']}/calculate-form" ?>">

        <div class="inputbox">
            <label>Sélectionnez votre démarche</label>
<!--            <select name="demarche" class="form-control">-->

                <?php print_demarche_options(); ?>

            </select>
        </div>
        <div class="inputbox">
            <label>Indiquez votre Code postal <span class="red">*</span></label>
            <input type="text"
                   name="codepostal"
                   id="l3__codepostal"
                   class="majuscules form-control"
                   value=""
                   pattern="[0-9]{5,5}"
                   required>
        </div>
        <div class="inputbox">
            <label>Renseignez les caractéristiques du véhicule</label>
            <div class="item-layout">
                <input type="text" name="immatriculation" class="majuscules form-control"
                       placeholder="1234 AB 78 ou AB-123-CD" value="">
            </div>
        </div>
        <div class="inputbox inputbox-renseignez">
            <label>Renseignez les caractéristiques du véhicule</label>
            <div class="item-layout">
                <input name="immatriculation"
                       class="majuscules form-control"
                       placeholder="1234 AB 78 ou AB-123-CD"
                       value=""
                       type="text"
                       style="">
            </div>
            <div class="imm-link">
                <a class="immatriculation-link" href="#">
                        <span class="icon">
                            <span class="fa fa-question-circle"></span>
                        </span>
                    <span>Immatriculation inconnue / import</span>
                </a>
            </div>
        </div>
        <div class="renseignez-layout none mtop20">
            <div class="inputbox nopad">
                <label>Genre <span class="red">*</span></label>
                <small>colonne J.1 de la carte grise</small>
                <select name="type_vehicule" class="form-control">

                    <?php print_genre_options() ?>

                </select>
            </div>
            <div class="inputbox nopad">
                <label>Neuf / Occasion</label>
                <small>véhicule FR ou étranger</small>
                <div class="boxwrapper neuf-boxwrapper">
                    <label class="neuf-choice" data-value="<?= OCCASION_FR ?>">Occasion FR</label>
                    <label class="neuf-choice active" data-value="<?= OCCASION_IMPORTEE ?>">Occasion importée</label>
                </div>
            </div>
            <div class="groupChoice-layout mtop10"
                 data-engine-options="<?= get_energie_options() ?>"
                 data-ptac-options="<?= get_ptac_options() ?>"
                 data-carrosserie-tm-options="<?= get_carrosserie_tm_options() ?>"
                 data-carrosserie-cycl-options="<?= get_carrosserie_cycl_options() ?>"
                 data-carrosserie-qm-options="<?= get_carrosserie_qm_options() ?>"></div>
        </div>

        <div class="inputbox submitForm">
            <input class="btn btn-primary btnCalculer" type="submit" value="Calculer">
        </div>

        <input type="hidden" id="txtNeufChoice_l3" name="neuf-choice" value="occasion-importee"/>

    </form>


<?php }

function print_catersis_form_alert_msg()
{ ?>

    <div id="msg_neuf_import" class="alert alert-info" role="alert" style="">
        Pour un véhicule importé de l'étranger, la puissance fiscale et/ou le taux de CO2 ne sont pas toujours connus.
        Indiquez les valeurs que vous pensez être correctes. Une fois le montant de la taxe déterminée précisemment par
        l'Administration, une régularisation
        sera faite avec vous le cas échéant. Le traitement du dossier peut prendre entre 3 et 4 semaines.
    </div>
<?php }

function print_catersis_form_tabs_list()
{ ?>

    <ul class="tab-icon">
        <li data-layout-id="1" class="active">
            <a href="#">
                <div class="icon">
                    <img src="<?= TITULAIRE_ACTIVE_IMAGE ?>" alt="" class="active-img">
                    <img src="<?= TITULAIRE_IMAGE ?>" alt="">
                </div>
                <div class="text">
                    Changement<br> de titulaire
                </div>
            </a>
        </li>
        <li data-layout-id="1" class="">
            <a href="#">
                <div class="icon">
                    <img src="<?= DOMICILE_IMAGE ?>" alt="" class="active-img">
                    <img src="<?= DOMICILE_ACTIVE_IMAGE ?>" alt="">
                </div>
                <div class="text">
                    Changement<br> de domicile
                </div>
            </a>
        </li>
        <li data-layout-id="1" class="">
            <a href="#">
                <div class="icon">
                    <img src="<?= DUPLICATA_IMAGE ?>" alt="" class="active-img">
                    <img src="<?= DUPLICATA_ACTIVE_IMAGE ?>" alt="">
                </div>
                <div class="text">
                    Duplicata<br> carte grise
                </div>
            </a>
        </li>
        <li data-layout-id="2" class="">
            <a href="#">
                <div class="icon">
                    <img src="<?= CESSION_IMAGE ?>" alt="" class="active-img">
                    <img src="<?= CESSION_ACTIVE_IMAGE ?>" alt="">
                </div>
                <div class="text">
                    Déclaration<br> de cession
                </div>
            </a>
        </li>
        <li data-layout-id="3" class="">
            <a href="#">
                <div class="icon">
                    <img src="<?= AUTRES_IMAGE ?>" alt="" class="active-img">
                    <img src="<?= AUTRES_ACTIVE_IMAGE ?>" alt="">
                </div>
                <div class="text">
                    Autres<br> démarches
                </div>
            </a>
        </li>
    </ul>

<?php }

function print_catersis_form_heading()
{ ?>

    <h1>PRIX DE VOTRE CARTE GRISE EN LIGNE</h1>
    <h4 class="mtop14">
        Le tarif de votre carte grise dépend de plusieurs critères. Consultez le prix de votre démarche en remplissant
        le formulaire ci-dessous pour faire votre demande de carte grise en ligne.
    </h4>

<?php }

function print_catersis_form_rightSide()
{ ?>

    <div class="wrapper-right-logo">
        <div class="title">Votre carte grise en ligne en 3 étapes</div>
        <div class="content">
            <div class="item-content mb-30">
                <img src="<?= LIGNE_IMAGE_1 ?>" alt="">
            </div>
            <div class="item-content">
                <img src="<?= LIGNE_IMAGE_2 ?>" alt="">
            </div>
        </div>
    </div>

<?php }

function print_catersis_form()
{ ?>

    <div class="section-fullwidth section-carte-grise-form">

        <div class="container">

            <?php print_catersis_form_heading(); ?>

            <div class="form-main">

                <?php print_catersis_form_tabs_list(); ?>

                <div class="form-content ohidden">

                    <div class="split-columns two-columns-layout">

                        <!--<!DOCTYPE html>-->

                        <!--<html xmlns:esi="http://www.edge-delivery.org/esi/1.0" lang="fr" class="no-js blink">&lt;!&ndash;<![endif]&ndash;&gt;-->
                        <meta charset="utf-8"/>
                        <title>Simulateur du coût du certificat d&#039;immatriculation - Données -
                            service-public.fr</title>
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>


                        <!-- Styles -->
                        <link href="https://www.service-public.fr/simulateur/calcul/assets/particuliers/bootstrap/css/bootstrap.css?version=1.4.21"
                              rel="stylesheet">

                        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
                        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
                        <meta name="audience" content="particuliers"/>
                        <meta name="sp_cookie_domain" content="service-public.fr"/>
                        <meta name="annuaire_root" content="https://lannuaire.service-public.fr"/>
                        <meta name="sp_root" content="https://www.service-public.fr"/>
                        <link rel="icon"
                              href="https://www.service-public.fr/resources/v-16617db380/web/img/favicon/favicon.ico"
                              type="image/x-icon" sizes="16x16 32x32 48x48 64x64">
                        <!--[if IE]>
                        <link rel="shortcut icon"
                              href="https://www.service-public.fr/resources/v-16617db380/web/img/favicon/favicon.ico"
                              type="image/x-icon"><![endif]-->
                        <link rel="icon apple-touch-icon-precomposed"
                              href="https://www.service-public.fr/resources/v-16617db380/web/img/favicon/favicon-152.png">
                        <meta name="msapplication-TileColor" content="#b91d47">
                        <meta name="msapplication-TileImage"
                              content="/resources/v-16617db380/web/img/favicon/favicon-144.png">
                        <link rel="apple-touch-icon" sizes="152x152"
                              href="https://www.service-public.fr/resources/v-16617db380/web/img/favicon/favicon-152.png">
                        <link rel="apple-touch-icon" sizes="144x144"
                              href="https://www.service-public.fr/resources/v-16617db380/web/img/favicon/favicon-144.png">
                        <link rel="apple-touch-icon" sizes="120x120"
                              href="https://www.service-public.fr/resources/v-16617db380/web/img/favicon/favicon-120.png">
                        <link rel="apple-touch-icon" sizes="114x114"
                              href="https://www.service-public.fr/resources/v-16617db380/web/img/favicon/favicon-114.png">
                        <link rel="apple-touch-icon" sizes="72x72"
                              href="https://www.service-public.fr/resources/v-16617db380/web/img/favicon/favicon-72.png">
                        <link rel="apple-touch-icon"
                              href="https://www.service-public.fr/resources/v-16617db380/web/img/favicon/favicon-57.png">
                        <script type="text/javascript"
                                src="https://www.service-public.fr/resources/v-16617db380/web/js/lib/modernizr.js"></script>
                        <!--[if lt IE 8]>
                        <link rel="stylesheet" type="text/css"
                              href="https://www.service-public.fr/resources/v-16617db380/web/css/styles-sp.min.css"/>
                        <link rel="stylesheet" type="text/css"
                              href="https://www.service-public.fr/resources/v-16617db380/web/css/styles-sp-more-ie.min.css"/>
                        <![endif]-->
                        <!--[if gte IE 8]><!-->
                        <link rel="stylesheet" type="text/css"
                              href="https://www.service-public.fr/resources/v-16617db380/web/css/styles-sp.min.css"/>
                        <link rel="stylesheet" type="text/css"
                              href="https://www.service-public.fr/resources/v-16617db380/web/css/styles-sp-more.min.css"/>
                        <!--<![endif]-->
                        <!--[if lt IE 9]>
                        <script src="https://www.service-public.fr/resources/v-16617db380/web/js/ie/html5shiv.min.js"></script>
                        <script>html5.addElements('svg');</script>
                        <script src="https://www.service-public.fr/resources/v-16617db380/web/js/ie/respond.min.js"></script>
                        <![endif]-->

                        <link rel='stylesheet' type='text/css'
                              href="https://www.service-public.fr/simulateur/calcul/assets/base/css/g6k.css?version=1.4.21"/>
                        <link rel='stylesheet' type='text/css'
                              href="https://www.service-public.fr/simulateur/calcul/assets/base/js/libs/fontawesome/css/all.min.css?version=1.4.21"/>
                        <link rel="stylesheet" type="text/css"
                              href="https://www.service-public.fr/simulateur/calcul/assets/base/widgets/abListbox/css/listbox.css?version=1.4.21"/>
                        <link rel="stylesheet" type="text/css"
                              href="https://www.service-public.fr/simulateur/calcul/assets/base/widgets/abDatepicker/css/datepicker.css?version=1.4.21"/>


                        <link rel='stylesheet' type='text/css'
                              href="https://www.service-public.fr/simulateur/calcul/assets/particuliers/css/cout-certificat-immatriculation.css?version=1.4.21"/>


                        <div id="outer-wrap">
                            <div id="inner-wrap">
                                <main class="main" id="main" role="main">
                                    <div class="container main-container">
                                        <h1 class="sr-only">Simulateur Particuliers</h1>
                                        <div class="col-main">
                                            <article class="article">
                                                <div id="step0" class="step-page step-container dynamic">

                                                    <form method="post" action="#main" enctype="multipart/form-data"
                                                          id="g6k_form">


                                                        <div id="donnees-panel-1" class="step-panel-container">
                                                            <fieldset
                                                                    class="fieldset-container disposition-classic form-horizontal"
                                                                    id="donnees-panel-1-fieldset-1">
                                                                <legend>
                                                                    Votre démarche
                                                                </legend>

                                                                <div id="demarche-container" data-type="choice"
                                                                     data-field-position="1"
                                                                     class="field-container form-group underlabel">
                                                                    <label id="demarche-label"
                                                                           class="control-label"
                                                                           for="demarche"><span
                                                                                class="asterisk"> * </span>Sélectionnez
                                                                        votre démarche<span
                                                                                class="asterisk"> * </span></label>
                                                                    <div class="input-group">
                                                                        <select
                                                                                class="form-control" id="demarche"
                                                                                name="demarche" data-widget="abListbox"
                                                                                aria-required="true" onchange="dropdownOne()">
                                                                            <option id="demarche_none"
                                                                                    value=""></option>
                                                                            <option id="demarche_1" value="1">Première
                                                                                immatriculation en France
                                                                                d'un véhicule
                                                                            </option>
                                                                            <option id="demarche_2" value="2">
                                                                                Immatriculation d’un véhicule d’occasion
                                                                                (changement de titulaire du certificat)
                                                                            </option>
                                                                            <option id="demarche_3" value="5">Changement
                                                                                d'adresse
                                                                            </option>
                                                                            <option id="demarche_4" value="4">Duplicata
                                                                                - véhicule FNI (ancienne
                                                                                immatriculation de type "1234 AB 01")
                                                                            </option>
                                                                            <option id="demarche_5" value="3">Duplicata
                                                                                - véhicule SIV
                                                                                (immatriculation type "AB 123 CD")
                                                                            </option>
                                                                            <option id="demarche_6" value="6">Changement
                                                                                d'état matrimonial,
                                                                                ajout/suppression d'un nom (mariage,
                                                                                Pacs, divorce, veuvage)
                                                                            </option>
                                                                            <option id="demarche_7" value="16">Héritage
                                                                                (hors veuvage) d'un
                                                                                véhicule
                                                                            </option>
                                                                            <option id="demarche_8" value="15">Passage
                                                                                en véhicule de collection
                                                                            </option>
                                                                            <option id="demarche_9" value="18">Ajout
                                                                                d'un autre propriétaire (sauf
                                                                                conjoint)
                                                                            </option>
                                                                            <option id="demarche_10" value="17">Achat
                                                                                par locataire suite leasing
                                                                            </option>
                                                                            <option id="demarche_11" value="8">
                                                                                Usurpation du numéro
                                                                                d'immatriculation du véhicule
                                                                            </option>
                                                                            <option id="demarche_12" value="9">
                                                                                Utilisation de toutes les cases
                                                                                réservées pour les contrôles techniques
                                                                            </option>
                                                                            <option id="demarche_13" value="10">
                                                                                Modification des caractéristiques
                                                                                techniques du véhicule
                                                                            </option>
                                                                            <option id="demarche_14" value="11">
                                                                                Modification de l'usage du
                                                                                véhicule
                                                                            </option>
                                                                            <option id="demarche_15" value="7">
                                                                                Correction d'erreur de saisie sur le
                                                                                certificat du fait de l'administration
                                                                            </option>
                                                                            <option id="demarche_16" value="21">Véhicule
                                                                                acquis en remplacement d'un
                                                                                véhicule détruit lors d'une catastrophe
                                                                                naturelle
                                                                            </option>
                                                                            <option id="demarche_17" value="22">Demande
                                                                                de duplicata d'un certificat
                                                                                détruit lors d'une catastrophe naturelle
                                                                            </option>
                                                                            <option id="demarche_18" value="12">
                                                                                Changement d'état civil
                                                                            </option>
                                                                            <option id="demarche_19" value="13">
                                                                                Changement de dénomination sociale
                                                                                d'une entreprise ou d'une association
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <button type="button" href="#help-demarche"
                                                                            data-toggle="collapse"
                                                                            data-target="#help-demarche"
                                                                            aria-controls="help-demarche"
                                                                            class="btn btn-help collapsed"
                                                                            title="aide sur Sélectionnez votre démarche"><span
                                                                                class="icon icon-help"
                                                                                aria-hidden="true"></span><span
                                                                                class="blank">Aide</span></button>
                                                                    <div id="demarche-field-error"
                                                                         class="field-error"></div>
                                                                    <div class="post-note">
                                                                        <p>Lorsque le certificat d'immatriculation est
                                                                            demandé pour plusieurs
                                                                            motifs, c'est le motif qui entraîne le coût
                                                                            le plus élevé qui est
                                                                            pris en compte.</p>

                                                                    </div>
                                                                    <div class="collapse help-panel" id="help-demarche">
                                                                        <dl>
                                                                            <dt>Sélectionnez votre démarche</dt>
                                                                            <dd>
                                                                                <p><u>Première immatriculation en France
                                                                                        d'un véhicule</u></p>
                                                                                <p>Cela concerne les véhicules :</p>
                                                                                <p>- achetés neuf en France,</p>
                                                                                <p>- achetés neuf à l'étranger et
                                                                                    importés en France,</p>
                                                                                <p>- loués neuf, avec option d'achat ou
                                                                                    pour une durée d’au
                                                                                    moins 2 ans, en France ou à
                                                                                    l'étranger et importés en
                                                                                    France,</p>
                                                                                <p>- achetés d'occasion à l'étranger et
                                                                                    importés en France.</p>
                                                                                <p><u>Nouvelle immatriculation d'un
                                                                                        véhicule d'occasion</u></p>
                                                                                <p>Si vous achetez un véhicule
                                                                                    d'occasion en France, vous avez
                                                                                    un mois, à partir de la date
                                                                                    inscrite sur le certificat de
                                                                                    cession, pour le faire
                                                                                    immatriculer.</p>
                                                                                <p><u>Duplicata</u></p>
                                                                                <p>Vous pouvez obtenir un duplicata de
                                                                                    votre certificat
                                                                                    d'immatriculation si votre original
                                                                                    a été volé, perdu ou
                                                                                    détérioré.</p>
                                                                                <p>En cas de vol vous devez
                                                                                    préalablement faire une déclaration
                                                                                    au commissariat ou à la
                                                                                    gendarmerie.</p>
                                                                                <p><u>Changement de domicile</u></p>
                                                                                <p>Si vous déménagez, vous devez faire
                                                                                    modifier l'adresse sur
                                                                                    votre certificat d'immatriculation
                                                                                    dans le délai d'un mois,
                                                                                    même si vous souhaitez vendre votre
                                                                                    véhicule.</p>
                                                                                <p><u>Changement d’état matrimonial
                                                                                        (mariage, Pacs, divorce,
                                                                                        veuvage)</u></p>
                                                                                <p>Les certificats d'immatriculation
                                                                                    délivrés à la suite d'un
                                                                                    changement de situation matrimoniale
                                                                                    sont délivrés
                                                                                    gratuitement sur présentation des
                                                                                    pièces justificatives
                                                                                    adéquates. Cela concerne les
                                                                                    demandes :</p>
                                                                                <p>⚬ après mariage ou conclusion d'un
                                                                                    Pacs :</p><br>
                                                                                <ul><br>
                                                                                    <li>ajout du nom de femme mariée au
                                                                                        nom de jeune fille
                                                                                    </li>
                                                                                    <br>
                                                                                    <li>ajout du nom de la femme, du
                                                                                        mari ou du partenaire de
                                                                                        pacs
                                                                                    </li>
                                                                                    <br></ul>
                                                                                <p>⚬ après divorce ou rupture du
                                                                                    Pacs:</p><br>
                                                                                <ul><br>
                                                                                    <li>suppression du nom de femme
                                                                                        mariée,
                                                                                    </li>
                                                                                    <br>
                                                                                    <li>suppression du nom de l'époux,
                                                                                        de la femme ou du
                                                                                        partenaire de Pacs
                                                                                    </li>
                                                                                    <br></ul>
                                                                                <p>⚬ en cas de veuvage :</p><br>
                                                                                <ul><br>
                                                                                    <li>modification de la mention
                                                                                        relative à la situation de
                                                                                        femme mariée,
                                                                                    </li>
                                                                                    <br>
                                                                                    <li>immatriculation au nom du
                                                                                        conjoint survivant d'un
                                                                                        véhicule antérieurement
                                                                                        immatriculé au nom de l'époux
                                                                                        décédé.
                                                                                    </li>
                                                                                    <br>
                                                                                    <li>immatriculation au nom du
                                                                                        conjoint survivant d'un
                                                                                        véhicule antérieurement
                                                                                        immatriculé au nom des deux
                                                                                        époux.
                                                                                    </li>
                                                                                    <br></ul>
                                                                                <p><u>Usurpation du numéro
                                                                                        d'immatriculation du
                                                                                        véhicule</u></p>
                                                                                <p>Un nouveau numéro d’immatriculation
                                                                                    vous sera attribué en cas
                                                                                    de vol ou d’usurpation de vos
                                                                                    plaques d’immatriculation, sur
                                                                                    présentation du dépôt de
                                                                                    plainte.</p>
                                                                                <p><u>Modification des caractéristiques
                                                                                        techniques du
                                                                                        véhicule</u></p>
                                                                                <p>Vous devez faire une déclaration si
                                                                                    vous effectuez une des
                                                                                    transformations suivantes sur votre
                                                                                    véhicule :</p><br>
                                                                                <ul><br>
                                                                                    <li>modification affectant les
                                                                                        caractéristiques suivantes de
                                                                                        la notice descriptive :
                                                                                        puissance, poids et dimensions,
                                                                                        essieux, freinage, organes de
                                                                                        manœuvre, de direction et
                                                                                        de visibilité, énergie,
                                                                                        émissions polluantes et
                                                                                        nuisances (bruit),
                                                                                    </li>
                                                                                    <br>
                                                                                    <li>modification des indications
                                                                                        d'ordre technique du
                                                                                        certificat d'immatriculation
                                                                                        (marque, type, genre,
                                                                                        catégorie, ...), à l'exception
                                                                                        du poids à vide, et de
                                                                                        modifications mineures de la
                                                                                        carrosserie,
                                                                                    </li>
                                                                                    <br>
                                                                                    <li>modification du genre du
                                                                                        véhicule,
                                                                                    </li>
                                                                                    <br>
                                                                                    <li>remplacement autrement qu'à
                                                                                        l'identique de la coque pour
                                                                                        les véhicules sans châssis,
                                                                                    </li>
                                                                                    <br>
                                                                                    <li>débridage d'une moto effectué
                                                                                        par un professionnel, la
                                                                                        faisant passer de la catégorie
                                                                                        A2 à A.
                                                                                    </li>
                                                                                    <br></ul>
                                                                                <br>
                                                                                <p><u>Modification de l'usage du
                                                                                        véhicule </u></p>
                                                                                <p>Sont notamment concernées par la
                                                                                    déclaration :</p><br>
                                                                                <ul><br>
                                                                                    <li>l'aménagement d'une camionnette
                                                                                        en camping-car
                                                                                    </li>
                                                                                    <br>
                                                                                    <li>l'aménagement d'une voiture en
                                                                                        ambulance
                                                                                    </li>
                                                                                    <br>
                                                                                    <li>l'aménagement d'un véhicule de
                                                                                        tourisme pour être
                                                                                        accessible en fauteuil roulant
                                                                                    </li>
                                                                                    <br>
                                                                                    <li>la transformation d'un véhicule
                                                                                        accessible en fauteuil
                                                                                        roulant en véhicule de tourisme
                                                                                    </li>
                                                                                    <br>
                                                                                    <li>la transformation d'un véhicule
                                                                                        utilitaire en véhicule
                                                                                        de tourisme (VT) ou l'inverse
                                                                                    </li>
                                                                                    <br>
                                                                                    <li>l'adjonction d'un side-car.</li>
                                                                                    <br></ul>
                                                                                <br>
                                                                                <p><u>Certificat W garage</u></p>
                                                                                <p>Le certificat W garage permet de
                                                                                    faire circuler un véhicule à
                                                                                    titre provisoire, avant son
                                                                                    immatriculation définitive. Il
                                                                                    est uniquement délivré aux
                                                                                    réparateurs, vendeurs,
                                                                                    transporteurs, carrossiers,
                                                                                    importateurs et constructeurs
                                                                                    pour l'utilisation de certains types
                                                                                    de véhicules.</p>
                                                                                <p><u>Passage en véhicule de
                                                                                        collection</u></p>
                                                                                <p>Le « véhicule de collection » doit
                                                                                    avoir plus de 30 ans. Le
                                                                                    passage en véhicule de collection
                                                                                    n’est pas obligatoire. La
                                                                                    mention « véhicule de collection »
                                                                                    figure en rubrique Z sur
                                                                                    la carte grise (certificat
                                                                                    d'immatriculation).</p>
                                                                                <p><u>Héritage (hors veuvage) d’un
                                                                                        véhicule</u></p>
                                                                                <p>Voir page suivante : <a
                                                                                            href="https://www.service-public.fr/particuliers/vosdroits/F1480"
                                                                                            target="_blank">https://www.service-public.fr/particuliers/vosdroits/F1480</a>
                                                                                </p>
                                                                                <p>Ne sont pas concernées par cette
                                                                                    démarche, la demande de
                                                                                    modification de la mention relative
                                                                                    à la situation de femme
                                                                                    mariée et la demande
                                                                                    d’immatriculation au nom du conjoint
                                                                                    survivant d'un véhicule
                                                                                    antérieurement immatriculé au nom de
                                                                                    l'époux décédé ou au nom des deux
                                                                                    époux. Ces deux démarches
                                                                                    relèvent d’un changement de
                                                                                    situation matrimoniale.</p>
                                                                                <p><u>Ajout d’un autre propriétaire
                                                                                        (hors conjoint)</u></p>
                                                                                <p>Concerne l’ajout en tant que
                                                                                    co-titulaire du certificat, le
                                                                                    concubin ou toute autre personne
                                                                                    ayant un lien de parenté ou
                                                                                    non avec le titulaire.</p>
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                                <div id="franceOuImport-container" data-type="choice"
                                                                     data-expanded="true"
                                                                     data-field-position="2"
                                                                     class="hide field-container form-group new-line underlabel">
                                                                    <label
                                                                            id="franceOuImport-label"
                                                                            class="control-label"><span
                                                                                class="asterisk"> * </span>Précisez
                                                                        si le véhicule a été<span
                                                                                class="asterisk"> * </span> : </label>
                                                                    <div class="input-group">
                                                                        <fieldset id="franceOuImport">
                                                                            <legend class="sr-only">Précisez si le
                                                                                véhicule a été
                                                                            </legend>
                                                                            <label class="choice"
                                                                                   for="franceOuImport_1"><input
                                                                                        id="franceOuImport_1"
                                                                                        type="radio"
                                                                                        name="franceOuImport"
                                                                                        value="1" onclick="checkRadio_One()"/>Acheté neuf en France
                                                                                ou à l'étranger</label>
                                                                            <label class="choice"
                                                                                   for="franceOuImport_2"><input
                                                                                        id="franceOuImport_2"
                                                                                        type="radio"
                                                                                        name="franceOuImport"
                                                                                        value="2" onclick="checkRadio_One()"/>Importé en France
                                                                                après avoir été immatriculé
                                                                                dans un autre pays</label>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div id="franceOuImport-field-error"
                                                                         class="field-error"></div>
                                                                </div>
                                                                <div id="quatriemeChangementDomicile-container"
                                                                     data-type="choice"
                                                                     data-expanded="true" data-field-position="3"
                                                                     class="hide field-container form-group new-line"><label
                                                                            id="quatriemeChangementDomicile-label"
                                                                            class="control-label"><span
                                                                                class="asterisk"> * </span>Vous avez
                                                                        déjà déclaré 3 changements
                                                                        d'adresse pour ce véhicule et vous voulez en
                                                                        déclarer un 4ème (les 3
                                                                        emplacements sur la carte grise sont
                                                                        occupés)<span
                                                                                class="asterisk"> * </span> : </label>
                                                                    <div class="input-group">
                                                                        <fieldset id="quatriemeChangementDomicile">
                                                                            <legend class="sr-only">Vous avez déjà
                                                                                déclaré 3 changements
                                                                                d'adresse pour ce véhicule et vous
                                                                                voulez en déclarer un 4ème
                                                                                (les 3 emplacements sur la carte grise
                                                                                sont occupés)
                                                                            </legend>
                                                                            <label class="choice"
                                                                                   for="quatriemeChangementDomicile_1"><input
                                                                                        id="quatriemeChangementDomicile_1"
                                                                                        type="radio"
                                                                                        name="quatriemeChangementDomicile"
                                                                                        value="1"/>Oui</label>
                                                                            <label class="choice"
                                                                                   for="quatriemeChangementDomicile_2"><input
                                                                                        id="quatriemeChangementDomicile_2"
                                                                                        type="radio"
                                                                                        name="quatriemeChangementDomicile"
                                                                                        value="2"/>Non</label>
                                                                        </fieldset>
                                                                    </div>
                                                                    <button type="button"
                                                                            href="#help-quatriemeChangementDomicile"
                                                                            data-toggle="collapse"
                                                                            data-target="#help-quatriemeChangementDomicile"
                                                                            aria-controls="help-quatriemeChangementDomicile"
                                                                            class="btn btn-help collapsed"
                                                                            title="aide sur Vous avez déjà déclaré 3 changements d'adresse pour ce véhicule et vous voulez en déclarer un 4ème (les 3 emplacements sur la carte grise sont occupés)">
                                                                        <span class="icon icon-help"
                                                                              aria-hidden="true"></span><span
                                                                                class="blank">Aide</span></button>
                                                                    <div id="quatriemeChangementDomicile-field-error"
                                                                         class="field-error"></div>
                                                                    <div class="collapse help-panel"
                                                                         id="help-quatriemeChangementDomicile">
                                                                        <dl>
                                                                            <dt>Vous avez déjà déclaré 3 changements
                                                                                d'adresse pour ce véhicule
                                                                                et vous voulez en déclarer un 4ème (les
                                                                                3 emplacements sur la
                                                                                carte grise sont occupés)
                                                                            </dt>
                                                                            <dd>
                                                                                <p>Au 4ème changement d'adresse, un
                                                                                    nouveau certificat doit être
                                                                                    établi. Vous devrez restituer
                                                                                    l'ancien certificat à la
                                                                                    préfecture (ou à la préfecture de
                                                                                    police de Paris).</p>
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                                <div id="modificationEnergiePropre-container"
                                                                     data-type="choice"
                                                                     data-field-position="4"
                                                                     class="hide field-container form-group new-line"><label
                                                                            id="modificationEnergiePropre-label"
                                                                            class="control-label"
                                                                            for="modificationEnergiePropre"><span
                                                                                class="asterisk"> * </span>Sélectionnez
                                                                        l'objet de la modification<span
                                                                                class="asterisk"> * </span></label>
                                                                    <div class="input-group"><select
                                                                                class="form-control"
                                                                                id="modificationEnergiePropre"
                                                                                name="modificationEnergiePropre"
                                                                                data-widget="abListbox" onchange="selectOption10Dropdown1()">
                                                                            <option id="modificationEnergiePropre_none"
                                                                                    value="0">---------
                                                                            </option>
                                                                            <option id="modificationEnergiePropre_1"
                                                                                    value="1">Pour le faire
                                                                                fonctionner au superéthanol E85 (FE, FL,
                                                                                FN, FG ou FH)
                                                                            </option>
                                                                            <option id="modificationEnergiePropre_2"
                                                                                    value="3">Pour le faire
                                                                                fonctionner à l'électricité, à
                                                                                l'hydrogène ou une combinaison des 2
                                                                                (EE, EH, GL, GH)
                                                                            </option>
                                                                            <option id="modificationEnergiePropre_3"
                                                                                    value="4">Pour le faire
                                                                                fonctionner au gaz naturel véhicules
                                                                                (GNV), du gaz de pétrole
                                                                                liquéfié (GPL)
                                                                            </option>
                                                                            <option id="modificationEnergiePropre_4"
                                                                                    value="5">Autre modification
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <div id="modificationEnergiePropre-field-error"
                                                                         class="field-error"></div>
                                                                </div>


                                                            </fieldset>
                                                            <fieldset
                                                                    class="fieldset-container disposition-classic form-horizontal"
                                                                    id="donnees-panel-1-fieldset-2">
                                                                <legend id="title-typeVehicule" class="hide">
                                                                    Votre véhicule
                                                                </legend>

                                                                <div id="typeVehicule-container" data-type="choice"
                                                                     data-field-position="1"
                                                                     class="hide field-container form-group underlabel">
                                                                    <label
                                                                            id="typeVehicule-label"
                                                                            class="control-label"
                                                                            for="typeVehicule"><span
                                                                                class="asterisk"> * </span>Genre
                                                                        national (J.1)<span
                                                                                class="asterisk"> * </span></label>
                                                                    <div class="input-group"><select
                                                                                class="form-control" id="typeVehicule"
                                                                                name="typeVehicule"
                                                                                data-widget="abListbox" onchange="dropdownTwo()">
                                                                            <option id="typeVehicule_none" value="0">
                                                                                Sélectionnez le genre de votre
                                                                                véhicule
                                                                            </option>
                                                                            <option id="typeVehicule_1" value="1">
                                                                                Véhicule de tourisme (VT ou M1)
                                                                                ex-Voiture particulière (VP)
                                                                            </option>
                                                                            <option id="typeVehicule_2" value="2">
                                                                                Camionnette, Utilitaire pour le
                                                                                transport de marchandises jusqu'à 3,5
                                                                                tonnnes (CTTE, N1)
                                                                            </option>
                                                                            <option id="typeVehicule_3" value="3">
                                                                                Camping-car et autre véhicule VASP
                                                                                d'un poids maximal inférieur ou égal à
                                                                                3,5 tonnes
                                                                            </option>
                                                                            <option id="typeVehicule_4" value="5">Moto
                                                                                jusqu'à 125 cm3 (MTL, L3e,
                                                                                L4e)
                                                                            </option>
                                                                            <option id="typeVehicule_5" value="6">Moto
                                                                                de plus de 125 cm3 (MTT1,
                                                                                MTT2, L3e, L4e)
                                                                            </option>
                                                                            <option id="typeVehicule_6" value="7">
                                                                                Tricycle à moteur (TM, L5e)
                                                                            </option>
                                                                            <option id="typeVehicule_7" value="8">Quad
                                                                                et Quadricycle à moteur (QM,
                                                                                L6e, L7e)
                                                                            </option>
                                                                            <option id="typeVehicule_8" value="9">
                                                                                Cyclomoteur à 3 roues (CYCL,
                                                                                L2e)
                                                                            </option>
                                                                            <option id="typeVehicule_9" value="10">
                                                                                Cyclomoteur à 2 roues ou à 3
                                                                                roues (inférieur ou égal à 50 cm3) non
                                                                                carrossé (CL, L1e, L2e)
                                                                            </option>
                                                                            <option id="typeVehicule_10" value="11">
                                                                                Camion d'un PTAC supérieur à 3,5
                                                                                tonnes et inférieur à 6 tonnes (CAM, N2)
                                                                            </option>
                                                                            <option id="typeVehicule_11" value="12">
                                                                                Camion d'un PTAC supérieur ou
                                                                                égal à 6 tonnes et inférieur à 11 tonnes
                                                                                (CAM, N2)
                                                                            </option>
                                                                            <option id="typeVehicule_12" value="13">
                                                                                Camion d'un PTAC supérieur ou
                                                                                égal à 11 tonnes (CAM, N2, N3)
                                                                            </option>
                                                                            <option id="typeVehicule_13" value="14">
                                                                                Transport en commun de personnes
                                                                                (TCP, M2, M3)
                                                                            </option>
                                                                            <option id="typeVehicule_14" value="16">
                                                                                Tracteur routier jusqu'à 3,5
                                                                                tonnes (TRR, N1)
                                                                            </option>
                                                                            <option id="typeVehicule_15" value="17">
                                                                                Véhicule agricole ou forestier
                                                                                (TRA, MAGA, T, C)
                                                                            </option>
                                                                            <option id="typeVehicule_16" value="18">
                                                                                Remorque ou semi-remorque
                                                                                agricole ou non-agricole (SRAT / SREM /
                                                                                REM / SRTC / RETC / SRSP /
                                                                                RESP / REA / SREA / MIAR / R / S / O)
                                                                            </option>
                                                                            <option id="typeVehicule_17" value="23">
                                                                                Caravane et remorque (genre
                                                                                RESP, O)
                                                                            </option>
                                                                            <option id="typeVehicule_18" value="24">
                                                                                Camping-car et autre véhicule
                                                                                VASP d'un poids maximal supérieur à 3,5
                                                                                tonnes
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <button type="button" href="#help-typeVehicule"
                                                                            data-toggle="collapse"
                                                                            data-target="#help-typeVehicule"
                                                                            aria-controls="help-typeVehicule"
                                                                            class="btn btn-help collapsed"
                                                                            title="aide sur Genre national (J.1)"><span
                                                                                class="icon icon-help"
                                                                                aria-hidden="true"></span><span
                                                                                class="blank">Aide</span></button>
                                                                    <div id="typeVehicule-field-error"
                                                                         class=" field-error"></div>
                                                                    <div class="collapse help-panel"
                                                                         id="help-typeVehicule">
                                                                        <dl>
                                                                            <dt>Genre national (J.1)</dt>
                                                                            <dd>
                                                                                <p>Le genre national du véhicule est
                                                                                    mentionné à la rubrique
                                                                                    'J.1' de la carte grise.</p>
                                                                                <p>Le montant du certificat varie selon
                                                                                    le genre du
                                                                                    véhicule.</p>
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                                <div id="IsCamionnette5Places-container"
                                                                     data-type="choice" data-expanded="true"
                                                                     data-field-position="2"
                                                                     class="hide field-container form-group"><label
                                                                            id="IsCamionnette5Places-label"
                                                                            class="control-label"><span
                                                                                class="asterisk"> * </span>Votre
                                                                        véhicule comprend-il au moins cinq
                                                                        places assises (S1 = 5) ?<span class="asterisk"> * </span></label>
                                                                    <div class="input-group">
                                                                        <fieldset id="IsCamionnette5Places">
                                                                            <legend class="sr-only">Votre véhicule
                                                                                comprend-il au moins cinq
                                                                                places assises (S1 = 5) ?
                                                                            </legend>
                                                                            <label class="choice"
                                                                                   for="IsCamionnette5Places_1"><input
                                                                                        id="IsCamionnette5Places_1"
                                                                                        type="radio"
                                                                                        name="IsCamionnette5Places"
                                                                                        value="1" onclick="checkRadio_IsCamionnette5Places()"/>Oui</label>
                                                                            <label class="choice"
                                                                                   for="IsCamionnette5Places_2"><input
                                                                                        id="IsCamionnette5Places_2"
                                                                                        type="radio"
                                                                                        name="IsCamionnette5Places"
                                                                                        value="2" onclick="checkRadio_IsCamionnette5Places()"/>Non</label>
                                                                        </fieldset>
                                                                    </div>
                                                                    <button type="button"
                                                                            href="#help-IsCamionnette5Places"
                                                                            data-toggle="collapse"
                                                                            data-target="#help-IsCamionnette5Places"
                                                                            aria-controls="help-IsCamionnette5Places"
                                                                            class="btn btn-help collapsed"
                                                                            title="aide sur Votre véhicule comprend-il au moins cinq places assises (S1 = 5) ?">
                                                                        <span class="icon icon-help"
                                                                              aria-hidden="true"></span><span
                                                                                class="blank">Aide</span></button>
                                                                    <div id="IsCamionnette5Places-field-error"
                                                                         class="field-error"></div>
                                                                    <div class="collapse help-panel"
                                                                         id="help-IsCamionnette5Places">
                                                                        <dl>
                                                                            <dt>Votre véhicule comprend-il au moins cinq
                                                                                places assises (S1 = 5)
                                                                                ?
                                                                            </dt>
                                                                            <dd>
                                                                                <p>Cette information figure en rubrique
                                                                                    S1 sur la carte grise du
                                                                                    véhicule ou, dans le cas d’un
                                                                                    véhicule neuf, sur le
                                                                                    certificat de conformité remis par
                                                                                    le concessionnaire (S1 =
                                                                                    5).</p>
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                                <div id="IsCodeCarosseriePickUpBE-container"
                                                                     data-type="choice"
                                                                     data-expanded="true" data-field-position="3"
                                                                     class="hide field-container form-group"><label
                                                                            id="IsCodeCarosseriePickUpBE-label"
                                                                            class="control-label"><span
                                                                                class="asterisk"> * </span>Le code de
                                                                        carrosserie (CE) de votre véhicule
                                                                        est-il camion pick-up (J2 = BE) ?<span
                                                                                class="asterisk"> * </span></label>
                                                                    <div class="input-group">
                                                                        <fieldset id="IsCodeCarosseriePickUpBE">
                                                                            <legend class="sr-only">Le code de
                                                                                carrosserie (CE) de votre
                                                                                véhicule est-il camion pick-up (J2 = BE)
                                                                                ?
                                                                            </legend>
                                                                            <label class="choice"
                                                                                   for="IsCodeCarosseriePickUpBE_1"><input
                                                                                        id="IsCodeCarosseriePickUpBE_1"
                                                                                        type="radio"
                                                                                        name="IsCodeCarosseriePickUpBE"
                                                                                        value="1" onclick="checkRadio_IsCodeCarosseriePickUpBE()"/>Oui</label>
                                                                            <label class="choice"
                                                                                   for="IsCodeCarosseriePickUpBE_2"><input
                                                                                        id="IsCodeCarosseriePickUpBE_2"
                                                                                        type="radio"
                                                                                        name="IsCodeCarosseriePickUpBE"
                                                                                        value="2" onclick="checkRadio_IsCodeCarosseriePickUpBE()"/>Non</label>
                                                                        </fieldset>
                                                                    </div>
                                                                    <button type="button"
                                                                            href="#help-IsCodeCarosseriePickUpBE"
                                                                            data-toggle="collapse"
                                                                            data-target="#help-IsCodeCarosseriePickUpBE"
                                                                            aria-controls="help-IsCodeCarosseriePickUpBE"
                                                                            class="btn btn-help collapsed"
                                                                            title="aide sur Le code de carrosserie (CE)  de votre véhicule est-il camion pick-up (J2 = BE) ?">
                                                                        <span class="icon icon-help"
                                                                              aria-hidden="true"></span><span
                                                                                class="blank">Aide</span></button>
                                                                    <div id="IsCodeCarosseriePickUpBE-field-error"
                                                                         class="field-error"></div>
                                                                    <div class="collapse help-panel"
                                                                         id="help-IsCodeCarosseriePickUpBE">
                                                                        <dl>
                                                                            <dt>Le code de carrosserie (CE) de votre
                                                                                véhicule est-il camion
                                                                                pick-up (J2 = BE) ?
                                                                            </dt>
                                                                            <dd>
                                                                                <p>Ce code figure en rubrique J2 sur la
                                                                                    carte grise du véhicule
                                                                                    ou, dans le cas d’un véhicule neuf,
                                                                                    sur le certificat de
                                                                                    conformité remis par le
                                                                                    concessionnaire.</p>
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                                <div id="IsPickUpAffectationRemonteesMecEtDomainesSki-container"
                                                                     data-type="choice" data-expanded="true"
                                                                     data-field-position="4"
                                                                     class="hide field-container form-group"><label
                                                                            id="IsPickUpAffectationRemonteesMecEtDomainesSki-label"
                                                                            class="control-label"><span
                                                                                class="asterisk"> * </span>Ce véhicule
                                                                        est-il de type tout terrain et affecté
                                                                        exclusivement à l'exploitation des
                                                                        remontées mécaniques et des domaines skiables
                                                                        ?<span
                                                                                class="asterisk"> * </span></label>
                                                                    <div class="input-group">
                                                                        <fieldset
                                                                                id="IsPickUpAffectationRemonteesMecEtDomainesSki">
                                                                            <legend class="sr-only">Ce véhicule est-il
                                                                                de type tout terrain et
                                                                                affecté exclusivement à l'exploitation
                                                                                des remontées mécaniques
                                                                                et des domaines skiables ?
                                                                            </legend>
                                                                            <label class="choice"
                                                                                   for="IsPickUpAffectationRemonteesMecEtDomainesSki_1"><input
                                                                                        id="IsPickUpAffectationRemonteesMecEtDomainesSki_1"
                                                                                        type="radio"
                                                                                        name="IsPickUpAffectationRemonteesMecEtDomainesSki"
                                                                                        value="1" onclick="checkRadio_IsPickUpAffectationRemonteesMecEtDomainesSki()"/>Oui</label>
                                                                            <label class="choice"
                                                                                   for="IsPickUpAffectationRemonteesMecEtDomainesSki_2"><input
                                                                                        id="IsPickUpAffectationRemonteesMecEtDomainesSki_2"
                                                                                        type="radio"
                                                                                        name="IsPickUpAffectationRemonteesMecEtDomainesSki"
                                                                                        value="2" onclick="checkRadio_IsPickUpAffectationRemonteesMecEtDomainesSki()"/>Non</label>
                                                                        </fieldset>
                                                                    </div>
                                                                    <button type="button"
                                                                            href="#help-IsPickUpAffectationRemonteesMecEtDomainesSki"
                                                                            data-toggle="collapse"
                                                                            data-target="#help-IsPickUpAffectationRemonteesMecEtDomainesSki"
                                                                            aria-controls="help-IsPickUpAffectationRemonteesMecEtDomainesSki"
                                                                            class="btn btn-help collapsed"
                                                                            title="aide sur Ce véhicule est-il de type tout terrain et affecté exclusivement à l'exploitation des remontées mécaniques et des domaines skiables  ?">
                                                                        <span class="icon icon-help"
                                                                              aria-hidden="true"></span><span
                                                                                class="blank">Aide</span></button>
                                                                    <div id="IsPickUpAffectationRemonteesMecEtDomainesSki-field-error"
                                                                         class="field-error"></div>
                                                                    <div class="collapse help-panel"
                                                                         id="help-IsPickUpAffectationRemonteesMecEtDomainesSki">
                                                                        <dl>
                                                                            <dt>Ce véhicule est-il de type tout terrain
                                                                                et affecté exclusivement
                                                                                à l'exploitation des remontées
                                                                                mécaniques et des domaines
                                                                                skiables ?
                                                                            </dt>
                                                                            <dd>
                                                                                <p>Le certificat d’immatriculation est
                                                                                    établi au nom d’un
                                                                                    exploitant de remontées mécaniques,
                                                                                    le véhicule est affecté
                                                                                    exclusivement à l'exploitation de
                                                                                    ces infrastructures ; le
                                                                                    véhicule comprend, d'origine ou à la
                                                                                    suite de travaux, trois
                                                                                    au moins des équipements techniques
                                                                                    suivants : plateau de
                                                                                    chargement, arceau de sécurité pour
                                                                                    habitacle, portique de
                                                                                    levage, crochet d'attelage, treuil
                                                                                    frontal, bac de benne,
                                                                                    blocage de différentiel, boîte de
                                                                                    transfert, arceau
                                                                                    porte-échelle arrière de cabine,
                                                                                    plusieurs points d'arrimage
                                                                                    sur les côtés des ridelles, pneus
                                                                                    mixtes. Vous devrez
                                                                                    certifier sur l'honneur que votre
                                                                                    véhicule répond à ces
                                                                                    conditions.</p>
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                                <div id="Vehicule_N1_Transport_voyageurs-container"
                                                                     data-type="choice"
                                                                     data-expanded="true" data-field-position="5"
                                                                     class="hide field-container form-group"><label
                                                                            id="Vehicule_N1_Transport_voyageurs-label"
                                                                            class="control-label"><span
                                                                                class="asterisk"> * </span>Le véhicule
                                                                        est-il destiné au tranport de
                                                                        voyageurs et de leurs bagages ?<span
                                                                                class="asterisk"> * </span></label>
                                                                    <div class="input-group">
                                                                        <fieldset id="Vehicule_N1_Transport_voyageurs">
                                                                            <legend class="sr-only">Le véhicule est-il
                                                                                destiné au tranport de
                                                                                voyageurs et de leurs bagages ?
                                                                            </legend>
                                                                            <label class="choice"
                                                                                   for="Vehicule_N1_Transport_voyageurs_1"><input
                                                                                        id="Vehicule_N1_Transport_voyageurs_1"
                                                                                        type="radio"
                                                                                        name="Vehicule_N1_Transport_voyageurs"
                                                                                        value="Oui" onclick="checkRadio_Vehicule_N1_Transport_voyageurs()"/>Oui</label>
                                                                            <label class="choice"
                                                                                   for="Vehicule_N1_Transport_voyageurs_2"><input
                                                                                        id="Vehicule_N1_Transport_voyageurs_2"
                                                                                        type="radio"
                                                                                        name="Vehicule_N1_Transport_voyageurs"
                                                                                        value="Non" onclick="checkRadio_Vehicule_N1_Transport_voyageurs()"/>Non</label>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div id="Vehicule_N1_Transport_voyageurs-field-error"
                                                                         class="field-error"></div>
                                                                </div>
                                                                <div id="declareVehiculeDemonstration-container"
                                                                     data-type="choice"
                                                                     data-expanded="true" data-field-position="6"
                                                                     class="hide field-container form-group new-line"><label
                                                                            id="declareVehiculeDemonstration-label"
                                                                            class="control-label"><span
                                                                                class="asterisk"> * </span>Véhicule de
                                                                        démonstration ?<span
                                                                                class="asterisk"> * </span></label>
                                                                    <div class="input-group">
                                                                        <fieldset id="declareVehiculeDemonstration">
                                                                            <legend class="sr-only">Véhicule de
                                                                                démonstration ?
                                                                            </legend>
                                                                            <label class="choice"
                                                                                   for="declareVehiculeDemonstration_1"><input
                                                                                        id="declareVehiculeDemonstration_1"
                                                                                        type="radio"
                                                                                        name="declareVehiculeDemonstration"
                                                                                        value="1" onclick="checkRadio_declareVehiculeDemonstration()"/>Oui</label>
                                                                            <label class="choice checked"
                                                                                   for="declareVehiculeDemonstration_2"><input
                                                                                        id="declareVehiculeDemonstration_2"
                                                                                        type="radio"
                                                                                        name="declareVehiculeDemonstration"
                                                                                        value="2"
                                                                                        checked="checked" onclick="checkRadio_declareVehiculeDemonstration()"/>Non</label>
                                                                        </fieldset>
                                                                    </div>
                                                                    <div id="declareVehiculeDemonstration-field-error"
                                                                         class="field-error"></div>
                                                                </div>
                                                                <div id="dateMiseEnCirculation-container"
                                                                     data-type="date"
                                                                     data-field-position="7"
                                                                     class="hide field-container form-group new-line">
                                                                    <div class="pre-note"><br>
                                                                    </div>
                                                                    <label id="dateMiseEnCirculation-label"
                                                                           class="control-label"
                                                                           for="dateMiseEnCirculation"><span
                                                                                class="asterisk"> * </span>Date de
                                                                        mise en circulation (B) (format : JJ/MM/AAAA)
                                                                        :<span
                                                                                class="asterisk"> * </span></label>
                                                                    <div class="input-group"><input type="text"
                                                                                                    aria-describedby="dateMiseEnCirculation-aria-description"
                                                                                                    id="dateMiseEnCirculation"
                                                                                                    name="dateMiseEnCirculation"
                                                                                                    data-widget="abDatepicker"
                                                                                                    value="15/01/2021"
                                                                                                    class="date form-control"
                                                                                                    placeholder="JJ/MM/AAAA"
                                                                                                    autocomplete="off"/>
                                                                        <span id="dateMiseEnCirculation-aria-description"
                                                                              class="sr-only">Saisir la date sous la forme JJ/MM/AAAA ou tabuler pour activer le sélecteur de date à droite de ce champ de saisie</span>
                                                                    </div>
                                                                    <button type="button"
                                                                            href="#help-dateMiseEnCirculation"
                                                                            data-toggle="collapse"
                                                                            data-target="#help-dateMiseEnCirculation"
                                                                            aria-controls="help-dateMiseEnCirculation"
                                                                            class="btn btn-help collapsed"
                                                                            title="aide sur Date de mise en circulation (B) (format : JJ/MM/AAAA) :">
                                                                        <span class="icon icon-help"
                                                                              aria-hidden="true"></span><span
                                                                                class="blank">Aide</span></button>
                                                                    <div id="dateMiseEnCirculation-field-error"
                                                                         class="field-error"></div>
                                                                    <div class="collapse help-panel"
                                                                         id="help-dateMiseEnCirculation">
                                                                        <dl>
                                                                            <dt>Date de mise en circulation (B) (format
                                                                                : JJ/MM/AAAA) :
                                                                            </dt>
                                                                            <dd>
                                                                                <p>L'ancienneté du véhicule se calcule à
                                                                                    partir de la date de
                                                                                    1ère mise en circulation. Les
                                                                                    véhicules de plus de 10 ans
                                                                                    d'âge bénéficient pour certaines
                                                                                    opérations d'une réduction
                                                                                    du taux du cheval fiscal (CV).</p>
                                                                                <br>
                                                                                <p>Cas d'un véhicule importé : en cas de
                                                                                    1ère immatriculation en
                                                                                    France d'une voiture particulière
                                                                                    (VP) déjà immatriculée à
                                                                                    l'étranger, la date de 1ère
                                                                                    immatriculation à l'étranger
                                                                                    doit être renseignée. Elle ne
                                                                                    joue</p>
                                                                                <p>pas sur le taux du CV mais peut
                                                                                    donner lieu à une réduction
                                                                                    du malus C0².</p>
                                                                                <p>Cas du duplicata : s'il vous est
                                                                                    demandé de saisir une date
                                                                                    de mise en circulation mais que vous
                                                                                    avez perdu la carte
                                                                                    grise et ne disposez pas d'une
                                                                                    photocopie, sachez que la
                                                                                    date ne joue que si le véhicule a
                                                                                    plus de 10 ans, peu de
                                                                                    chevaux fiscaux et bénéficie
                                                                                    d'abattements. Vous pouvez
                                                                                    saisir la date approximative.</p>
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                                <div id="puissanceAdministrative-container"
                                                                     data-type="number"
                                                                     data-field-position="8"
                                                                     class="hide field-container form-group new-line"><label
                                                                            id="puissanceAdministrative-label"
                                                                            class="control-label"
                                                                            for="puissanceAdministrative"><span
                                                                                class="asterisk"> * </span>Puissance
                                                                        administrative nationale (P.6)<span
                                                                                class="asterisk"> * </span></label>
                                                                    <div class="input-group"><input type="number"
                                                                                                    id="puissanceAdministrative"
                                                                                                    name="puissanceAdministrative"
                                                                                                    value=""
                                                                                                    title="format: chiffres seulement"
                                                                                                    autocomplete="off"/>
                                                                    </div>
                                                                    <span class="unit"> CV</span>
                                                                    <button type="button"
                                                                            href="#help-puissanceAdministrative"
                                                                            data-toggle="collapse"
                                                                            data-target="#help-puissanceAdministrative"
                                                                            aria-controls="help-puissanceAdministrative"
                                                                            class="btn btn-help collapsed"
                                                                            title="aide sur Puissance administrative nationale (P.6)"><span
                                                                                class="icon icon-help"
                                                                                aria-hidden="true"></span><span
                                                                                class="blank">Aide</span></button>
                                                                    <div id="puissanceAdministrative-field-error"
                                                                         class="field-error"></div>
                                                                    <div class="collapse help-panel"
                                                                         id="help-puissanceAdministrative">
                                                                        <dl>
                                                                            <dt>Puissance administrative nationale
                                                                                (P.6)
                                                                            </dt>
                                                                            <dd>
                                                                                <p>La puissance administrative
                                                                                    nationale, appelée aussi
                                                                                    puissance fiscale (en chevaux
                                                                                    vapeur) est mentionnée à la
                                                                                    rubrique 'P.6' de la carte
                                                                                    grise.</p>
                                                                                <p>La puissance administrative est
                                                                                    utilisée pour le calcul de la
                                                                                    taxe CO2 ou du malus écologique si
                                                                                    votre véhicule n’a pas
                                                                                    fait l’objet d’une réception
                                                                                    européenne.</p>
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                                <div id="declareVehiculeDeCollection-container"
                                                                     data-type="choice"
                                                                     data-expanded="true" data-field-position="9"
                                                                     class="hide field-container form-group new-line"><label
                                                                            id="declareVehiculeDeCollection-label"
                                                                            class="control-label"><span
                                                                                class="asterisk"> * </span>Véhicule de
                                                                        collection ?<span
                                                                                class="asterisk"> * </span></label>
                                                                    <div class="input-group">
                                                                        <fieldset id="declareVehiculeDeCollection">
                                                                            <legend class="sr-only">Véhicule de
                                                                                collection ?
                                                                            </legend>
                                                                            <label class="choice"
                                                                                   for="declareVehiculeDeCollection_1"><input
                                                                                        id="declareVehiculeDeCollection_1"
                                                                                        type="radio"
                                                                                        name="declareVehiculeDeCollection"
                                                                                        value="1"/>Oui</label>
                                                                            <label class="choice checked"
                                                                                   for="declareVehiculeDeCollection_2"><input
                                                                                        id="declareVehiculeDeCollection_2"
                                                                                        type="radio"
                                                                                        name="declareVehiculeDeCollection"
                                                                                        value="2"
                                                                                        checked="checked"/>Non</label>
                                                                        </fieldset>
                                                                    </div>
                                                                    <button type="button"
                                                                            href="#help-declareVehiculeDeCollection"
                                                                            data-toggle="collapse"
                                                                            data-target="#help-declareVehiculeDeCollection"
                                                                            aria-controls="help-declareVehiculeDeCollection"
                                                                            class="btn btn-help collapsed"
                                                                            title="aide sur Véhicule de collection ?"><span
                                                                                class="icon icon-help"
                                                                                aria-hidden="true"></span><span
                                                                                class="blank">Aide</span></button>
                                                                    <div id="declareVehiculeDeCollection-field-error"
                                                                         class="field-error"></div>
                                                                    <div class="collapse help-panel"
                                                                         id="help-declareVehiculeDeCollection">
                                                                        <dl>
                                                                            <dt>Véhicule de collection ?</dt>
                                                                            <dd>
                                                                                <p>Les véhicules de plus de 30 ans, à
                                                                                    moteur ou remorqués qui ne
                                                                                    peuvent faire pas l’objet d’une
                                                                                    réception nationale peuvent
                                                                                    être immatriculés avec un usage «
                                                                                    véhicule de collection
                                                                                    ».</p>
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                                <div id="energie-container" data-type="choice"
                                                                     data-field-position="10"
                                                                     class="hide field-container form-group new-line"><label
                                                                            id="energie-label"
                                                                            class="control-label"
                                                                            for="energie"><span
                                                                                class="asterisk"> * </span>Energie (P.3)<span
                                                                                class="asterisk"> * </span></label>
                                                                    <div class="input-group"><select
                                                                                class="form-control" id="energie"
                                                                                name="energie" data-widget="abListbox">
                                                                            <option id="energie_none" value=""></option>
                                                                            <option id="energie_1" value="1">Essence
                                                                                (ES)
                                                                            </option>
                                                                            <option id="energie_2" value="2">Gazole
                                                                                (GO)
                                                                            </option>
                                                                            <option id="energie_3" value="3">Véhicule
                                                                                hybride fonctionnant à
                                                                                l'essence/électricité ou
                                                                                gazole/électricité (EE, EH, GL, GH)
                                                                            </option>
                                                                            <option id="energie_4" value="4">Véhicule
                                                                                fonctionnant exclusivement ou
                                                                                non au GPL (GP, EG, ER, EQ, G2, PE, PH)
                                                                            </option>
                                                                            <option id="energie_5" value="5">Véhicule
                                                                                fonctionnant exclusivement au
                                                                                superéthanol E85 (FE)
                                                                            </option>
                                                                            <option id="energie_6" value="6">Véhicule
                                                                                hybride fonctionnant avec du
                                                                                superéthanol (FG, FN, FL,FH)
                                                                            </option>
                                                                            <option id="energie_7" value="7">Véhicule
                                                                                fonctionnant exclusivement ou
                                                                                non au gaz naturel (GN, EN, GF, 1A, EM,
                                                                                EP, GM, GQ, NE, NH)
                                                                            </option>
                                                                            <option id="energie_8" value="8">Véhicule
                                                                                fonctionnant à l'électricité
                                                                                (EL), à l'hydrogène (H2) ou combinant
                                                                                les 2 (HE, HH)
                                                                            </option>
                                                                            <option id="energie_9" value="9">Ethanol
                                                                                (ET)
                                                                            </option>
                                                                            <option id="energie_10" value="10">Gazogène
                                                                                (GA)
                                                                            </option>
                                                                            <option id="energie_11" value="11">Autres
                                                                                hydrocarbures gazeux comprimés
                                                                                (GZ)
                                                                            </option>
                                                                            <option id="energie_12" value="12">Mélange
                                                                                gazogène-gazole (GG)
                                                                            </option>
                                                                            <option id="energie_13" value="13">Mélange
                                                                                gazogène-essence (GE)
                                                                            </option>
                                                                            <option id="energie_14" value="14">Pétrole
                                                                                lampant (PL)
                                                                            </option>
                                                                            <option id="energie_15" value="15">Air
                                                                                comprimé (AC)
                                                                            </option>
                                                                        </select>
                                                                    </div>
                                                                    <button type="button" href="#help-energie"
                                                                            data-toggle="collapse"
                                                                            data-target="#help-energie"
                                                                            aria-controls="help-energie"
                                                                            class="btn btn-help collapsed"
                                                                            title="aide sur Energie (P.3)"><span
                                                                                class="icon icon-help"
                                                                                aria-hidden="true"></span><span
                                                                                class="blank">Aide</span></button>
                                                                    <div id="energie-field-error"
                                                                         class="field-error"></div>
                                                                    <div class="collapse help-panel" id="help-energie">
                                                                        <dl>
                                                                            <dt>Energie (P.3)</dt>
                                                                            <dd>
                                                                                <p>Le type de carburant ou la source
                                                                                    d'énergie utilisé par le
                                                                                    véhicule est mentionné à la rubrique
                                                                                    'P.3' de la carte
                                                                                    grise.</p><br>
                                                                                <p>Le coût du certificat
                                                                                    d’immatriculation inclut notamment
                                                                                    une
                                                                                    taxe destinée à la région dans
                                                                                    laquelle se situe le domicile
                                                                                    du propriétaire du véhicule. Le
                                                                                    conseil régional ou
                                                                                    l’assemblée de Corse peuvent prévoir
                                                                                    une exonération totale
                                                                                    ou partielle (50 %) pour les
                                                                                    véhicules fonctionnant
                                                                                    exclusivement ou non au moyen du
                                                                                    GPL, du superéthanol, du
                                                                                    gaz naturel.</p><br>
                                                                                <p><strong>À noter :</strong> les
                                                                                    véhicules propres fonctionnant
                                                                                    à l'électricité, à l'hydrogène ou en
                                                                                    combinant ces 2
                                                                                    énergies sont maintenant exonérés
                                                                                    totalement de cette taxe.
                                                                                </p>
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                                <div id="invalidite-container" data-type="choice"
                                                                     data-expanded="true"
                                                                     data-field-position="11"
                                                                     class="hide field-container form-group new-line"><label
                                                                            id="invalidite-label" class="control-label"><span
                                                                                class="asterisk"> * </span>Carrosserie
                                                                        handicap / Carte
                                                                        d'invalidité<span
                                                                                class="asterisk"> * </span></label>
                                                                    <div class="input-group">
                                                                        <fieldset id="invalidite">
                                                                            <legend class="sr-only">Carrosserie handicap
                                                                                / Carte d'invalidité
                                                                            </legend>
                                                                            <label class="choice"
                                                                                   for="invalidite_1"><input
                                                                                        id="invalidite_1"
                                                                                        type="radio"
                                                                                        name="invalidite"
                                                                                        value="1" onclick="checkRadio_invalidite()"/>Oui</label>
                                                                            <label class="choice checked"
                                                                                   for="invalidite_2"><input
                                                                                        id="invalidite_2" type="radio"
                                                                                        name="invalidite" value="2"
                                                                                        checked="checked" onclick="checkRadio_invalidite()"/>Non</label>
                                                                        </fieldset>
                                                                    </div>
                                                                    <button type="button" href="#help-invalidite"
                                                                            data-toggle="collapse"
                                                                            data-target="#help-invalidite"
                                                                            aria-controls="help-invalidite"
                                                                            class="btn btn-help collapsed"
                                                                            title="aide sur Carrosserie handicap / Carte d'invalidité"><span
                                                                                class="icon icon-help"
                                                                                aria-hidden="true"></span><span
                                                                                class="blank">Aide</span></button>
                                                                    <div id="invalidite-field-error"
                                                                         class="field-error"></div>
                                                                    <div class="collapse help-panel"
                                                                         id="help-invalidite">
                                                                        <dl>
                                                                            <dt>Carrosserie handicap / Carte
                                                                                d'invalidité
                                                                            </dt>
                                                                            <dd>
                                                                                <p>Les voitures particulières :</p>
                                                                                <p>- à carrosserie " Handicap "</p>
                                                                                <p>- ou acquises par une personne
                                                                                    titulaire de la carte
                                                                                    d'invalidité ou "mobilité inclusion"
                                                                                    portant la mention
                                                                                    invalidité</p>
                                                                                <p>- ou acquises par le parent d’un
                                                                                    enfant mineur ou à charge,
                                                                                    et du même foyer fiscal, lui-même
                                                                                    titulaire d'une des cartes
                                                                                    citées plus haut</p>
                                                                                <p>sont exonérés du malus écologique et
                                                                                    de la taxe additionnelle
                                                                                    sur les véhicules les plus
                                                                                    polluants.</p>
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                                <div id="receptionCommunautaire-container"
                                                                     data-type="choice"
                                                                     data-expanded="true" data-field-position="12"
                                                                     class="hide field-container form-group new-line"><label
                                                                            id="receptionCommunautaire-label"
                                                                            class="control-label"><span
                                                                                class="asterisk"> * </span>Le véhicule a
                                                                        fait l'objet d'une réception
                                                                        communautaire ?<span class="asterisk"> * </span></label>
                                                                    <div class="input-group">
                                                                        <fieldset id="receptionCommunautaire">
                                                                            <legend class="sr-only">Le véhicule a fait
                                                                                l'objet d'une réception
                                                                                communautaire ?
                                                                            </legend>
                                                                            <label class="choice checked"
                                                                                   for="receptionCommunautaire_1"><input
                                                                                        id="receptionCommunautaire_1"
                                                                                        type="radio"
                                                                                        name="receptionCommunautaire"
                                                                                        value="1" checked="checked" onclick="checkRadio_receptionCommunautaire()"/>Oui</label>
                                                                            <label class="choice"
                                                                                   for="receptionCommunautaire_2"><input
                                                                                        id="receptionCommunautaire_2"
                                                                                        type="radio"
                                                                                        name="receptionCommunautaire"
                                                                                        value="2" onclick="checkRadio_receptionCommunautaire()"/>Non</label>
                                                                        </fieldset>
                                                                    </div>
                                                                    <button type="button"
                                                                            href="#help-receptionCommunautaire"
                                                                            data-toggle="collapse"
                                                                            data-target="#help-receptionCommunautaire"
                                                                            aria-controls="help-receptionCommunautaire"
                                                                            class="btn btn-help collapsed"
                                                                            title="aide sur Le véhicule a fait l'objet d'une réception communautaire ?">
                                                                        <span class="icon icon-help"
                                                                              aria-hidden="true"></span><span
                                                                                class="blank">Aide</span></button>
                                                                    <div id="receptionCommunautaire-field-error"
                                                                         class="field-error"></div>
                                                                    <div class="collapse help-panel"
                                                                         id="help-receptionCommunautaire">
                                                                        <dl>
                                                                            <dt>Le véhicule a fait l'objet d'une
                                                                                réception communautaire ?
                                                                            </dt>
                                                                            <dd>
                                                                                <p>La réception européenne (ex
                                                                                    communautaire) d'un véhicule est
                                                                                    l’acte par lequel un Etat atteste de
                                                                                    la conformité du
                                                                                    véhicule aux réglementations
                                                                                    concernant les exigences
                                                                                    techniques applicables pour la
                                                                                    sécurité et les émissions de
                                                                                    véhicules.</p>
                                                                                <p>Cette opération peut être accordée
                                                                                    :</p>
                                                                                <p>- par type, sur la base d’un
                                                                                    prototype représentatif d’un
                                                                                    véhicule produit en série par un
                                                                                    constructeur (véhicules
                                                                                    neufs uniquement),</p>
                                                                                <p>- à titre individuel (ou à titre
                                                                                    isolé) à un aménageur, à un
                                                                                    constructeur ou à un particulier,
                                                                                    pour un véhicule donné
                                                                                    (neuf, transformé, importé ou démuni
                                                                                    de certificat
                                                                                    d’immatriculation).</p>
                                                                                <p>Le champ 'K' de la carte grise
                                                                                    correspond au numéro de
                                                                                    réception par type si celui-ci est
                                                                                    disponible. Le champ
                                                                                    'D.2.1' correspond au code national
                                                                                    d’identification du type
                                                                                    (en cas de réception
                                                                                    européenne).</p>
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>
                                                                <div id="tauxCO2-container" data-type="number"
                                                                     data-field-position="13"
                                                                     class="hide field-container form-group new-line"><label
                                                                            id="tauxCO2-label"
                                                                            class="control-label"
                                                                            for="tauxCO2"><span
                                                                                class="asterisk"> * </span>Taux
                                                                        d'émission CO2 (V.7)<span
                                                                                class="asterisk"> * </span></label>
                                                                    <div class="input-group"><input type="number"
                                                                                                    id="tauxCO2"
                                                                                                    name="tauxCO2"
                                                                                                    value=""
                                                                                                    title="format: chiffres seulement"
                                                                                                    autocomplete="off"/>
                                                                    </div>
                                                                    <span class="unit"> g/km</span>
                                                                    <button type="button" href="#help-tauxCO2"
                                                                            data-toggle="collapse"
                                                                            data-target="#help-tauxCO2"
                                                                            aria-controls="help-tauxCO2"
                                                                            class="btn btn-help collapsed"
                                                                            title="aide sur Taux d'émission CO2 (V.7)"><span
                                                                                class="icon icon-help"
                                                                                aria-hidden="true"></span><span
                                                                                class="blank">Aide</span></button>
                                                                    <div id="tauxCO2-field-error"
                                                                         class="field-error"></div>
                                                                    <div class="post-note"><p><b>Attention </b>: si le
                                                                            véhicule n'est pas neuf,
                                                                            indiquez la valeur au jour de la première
                                                                            immatriculation (norme NEDC
                                                                            avant le 1/03/2020).</p>
                                                                    </div>
                                                                    <div class="collapse help-panel" id="help-tauxCO2">
                                                                        <dl>
                                                                            <dt>Taux d'émission CO2 (V.7)</dt>
                                                                            <dd>

                                                                                <p>Le taux d'émission en CO² est
                                                                                    mentionné à la rubrique 'V.7'
                                                                                    de la carte grise. Il est utilisé
                                                                                    pour le calcul de la taxe
                                                                                    CO² ou du malus écologique si votre
                                                                                    véhicule a fait l’objet
                                                                                    d’une réception européenne.</p>

                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>


                                                            </fieldset>
                                                            <fieldset
                                                                    class="hide fieldset-container disposition-classic form-horizontal"
                                                                    id="donnees-panel-1-fieldset-3">
                                                                <legend>
                                                                    Votre lieu de domicile
                                                                </legend>

                                                                <div id="departement-container" data-type="department"
                                                                     data-field-position="1"
                                                                     class="hide field-container form-group underlabel">
                                                                    <label id="departement-label"
                                                                           class="control-label"
                                                                           for="departement"><span
                                                                                class="asterisk"> * </span>Sélectionnez
                                                                        votre département<span
                                                                                class="asterisk"> * </span></label>
                                                                    <div class="input-group"><select id="departement"
                                                                                                     name="departement"
                                                                                                     data-widget="abListbox"
                                                                                                     class="form-control">
                                                                            <option value=""
                                                                                    selected="selected"></option>
                                                                            <option value="01">01 - Ain</option>
                                                                            <option value="02">02 - Aisne</option>
                                                                            <option value="03">03 - Allier</option>
                                                                            <option value="04">04 -
                                                                                Alpes-de-Haute-Provence
                                                                            </option>
                                                                            <option value="05">05 - Hautes-Alpes
                                                                            </option>
                                                                            <option value="06">06 - Alpes-Maritimes
                                                                            </option>
                                                                            <option value="07">07 - Ardèche</option>
                                                                            <option value="08">08 - Ardennes</option>
                                                                            <option value="09">09 - Ariège</option>
                                                                            <option value="10">10 - Aube</option>
                                                                            <option value="11">11 - Aude</option>
                                                                            <option value="12">12 - Aveyron</option>
                                                                            <option value="13">13 - Bouches-du-Rhône
                                                                            </option>
                                                                            <option value="14">14 - Calvados</option>
                                                                            <option value="15">15 - Cantal</option>
                                                                            <option value="16">16 - Charente</option>
                                                                            <option value="17">17 - Charente-Maritime
                                                                            </option>
                                                                            <option value="18">18 - Cher</option>
                                                                            <option value="19">19 - Corrèze</option>
                                                                            <option value="21">21 - Côte-d&#039;Or
                                                                            </option>
                                                                            <option value="22">22 - Côtes-d&#039;Armor
                                                                            </option>
                                                                            <option value="23">23 - Creuse</option>
                                                                            <option value="24">24 - Dordogne</option>
                                                                            <option value="25">25 - Doubs</option>
                                                                            <option value="26">26 - Drôme</option>
                                                                            <option value="27">27 - Eure</option>
                                                                            <option value="28">28 - Eure-et-Loir
                                                                            </option>
                                                                            <option value="29">29 - Finistère</option>
                                                                            <option value="2A">2A - Corse-du-Sud
                                                                            </option>
                                                                            <option value="2B">2B - Haute-Corse</option>
                                                                            <option value="30">30 - Gard</option>
                                                                            <option value="31">31 - Haute-Garonne
                                                                            </option>
                                                                            <option value="32">32 - Gers</option>
                                                                            <option value="33">33 - Gironde</option>
                                                                            <option value="34">34 - Hérault</option>
                                                                            <option value="35">35 - Ille-et-Vilaine
                                                                            </option>
                                                                            <option value="36">36 - Indre</option>
                                                                            <option value="37">37 - Indre-et-Loire
                                                                            </option>
                                                                            <option value="38">38 - Isère</option>
                                                                            <option value="39">39 - Jura</option>
                                                                            <option value="40">40 - Landes</option>
                                                                            <option value="41">41 - Loir-et-Cher
                                                                            </option>
                                                                            <option value="42">42 - Loire</option>
                                                                            <option value="43">43 - Haute-Loire</option>
                                                                            <option value="44">44 - Loire-Atlantique
                                                                            </option>
                                                                            <option value="45">45 - Loiret</option>
                                                                            <option value="46">46 - Lot</option>
                                                                            <option value="47">47 - Lot-et-Garonne
                                                                            </option>
                                                                            <option value="48">48 - Lozère</option>
                                                                            <option value="49">49 - Maine-et-Loire
                                                                            </option>
                                                                            <option value="50">50 - Manche</option>
                                                                            <option value="51">51 - Marne</option>
                                                                            <option value="52">52 - Haute-Marne</option>
                                                                            <option value="53">53 - Mayenne</option>
                                                                            <option value="54">54 - Meurthe-et-Moselle
                                                                            </option>
                                                                            <option value="55">55 - Meuse</option>
                                                                            <option value="56">56 - Morbihan</option>
                                                                            <option value="57">57 - Moselle</option>
                                                                            <option value="58">58 - Nièvre</option>
                                                                            <option value="59">59 - Nord</option>
                                                                            <option value="60">60 - Oise</option>
                                                                            <option value="61">61 - Orne</option>
                                                                            <option value="62">62 - Pas-de-Calais
                                                                            </option>
                                                                            <option value="63">63 - Puy-de-Dôme</option>
                                                                            <option value="64">64 -
                                                                                Pyrénées-Atlantiques
                                                                            </option>
                                                                            <option value="65">65 - Hautes-Pyrénées
                                                                            </option>
                                                                            <option value="66">66 -
                                                                                Pyrénées-Orientales
                                                                            </option>
                                                                            <option value="67">67 - Bas-Rhin</option>
                                                                            <option value="68">68 - Haut-Rhin</option>
                                                                            <option value="69">69 - Rhône</option>
                                                                            <option value="70">70 - Haute-Saône</option>
                                                                            <option value="71">71 - Saône-et-Loire
                                                                            </option>
                                                                            <option value="72">72 - Sarthe</option>
                                                                            <option value="73">73 - Savoie</option>
                                                                            <option value="74">74 - Haute-Savoie
                                                                            </option>
                                                                            <option value="75">75 - Paris</option>
                                                                            <option value="76">76 - Seine-Maritime
                                                                            </option>
                                                                            <option value="77">77 - Seine-et-Marne
                                                                            </option>
                                                                            <option value="78">78 - Yvelines</option>
                                                                            <option value="79">79 - Deux-Sèvres</option>
                                                                            <option value="80">80 - Somme</option>
                                                                            <option value="81">81 - Tarn</option>
                                                                            <option value="82">82 - Tarn-et-Garonne
                                                                            </option>
                                                                            <option value="83">83 - Var</option>
                                                                            <option value="84">84 - Vaucluse</option>
                                                                            <option value="85">85 - Vendée</option>
                                                                            <option value="86">86 - Vienne</option>
                                                                            <option value="87">87 - Haute-Vienne
                                                                            </option>
                                                                            <option value="88">88 - Vosges</option>
                                                                            <option value="89">89 - Yonne</option>
                                                                            <option value="90">90 - Territoire de
                                                                                Belfort
                                                                            </option>
                                                                            <option value="91">91 - Essonne</option>
                                                                            <option value="92">92 - Hauts-de-Seine
                                                                            </option>
                                                                            <option value="93">93 - Seine-Saint-Denis
                                                                            </option>
                                                                            <option value="94">94 - Val-de-Marne
                                                                            </option>
                                                                            <option value="95">95 - Val-d&#039;Oise
                                                                            </option>
                                                                            <option value="971">971 - Guadeloupe
                                                                            </option>
                                                                            <option value="972">972 - Martinique
                                                                            </option>
                                                                            <option value="973">973 - Guyane</option>
                                                                            <option value="974">974 - La Réunion
                                                                            </option>
                                                                            <option value="976">976 - Mayotte</option>

                                                                        </select>
                                                                    </div>
                                                                    <button type="button" href="#help-departement"
                                                                            data-toggle="collapse"
                                                                            data-target="#help-departement"
                                                                            aria-controls="help-departement"
                                                                            class="btn btn-help collapsed"
                                                                            title="aide sur Sélectionnez votre département"><span
                                                                                class="icon icon-help"
                                                                                aria-hidden="true"></span><span
                                                                                class="blank">Aide</span></button>
                                                                    <div id="departement-field-error"
                                                                         class="field-error"></div>
                                                                    <div class="collapse help-panel"
                                                                         id="help-departement">
                                                                        <dl>
                                                                            <dt>Sélectionnez votre département</dt>
                                                                            <dd>
                                                                                <p>Le coût du certificat
                                                                                    d’immatriculation inclut notamment
                                                                                    une
                                                                                    taxe destinée à la région dans
                                                                                    laquelle se situe le domicile
                                                                                    du propriétaire du véhicule et
                                                                                    calculée à partir d’un taux
                                                                                    unitaire du cheval vapeur voté par
                                                                                    le conseil régional ou
                                                                                    l’assemblée de Corse. Ces assemblées
                                                                                    peuvent prévoir une
                                                                                    exonération totale ou partielle (50
                                                                                    %) pour les véhicules
                                                                                    fonctionnant exclusivement ou non au
                                                                                    moyen de certaines
                                                                                    énergie plus “propres”.</p>
                                                                                <p><strong>Si vous accomplissez la
                                                                                        démarche pour le compte d’une
                                                                                        entreprise ou d’une
                                                                                        association</strong>, vous devez
                                                                                    indiquer le département où se situe
                                                                                    l'établissement auquel
                                                                                    le véhicule est affecté à titre
                                                                                    principal.</p>
                                                                                <p><strong>Si vous accomplissez la
                                                                                        démarche pour le compte d’une
                                                                                        entreprise de location de
                                                                                        véhicules</strong>, vous devez
                                                                                    indiquer le département où se situe
                                                                                    l'établissement qui
                                                                                    procède à la 1ère location du
                                                                                    véhicule.</p>
                                                                            </dd>
                                                                        </dl>
                                                                    </div>
                                                                </div>


                                                            </fieldset>
                                                        </div>
                                                        <div id="global-error" class="hide"
                                                             aria-live="assertive"></div>


                                                        <div class="action_buttons button bottom">
                                                            <button class="btn btn-primary" type="submit"
                                                                    name="calculer">Calculer
                                                            </button>
                                                            <button class="btn btn-default" type="reset" name="effacer">
                                                                Effacer formulaire
                                                            </button>
                                                        </div>


                                                        <input type="hidden" name="typeImmatriculation" value=""/>
                                                        <input type="hidden" name="neufOuMoinsDe10" value=""/>
                                                        <input type="hidden" name="moisEntames" value=""/>
                                                        <input type="hidden" name="anneesEntamees" value=""/>
                                                        <input type="hidden" name="moisDateMiseEnCirculation" value=""/>
                                                        <input type="hidden" name="jourDateMiseEnCirculation" value=""/>
                                                        <input type="hidden" name="baremeDemarcheAutre" value=""/>
                                                        <input type="hidden" name="baremeDemarche" value="0,000"/>
                                                        <input type="hidden" name="baremeVehiculeMoins10Ans" value=""/>
                                                        <input type="hidden" name="baremeVehiculePlus10Ans" value=""/>
                                                        <input type="hidden" name="baremeVehicule" value="0,000"/>
                                                        <input type="hidden" name="taxeFormationProTransport" value=""/>
                                                        <input type="hidden" name="taxeRegionale1CV" value=""/>
                                                        <input type="hidden" name="exoVehiculePropre" value=""/>
                                                        <input type="hidden" name="vehiculeDeCollection" value="false"/>
                                                        <input type="hidden" name="pourcentMalusCO2" value=""/>
                                                        <input type="hidden" name="annee" value="2021"/>
                                                        <input type="hidden" name="anneeMalus" value="2021"/>
                                                        <input type="hidden" name="malusCO2" value=""/>
                                                        <input type="hidden" name="malus_Selon_Taux_CO2" value=""/>
                                                        <input type="hidden" name="malus_Selon_CV" value=""/>
                                                        <input type="hidden" name="malus" value="0,00"/>
                                                        <input type="hidden" name="malusReduit" value="0,00"/>
                                                        <input type="hidden" name="energiePropre" value=""/>
                                                        <input type="hidden" name="taxeRegionaleY1AvantReduc"
                                                               value="0,000"/>
                                                        <input type="hidden" name="taxeRegionaleY1" value="0,00"/>
                                                        <input type="hidden" name="MajorationVehiculeTransportY2"
                                                               value="0,00"/>
                                                        <input type="hidden" name="Y3_Malus" value="0,00"/>
                                                        <input type="hidden" name="taxeGestionY4" value="11,00"/>
                                                        <input type="hidden" name="sousTotal" value="11,00"/>
                                                        <input type="hidden" name="redevanceAcheminementY5"
                                                               value="0,00"/>
                                                        <input type="hidden" name="taxesAPayerY6" value="11,00"/>
                                                        <input type="hidden" name="CamionnettePickUpSoumisEcotaxes"
                                                               value="false"/>
                                                        <input type="hidden" name="periodeannee" value="1"/>
                                                        <input type="hidden" name="EnergieTresPropre" value=""/>
                                                        <input type="hidden" name="VehiculeTourisme" value="false"/>
                                                        <input type="hidden" name="Malus_CV_E85" value=""/>
                                                        <input type="hidden" name="Puissance_CV_E85" value=""/>
                                                        <input type="hidden" name="step" value="0"/>
                                                        <input type="hidden" name="sequence" value=""/>
                                                        <input type="hidden" name="script" value="1"/>
                                                        <input type="hidden" name="view" value="particuliers"/>
                                                        <input type="hidden" name="recaptcha"
                                                               value="&lt;YOUR GOOGLE SITE KEY&gt;"/>
                                                        <input type="hidden" name="visible_datas_in_pdf"
                                                               id="visible_datas_in_pdf" value="{}"/>
                                                        <input type="hidden" name="_csrf_token"
                                                               value="Eox32gGkhd-NUxf8OruK5HD7UoQM1fbUiD9iqLlPzJQ"/>


                                                    </form>
                                                </div>

                                            </article>
                                        </div>
                                        <!-- col-main -->
                                    </div>
                                    <!-- container -->
                                </main>
                                <!-- Modal -->
                                <div class="modal fade" id="modal-mail" tabindex="-1" role="dialog"
                                     aria-labelledby="modalLabel"
                                     aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="btn btn-link btn-close"
                                                        data-dismiss="modal">Fermer
                                                </button>
                                                <h4 class="modal-title" id="modalLabel">Partage par courriel</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div id="envoi-mail" class="envoi-mail">
                                                    <form action="">
                                                        <p><strong>Pour partager cette page, veuillez saisir les
                                                                informations suivantes</strong>
                                                        </p>
                                                        <p class="note">Les champs marqués d'un <span
                                                                    class="symbol-required">*</span> sont
                                                            obligatoires</p>
                                                        <div class="form-group envoi-mail-col-1">
                                                            <label for="envoi-mail-1"><span
                                                                        class="symbol-required">*</span> Nom</label>
                                                            <input type="text" class="form-control" id="envoi-mail-1"
                                                                   aria-required="true">
                                                        </div>
                                                        <div class="form-group envoi-mail-col-2">
                                                            <label for="envoi-mail-2"><span
                                                                        class="symbol-required">*</span> Prénom</label>
                                                            <input type="text" class="form-control" id="envoi-mail-2"
                                                                   aria-required="true">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="envoi-mail-3"><span
                                                                        class="symbol-required">*</span> Le courriel des
                                                                destinataires</label>
                                                            <input type="text" class="form-control" id="envoi-mail-3"
                                                                   aria-required="true">
                                                            <p class="help-block">Vous pouvez mettre jusqu'à 3 adresses
                                                                en les séparant par un
                                                                ;</p>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="envoi-mail-4">Votre message</label>
                                                            <textarea class="form-control" id="envoi-mail-4" cols="30"
                                                                      rows="8"></textarea>
                                                            <p class="letter-count" aria-live="polite"><span>800</span>
                                                                caractères restants</p>
                                                        </div>
                                                        <div class="submit">
                                                            <button class="btn btn-primary" type="submit">Envoyer
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                                <!-- envoi-mail -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--[if lt IE 9]>
<!--                        <script src="https://www.service-public.fr/simulateur/calcul/assets/base/js/libs/jquery-1.11.1.min.js?version=1.4.21"></script>-->

<!--&lt;!&ndash;-->                        <![endif]-->-->
                        <!--[if gte IE 9]><!-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/base/js/libs/jquery-3.3.1.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/base/js/libs/jquery-migrate-3.0.0.js?version=1.4.21"></script>-->
                        <!--<![endif]-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/base/js/libs/xss.min.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/base/js/libs/jquery.toggleOption.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/base/js/libs/jquery.swap-image.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/base/js/libs/jquery.cookie.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/base/js/libs/jquery.placeholder.min.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/bundles/bazingajstranslation/js/translator.min.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/bundles/bazingajstranslation/js/translations/config.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/bundles/bazingajstranslation/js/translations/messages/fr.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/base/js/libs/autoNumeric/autoNumeric.min.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/base/js/libs/DefiantJS/defiant.min.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/base/js/libs/JSONPath/jsonpath.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/base/widgets/abListbox/js/listbox.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/base/widgets/abListbox/js/abListbox.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/base/widgets/abDatepicker/js/locales/fr-FR.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/base/widgets/abDatepicker/js/datepicker.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/base/widgets/abDatepicker/js/abDatepicker.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/simulateur/calcul/assets/base/js/g6k.min.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript">-->
<!--                            $(function () {-->
<!--                                var options = {-->
<!--                                    locale: 'fr-FR',-->
<!--                                    dynamic: true,-->
<!--                                    mobile: false,-->
<!--                                    dateFormat: 'd/m/Y',-->
<!--                                    decimalPoint: ',',-->
<!--                                    moneySymbol: '€',-->
<!--                                    symbolPosition: 'after',-->
<!--                                    arbotypePage: 'simulateur',-->
<!--                                    groupingSeparator: ' ',-->
<!--                                    groupingSize: '3'-->
<!--                                };-->
<!--                                var g6k = new G6k(options);-->
<!--                                g6k.run();-->
<!--                            });-->
<!--                        </script>-->
                        <!--  JavaScript  -->

                        <script> document.urlResourcesRoot = "https:\/\/www.service-public.fr\/resources\/v-16617db380";</script>
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/resources/v-16617db380/web/js/lib/require.js"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/resources/v-16617db380/web/js/lib/common.js"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/resources/v-16617db380/web/js/lib/configRgpd.js"></script>-->
<!--                        <script type="text/javascript"-->
<!--                                src="https://www.service-public.fr/resources/v-16617db380/web/js/ie/expandAllOnIE8.js"></script>-->
<!--                        <script type="text/javascript">-->
<!--                            if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent))-->
<!--                                document.write('<script src="https://www.service-public.fr/resources/v-16617db380/web/js/ie/polyfill.min.js"><\/script>');-->
<!--                        </script>-->
                        <script>
                            window['disable-google-analytics-property-name'] = 'ga-disable-' + '"UA-58964926-1"';
                        </script>
                        <script type="opt-in" data-type="application/javascript" data-name="google-analytics">


                        </script>

                        <script type="opt-in" data-type="application/javascript" data-name="at-internet">

                        </script>

<!--                        <script src="https://www.service-public.fr/simulateur/calcul/assets/particuliers/static/js/patch.accessibilite.dila.js?version=1.4.21"></script>-->
<!--                        <script type="text/javascript">-->
<!--                            $.noConflict();-->
<!--                            jQuery(document).ready(function () {-->
<!--                                $('input.listbox-input').listbox('theme', 'blue');-->
<!--                                $('input.date').datepicker('theme', 'blue');-->
<!--                                $('input.date').datepicker('inputFormat', ["d/M/y", "d-M-y", "d.M.y", "ddMMy", "dd/MM/y"]);-->
<!--                                $('html body').find(':input:visible').first().focus();-->
<!--                                window.scrollTo(0, 0);-->
<!--                            });-->
<!--                            var ad_slots = {};        </script>-->


                        <!--</html>-->


                        <div class="sideRight item-layout-action">

                            <?php print_catersis_form_rightSide(); ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


<?php }

function _cartersis_get_regions_data()
{

    return array(

        /* auvergne-rhone-alpes */

        'auvergne-rhone-alpes_rhone-alpes' => array(

            'state_abbreviation' => 'B9',
            'tax_rate' => 43

        ),
        'auvergne-rhone-alpes_auvergne' => array(

            'state_abbreviation' => '98',
            'tax_rate' => 43

        ),

        /* bourgogne-franche-comte */

        'bourgogne-franche-comte_bourgogne' => array(

            'state_abbreviation' => 'A1',
            'tax_rate' => 51

        ),
        'bourgogne-franche-comte_franche-comte' => array(

            'state_abbreviation' => 'A6',
            'tax_rate' => 51

        ),

        /* bretagne */

        'bretagne' => array(

            'state_abbreviation' => 'A2',
            'tax_rate' => 51

        ),

        /* centre-val-de-loire */

        'centre-val-de-loire' => array(

            'state_abbreviation' => 'A3',
            'tax_rate' => 49.8

        ),

        /* corse */

        'corse' => array(

            'state_abbreviation' => 'A5',
            'tax_rate' => 27
        ),

        /* grand-est */

        'grand-est_lorraine' => array(

            'state_abbreviation' => 'B2',
            'tax_rate' => 42
        ),

        'grand-est_champagne-ardenne' => array(

            'state_abbreviation' => 'A4',
            'tax_rate' => 42
        ),

        'grand-est_alsace' => array(

            'state_abbreviation' => 'C1',
            'tax_rate' => 42
        ),

        /* hauts-de-france */

        'hauts-de-france_picardie' => array(

            'state_abbreviation' => 'B6',
            'tax_rate' => 33

        ),

        'hauts-de-france_nord-pas-de-calais' => array(

            'state_abbreviation' => 'B4',
            'tax_rate' => 35.4

        ),

        /* ile-de-france */

        'ile-de-france' => array(

            'state_abbreviation' => 'A8',
            'tax_rate' => 46.15

        ),

        /* nouvelle-aquitaine */

        'nouvelle-aquitaine_poitou-charentes' => array(

            'state_abbreviation' => 'B7',
            'tax_rate' => 41

        ),

        'nouvelle-aquitaine_limousin' => array(

            'state_abbreviation' => 'B1',
            'tax_rate' => 41

        ),

        'nouvelle-aquitaine_aquitaine' => array(

            'state_abbreviation' => '97',
            'tax_rate' => 41

        ),

        /* normandie */

        'normandie_basse-normandie' => array(

            'state_abbreviation' => '99',
            'tax_rate' => 35

        ),

        'normandie_haute-normandie' => array(

            'state_abbreviation' => 'A7',
            'tax_rate' => 35

        ),

        /* occitanie */

        'occitanie_midi-pyrenees' => array(

            'state_abbreviation' => 'B3',
            'tax_rate' => 44

        ),

        'occitanie_languedoc-roussillon' => array(

            'state_abbreviation' => 'A9',
            'tax_rate' => 44

        ),

        /* pays-de-la-loire */

        'pays-de-la-loire' => array(

            'state_abbreviation' => 'B5',
            'tax_rate' => 48

        ),

        /* provence-alpes-cote-d-azur */

        'provence-alpes-cote-d-azur' => array(

            'state_abbreviation' => 'B8',
            'tax_rate' => 51.2

        ),

        /* guadeloupe */

        'guadeloupe' => array(

            'state_abbreviation' => 'GP',
            'tax_rate' => 41

        ),

        /* guyane */

        'guyane' => array(

            'state_abbreviation' => 'GF',
            'tax_rate' => 42.5

        ),

        /* la-reunion */

        'la-reunion' => array(

            'state_abbreviation' => 'RE',
            'tax_rate' => 51

        ),

        /* martinique */

        'martinique' => array(

            'state_abbreviation' => 'MQ',
            'tax_rate' => 30

        ),

        /* mayotte */

        'mayotte' => array(

            'state_abbreviation' => '00',
            'tax_rate' => 30

        ),

    );

}

function _cartersis_get_tax_region($st_abbreviation)
{

    $data = _cartersis_get_regions_data();

    $st_abbreviation = trim(strtolower($st_abbreviation));

    foreach ($data as $key => $region) :

        extract($region);

        $state_abbreviation = trim(strtolower($state_abbreviation));

        if ($state_abbreviation === $st_abbreviation) :

            return $tax_rate;

        endif;

    endforeach;

    return null;

}

function is_region($name, $st_abbreviation)
{

    $data = _cartersis_get_regions_data();

    return strtolower($data[$name]['state_abbreviation']) === strtolower($st_abbreviation);

}

function is_new_vehicle_action($demarche)
{

    return $demarche === 'carte_grise_dun_vehicule_neuf';

}

function get_tax_region_ratio_percent($vehicle_id, $date_immatriculation)
{

    $diff_year = get_diff_year_by_immatriculation($date_immatriculation);

    if ($vehicle_id === TYPE_VEHICLES::PERSONAL_VEHICLE ||
        $vehicle_id === TYPE_VEHICLES::THREE_WHEEL_MOTORCYCLE_VEHICLE ||
        $vehicle_id === TYPE_VEHICLES::THREE_WHEEL_MOTORCYCLE_VEHICLE_HAS_ENGINE ||
        $vehicle_id === TYPE_VEHICLES::MULTIPURPOSE_VEHICLE) :

        if ($diff_year < 10) :

            return '1';

        else :

            return '1/2';

        endif;

    endif;

    if ($vehicle_id === TYPE_VEHICLES::TWO_WHEEL_MOTORCYCLE_VEHICLE) :

        if ($diff_year < 10) :

            return '1/2';

        else :

            return '1/4';

        endif;

    endif;

    if ($vehicle_id === TYPE_VEHICLES::MOTORCYCLE_VEHICLE_SMALLER_THAN_50CC) :

        if ($diff_year < 10) :

            return '1/2cv';

        else :

            return '1/4cv';

        endif;

    endif;

    if ($vehicle_id === TYPE_VEHICLES::MULTIPURPOSE_VEHICLE) :

        if ($diff_year < 10) :

            return '1/2';

        else :

            return '1/4';

        endif;


    endif;

    if ($vehicle_id === TYPE_VEHICLES::TRACTOR_VEHICLE_LARGER_THAN_3T) :

        if ($diff_year < 10) :

            return '1/2';

        else :

            return '1/4';

        endif;


    endif;

    if ($vehicle_id === TYPE_VEHICLES::SEMI_TRAILER_VEHICLE) :

        return '1.5cv';

    endif;

    return '';

}

function calc_tax_region_ratio_percent($cv, $tax_rate, $vehicle_id, $date_immatriculation)
{

    $result = get_tax_region_ratio_percent($vehicle_id, $date_immatriculation);

    $full_result = round($cv * $tax_rate);

    if ($result) :

        switch ($result) :

            case '1' :

                return $full_result;

                break;

            case '1/2' :

                return $full_result / 2;

                break;

            case '1/4' :

                return $full_result / 4;

                break;

            case '1/2cv' :

                return ($cv / 2) * $tax_rate;

                break;

            case '1/4cv' :

                return ($cv / 4) * $tax_rate;

                break;

            case '1.5cv' :

                return (1.5 * $cv) * $tax_rate;

                break;

        endswitch;

    endif;

    return $full_result;

}


function get_type_special_vehicle($code)
{

    switch ($code) :

        case 'vp' :

            return TYPE_VEHICLES::PERSONAL_VEHICLE;

            break;

        case 'motocyclette':

            return TYPE_VEHICLES::TWO_WHEEL_MOTORCYCLE_VEHICLE;

            break;

        case 'cyclomoteur':

            return TYPE_VEHICLES::THREE_WHEEL_MOTORCYCLE_VEHICLE;

            break;

        case 'tricycles_tm':

            return TYPE_VEHICLES::THREE_WHEEL_MOTORCYCLE_VEHICLE_HAS_ENGINE;

            break;

        case 'quadricycles':

            return TYPE_VEHICLES::FOUR_WHEEL_MOTORCYCLE_VEHICLE_HAS_ENGINE;

            break;

        case 'vehicule' :

            return TYPE_VEHICLES::MULTIPURPOSE_VEHICLE;

            break;

        case 'velomoteur_et_cyclomoteur' :

            return TYPE_VEHICLES::MOTORCYCLE_VEHICLE_SMALLER_THAN_50CC;

            break;

        case 'rem' :

            return TYPE_VEHICLES::SEMI_TRAILER_VEHICLE;

            break;

        case 'camion' :

            return TYPE_VEHICLES::TRUCK_VEHICLE_LARGER_THAN_3T;

            break;

        case 'bus_tcp' :

            return TYPE_VEHICLES::BUS_VEHICLE_LARGER_THAN_3T;

            break;

        case 'tracteur_routier' :

            return TYPE_VEHICLES::TRACTOR_VEHICLE_LARGER_THAN_3T;

            break;

        case 'resp' :

            return TYPE_VEHICLES::TRAVEL_TRAILER_VEHICLE;

            break;

        case 'engin' :

            return TYPE_VEHICLES::AGRICULTURAL_MACHINES_VEHICLE;

            break;

        case 'vehicule_societe' :

            return TYPE_VEHICLES::UTILITY_VEHICLE;

            return;

        default:

            break;

    endswitch;

    return null;

}

// $date format: Y-m-d
function get_diff_year_by_immatriculation($date)
{

    $now = date('Y-m-d');

    return DateTimeUtils::getDiffYear($date, $now);

}

function print_cartise_form_calculate_results()
{

    //echo "<pre>";

    //print_r($_POST);

    require_once _LIB_PHP_CURL_DIR . '/src/Curl/Curl.php';

    $curl = new Curl\Curl();

    //echo var_dump($curl);

    $postalcode = $_POST['codepostal'];
    $aliases = _ALIAS_API_REGIONS;

    $cv = (int)$_POST['chevaux_fiscaux'];
    $co2 = (int)$_POST['co2'];
    $type_vehicule = $_POST['type_vehicule'];
    $energie = $_POST['energie'];
    $date_immatriculation = $_POST['date_immatriculation'];
    $neuf_choice = $_POST['neuf-choice'];

    $demarche = $_POST['demarche'];

    $region_data = array();

    $result = 0;

    //print_r($_POST);

    $demarche_label = get_label_option_by_value('cartiseform-selectionnez-select-id', $demarche);
    $type_vehicule_label = get_label_option_by_value('cartiseform-genre-select-id', $type_vehicule);
    $energie_label = get_label_option_by_value('cartiseform-energie-select-id', $energie);

    echo 'demarche: ' . $demarche_label . '<br/>';
    echo 'type vehicule: ' . $type_vehicule_label . '<br/>';
    echo 'postal code: ' . $postalcode . '<br/>';
    echo 'cv: ' . $cv . '<br/>';
    echo 'date immatriculation: ' . $date_immatriculation . '<br/>';
    echo 'energie: ' . $energie_label . '<br/>';
    echo 'neuf choice: ' . $neuf_choice . '<br/>';
    echo 'co2: ' . $co2 . '<br/>';

    if ($postalcode) :

        foreach ($aliases as $key => $alias) :

            sleep(1);

            $url = _API_REGION_URL . '/' . $alias . '/' . $postalcode;

            //echo $url . "<br/>";

            $curl->get($url);

            if ($curl->error) :

                $contents = false;

            else :

                $contents = $curl->response;

            endif;

            // Open the file using the HTTP headers set above
            //$contents = file_get_contents($url);

            if ($contents !== false) :

                $region_data = json_decode($contents, true);

                break;

            endif;

        endforeach;

    endif;

    //print_r( $region_data );

    if ($region_data) :

        $region_data = $region_data['places'][0];

        echo 'state: ' . $region_data['state'] . '<br/>';
        echo 'state abbreviation: ' . $region_data['state abbreviation'] . '<br/>';

        $st_abbreviation = $region_data['state abbreviation'];

        $tax_rate = _cartersis_get_tax_region($st_abbreviation);

        if (is_new_vehicle_action($demarche)) :

            $vehicle_id = get_type_special_vehicle($type_vehicule);

            $tax_rate = calc_tax_region_ratio_percent($cv, $tax_rate, $vehicle_id, $date_immatriculation);

        else :

            if ($tax_rate) :

                $tax_rate = round($cv * $tax_rate);

            endif;

        endif;

        if ($tax_rate) :

            echo '<br/>Tax region: ' . $tax_rate . ' ' . EURO;

        endif;

    endif;


}
