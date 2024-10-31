<form method="POST">

<div class="fixed col-all"><h3><?php echo $title .': '. __('panel control') ?></h3><input type="submit" value="<?php _e('Salveaza', 'calculator-tipar') ?>" id="save-configuration" /></div>

<div class="calculator-tipar-offset-col1">
<?php include(dirname( __FILE__ ).'/control.php') ?>
</div>

<div class="calculator-tipar-offset calculator-tipar-offset-col2">

<label><?php _e('Formate prestabilite', 'calculator-tipar') ?></label>
<select multiple id="format" name="format"><?php echo $format ?></select>

<label><?php _e('Gramaje hartie de tiparit', 'calculator-tipar') ?></label>
<select multiple id="paper_weights" name="paper_weights" ><?php echo $paper_weights ?></select>

<label><?php _e('Cost pliere', 'calculator-tipar') ?></label>
<select multiple id="folds_price" name="folds_price" ><?php echo $folds_price ?></select>

<!--//<label><?php //_e('Mesaje eroare', 'calculator-tipar') ?></label>
<select multiple id="error" name="error"><?php //echo $error ?></select> //-->

</div>

<div class="calculator-tipar-offset calculator-tipar-offset-col3">
	<?php include dirname( __FILE__ ).'/help.php' ?>
</div>

</form>