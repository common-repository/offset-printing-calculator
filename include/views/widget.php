<form id="calculator-tipografic" name="calculator-tipografic">

	<legend><?php _e($title) ?></legend>

	<label for="format"><?php _e('Alege formatul', 'calculator-tipar') ?></label>
	<select id="format" name="format">
		<option />
	</select>

	<label for="formats"><?php _e('Format deschis', 'calculator-tipar') ?></label>
	<p class="info"><?php echo strtr(__('Cate formate deschise incap pe :print_area ?', 'calculator-tipar'), array(':print_area' => $maximum_print_area)) ?></p>
	<input type="text" id="formats" name="formats" />

	<label for="front"><?php _e('Tipar fata', 'calculator-tipar') ?></label>
	<select id="front" name="front">
		<option value="4"><?php _e('policromie', 'calculator-tipar') ?></option>
	</select>

	<label for="verso"><?php _e('Tipar verso', 'calculator-tipar') ?></label>
	<select id="verso" name="verso">
		<option value="0"><?php _e('fara', 'calculator-tipar') ?></option>
		<option value="4"><?php _e('policromie', 'calculator-tipar') ?></option>
	</select>

	<label for="folds_number"><?php _e('Numar indoituri', 'calculator-tipar') ?></label>
	<select id="folds_number" name="folds_number">
		<option />
	</select>

	<label for="paper_weight"><?php _e('Greutate hartie', 'calculator-tipar') ?></label>
	<select id="paper_weight" name="paper_weight">
		<option />
	</select>

	<label for="copies_number"><?php _e('Tiraj', 'calculator-tipar') ?></label>
	<input type="text" id="copies_number" name="copies_number" value="100" />

	<button id="do_calculation" name="do_calculation"><?php _e('Calculeaza', 'calculator-tipar') ?></button>
	
<div id="pret">
</div>

<div class="friendly-link"><?php echo $thanks_text_line ?> <a href="http://www.printuridigital.ro" target="blank"><?php echo $thanks_text_link ?></a></div>
</form>