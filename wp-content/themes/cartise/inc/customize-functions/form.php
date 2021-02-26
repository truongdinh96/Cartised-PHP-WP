<?php
class TYPE_VEHICLES {

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

function print_genre_options() {

	$section_cartise_form = get_option('section-cartise-form_option_name');

	$genres_option = $section_cartise_form['cartiseform-genre-select-id'];
	$genres_option = preg_split("/\R/", $genres_option);

	foreach ( $genres_option as $genre ) :

		$option = explode('|', $genre);
		$option = array_map('trim', $option); ?>

        <option value="<?php echo $option[0] ?>"><?php echo $option[1] ?></option>

	<?php endforeach;

}

function get_label_option_by_value($field_id, $v) {

	$section_cartise_form = get_option('section-cartise-form_option_name');

	$options = $section_cartise_form[$field_id];

	$data = preg_split("/\R/", $options);

	foreach ( $data as $key => $row ) :

		$pieces = explode('|', $row);
		$value = trim( $pieces[0] );
		$label = trim( $pieces[1] );

		if ( $value === $v ) return $label;

	endforeach;

	return null;

}

function get_energie_options() {

	$section_cartise_form = get_option('section-cartise-form_option_name');

	$energies_option = $section_cartise_form['cartiseform-energie-select-id'];

	return $energies_option;

}

function get_ptac_options() {

	$section_cartise_form = get_option('section-cartise-form_option_name');

	$ptac_option = $section_cartise_form['cartiseform-ptac-select-id'];

	return $ptac_option;

}

function print_energie_options() {

	$energies_option = get_energie_options();

	$energies_option = preg_split("/\R/", $energies_option);

	foreach ( $energies_option as $energie ) :

		$option = explode('|', $energie);
		$option = array_map('trim', $option); ?>

        <option value="<?php echo $option[0] ?>"><?php echo $option[1] ?></option>

	<?php endforeach;

}

function get_carrosserie_tm_options() {

	$section_cartise_form = get_option('section-cartise-form_option_name');

	$carrosserie_tm_options = $section_cartise_form['cartiseform-carrosserie-tm-select-id'];

	return $carrosserie_tm_options;

}

function get_carrosserie_cycl_options() {

	$section_cartise_form = get_option('section-cartise-form_option_name');

	$carrosserie_cycl_options = $section_cartise_form['cartiseform-carrosserie-cycl-select-id'];

	return $carrosserie_cycl_options;

}

function get_carrosserie_qm_options() {

	$section_cartise_form = get_option('section-cartise-form_option_name');

	$carrosserie_qm_options = $section_cartise_form['cartiseform-carrosserie-qm-select-id'];

	return $carrosserie_qm_options;

}

function print_demarche_options() {

	$section_cartise_form = get_option('section-cartise-form_option_name');

	$selectionnez_option = $section_cartise_form['cartiseform-selectionnez-select-id'];
	$selectionnez_option = preg_split("/\R/", $selectionnez_option);

	foreach ( $selectionnez_option as $selectionnez ) :

		$option = explode('|', $selectionnez);
		$option = array_map('trim', $option); ?>

        <option value="<?php echo $option[0] ?>"><?php echo $option[1] ?></option>

	<?php endforeach;

}

function print_catersis_form_l1() { ?>

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
                <input type="text" name="immatriculation" class="majuscules form-control" placeholder="1234 AB 78 ou AB-123-CD" value="">
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
                 data-carrosserie-qm-options="<?= get_carrosserie_qm_options() ?>" ></div>

        </div>

        <div class="inputbox submitForm">
            <input class="btn btn-primary btnCalculer" type="submit" value="Calculer">
        </div>

        <input type="hidden" id="txtNeufChoice_l1" name="neuf-choice" value="occasion-importee" />

    </form>

<?php }

function print_catersis_form_l2() { ?>

    <form id="frmCartise_l2" method="post" action="<?= "//{$_SERVER['SERVER_NAME']}/calculate-form" ?>">

        <div class="inputbox">
            <label>Renseignez l'immatriculation *</label>
            <input type="text" name="immatriculation_declaration_cession" id="immatriculation_declaration_cession" class="majuscules form-control" placeholder="1234 AB 78 ou AB-123-CD" value="">
        </div>
        <div class="inputbox">
            <label>Indiquez la date de la vente *</label>
            <input type="text" name="date_declaration_cession" id="l2__date_declaration_cession" class="form-control" value="">
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

function print_catersis_form_l3() { ?>

    <form id="frmCartise_l3" method="post" action="<?= "//{$_SERVER['SERVER_NAME']}/calculate-form" ?>">

        <div class="inputbox">
            <label>Sélectionnez votre démarche</label>
            <select name="demarche" class="form-control">

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
                <input type="text" name="immatriculation" class="majuscules form-control" placeholder="1234 AB 78 ou AB-123-CD" value="">
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
                 data-carrosserie-qm-options="<?= get_carrosserie_qm_options() ?>" ></div>
        </div>

        <div class="inputbox submitForm">
            <input class="btn btn-primary btnCalculer" type="submit" value="Calculer">
        </div>

        <input type="hidden" id="txtNeufChoice_l3" name="neuf-choice" value="occasion-importee" />

    </form>


<?php }

function print_catersis_form_alert_msg() { ?>

    <div id="msg_neuf_import" class="alert alert-info" role="alert" style="">
        Pour un véhicule importé de l'étranger, la puissance fiscale et/ou le taux de CO2 ne sont pas toujours connus. Indiquez les valeurs que vous pensez être correctes. Une fois le montant de la taxe déterminée précisemment par l'Administration, une régularisation
        sera faite avec vous le cas échéant. Le traitement du dossier peut prendre entre 3 et 4 semaines. </div>
<?php }

function print_catersis_form_tabs_list() { ?>

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

function print_catersis_form_heading() { ?>

    <h1>PRIX DE VOTRE CARTE GRISE EN LIGNE</h1>
    <h4 class="mtop14">
        Le tarif de votre carte grise dépend de plusieurs critères. Consultez le prix de votre démarche en remplissant le formulaire ci-dessous pour faire votre demande de carte grise en ligne.
    </h4>

<?php }

function print_catersis_form_rightSide() { ?>

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

function print_catersis_form() { ?>

    <div class="section-fullwidth section-carte-grise-form">

        <div class="container">

			<?php print_catersis_form_heading(); ?>

            <div class="form-main">

				<?php print_catersis_form_tabs_list(); ?>

                <div class="form-content ohidden">

                    <div class="split-columns two-columns-layout">

<!--                        <iframe src="https://1c7c2a4db739.ngrok.io/calcul/cout-certificat-immatriculation" style="width: 100%;"></iframe>-->
                        <iframe id="myIframe" src="https://da417503f457.ngrok.io/calcul/cout-certificat-immatriculation" width=100% height="1024px" scrolling="yes" style="overflow:hidden; margin-top:-4px; margin-left:-4px; border:none;"></iframe>
<!--	                    <iframe src="https://1c7c2a4db739.ngrok.io/calcul/cout-certificat-immatriculation" width=100% scrolling="no" style="overflow:hidden; margin-top:-4px; margin-left:-4px; border:none;" class="iframe-full-height"></iframe>-->
                        <div class="sideRight item-layout-action">

							<?php print_catersis_form_rightSide(); ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

<?php }

function _cartersis_get_regions_data() {

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

function _cartersis_get_tax_region($st_abbreviation) {

	$data = _cartersis_get_regions_data();

	$st_abbreviation = trim( strtolower( $st_abbreviation ) );

	foreach( $data as $key => $region ) :

		extract($region);

		$state_abbreviation = trim( strtolower( $state_abbreviation ) );

		if ( $state_abbreviation === $st_abbreviation ) :

			return $tax_rate;

		endif;

	endforeach;

	return null;

}

function is_region($name, $st_abbreviation) {

	$data = _cartersis_get_regions_data();

	return strtolower( $data[$name]['state_abbreviation'] ) === strtolower( $st_abbreviation );

}

function is_new_vehicle_action($demarche) {

	return $demarche === 'carte_grise_dun_vehicule_neuf';

}

function get_tax_region_ratio_percent($vehicle_id, $date_immatriculation) {

	$diff_year = get_diff_year_by_immatriculation($date_immatriculation);

	if ( $vehicle_id === TYPE_VEHICLES::PERSONAL_VEHICLE ||
		$vehicle_id === TYPE_VEHICLES::THREE_WHEEL_MOTORCYCLE_VEHICLE ||
		$vehicle_id === TYPE_VEHICLES::THREE_WHEEL_MOTORCYCLE_VEHICLE_HAS_ENGINE ||
		$vehicle_id === TYPE_VEHICLES::MULTIPURPOSE_VEHICLE ) :

		if ( $diff_year < 10 ) :

			return '1';

		else :

			return '1/2';

		endif;

	endif;

	if ( $vehicle_id === TYPE_VEHICLES::TWO_WHEEL_MOTORCYCLE_VEHICLE ) :

		if ( $diff_year < 10 ) :

			return '1/2';

		else :

			return '1/4';

		endif;

	endif;

	if ( $vehicle_id === TYPE_VEHICLES::MOTORCYCLE_VEHICLE_SMALLER_THAN_50CC ) :

		if ( $diff_year < 10 ) :

			return '1/2cv';

		else :

			return '1/4cv';

		endif;

	endif;

	if ( $vehicle_id === TYPE_VEHICLES::MULTIPURPOSE_VEHICLE ) :

		if ( $diff_year < 10 ) :

			return '1/2';

		else :

			return '1/4';

		endif;


	endif;

	if ( $vehicle_id === TYPE_VEHICLES::TRACTOR_VEHICLE_LARGER_THAN_3T ) :

		if ( $diff_year < 10 ) :

			return '1/2';

		else :

			return '1/4';

		endif;


	endif;

	if ( $vehicle_id === TYPE_VEHICLES::SEMI_TRAILER_VEHICLE ) :

		return '1.5cv';

	endif;

	return '';

}

function calc_tax_region_ratio_percent($cv, $tax_rate, $vehicle_id, $date_immatriculation) {

	$result = get_tax_region_ratio_percent($vehicle_id, $date_immatriculation);

	$full_result = round( $cv * $tax_rate );

	if ( $result ) :

		switch ( $result ) :

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


function get_type_special_vehicle($code) {

	switch ( $code ) :

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
function get_diff_year_by_immatriculation($date) {

	$now = date('Y-m-d');

	return DateTimeUtils::getDiffYear($date, $now);

}

function print_cartise_form_calculate_results() {

	//echo "<pre>";

	//print_r($_POST);

	require_once _LIB_PHP_CURL_DIR . '/src/Curl/Curl.php';

	$curl = new Curl\Curl();

	//echo var_dump($curl);

	$postalcode = $_POST['codepostal'];
	$aliases = _ALIAS_API_REGIONS;

	$cv = (int) $_POST['chevaux_fiscaux'];
	$co2 = (int) $_POST['co2'];
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

	if ( $postalcode ) :

		foreach ( $aliases as $key => $alias ) :

			sleep(1);

			$url = _API_REGION_URL . '/'. $alias . '/' . $postalcode;

			//echo $url . "<br/>";

			$curl->get($url);

			if ($curl->error) :

				$contents = false;

			else :

				$contents = $curl->response;

			endif;

			// Open the file using the HTTP headers set above
			//$contents = file_get_contents($url);

			if ( $contents !== false ) :

				$region_data = json_decode($contents, true);

				break;

			endif;

		endforeach;

	endif;

	//print_r( $region_data );

	if ( $region_data ) :

		$region_data = $region_data['places'][0];

		echo 'state: ' . $region_data['state'] . '<br/>';
		echo 'state abbreviation: ' . $region_data['state abbreviation'] . '<br/>';

		$st_abbreviation = $region_data['state abbreviation'];

		$tax_rate = _cartersis_get_tax_region($st_abbreviation);

		if ( is_new_vehicle_action($demarche) ) :

			$vehicle_id = get_type_special_vehicle($type_vehicule);

			$tax_rate = calc_tax_region_ratio_percent($cv, $tax_rate, $vehicle_id, $date_immatriculation);

		else :

			if ( $tax_rate ) :

				$tax_rate = round( $cv * $tax_rate );

			endif;

		endif;

		if ( $tax_rate ) :

			echo '<br/>Tax region: ' . $tax_rate . ' ' . EURO;

		endif;

	endif;


}