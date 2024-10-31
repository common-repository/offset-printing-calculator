<?php

// get language of blog
$lang = get_bloginfo('language');
// adapt value of language to be name of a variable
$t = str_replace('-', '_', $lang);
// translation
$ro_RO = array(
	'Introdu titlul' => 'O denumire ce  va apare ca titlu afisat public in widget.',

	'Moneda' => 
	'Valuta in care  se vor face calculele de pret. Atat pentru hartie, pentru placi sau prelucrari calculele se fac in aceasi valuta. Daca alegeti o anumita valuta
	iar dvs. achizitionati hartia in alta valuta va trebui sa convertiti costul hartiei in valuta aleasa pentru calculatorul de tipar.',

	'Coala offset de la producator (in mp)' =>
	'Colile achizitionate de la furnizor sunt in principiu de doua formate, 0.7 x 1 m si 0.64 x 0.88, cu suprafete corespunzatoare. Numarul care intereseaza aici este
	 suprafata formatului in metrii patrati. Pentru formatul 0.7 x 1 m suprafata este de 0.7 mp, pentru 0.64 x 0.88 este de 0.56 mp. 
	 Pentru alte formate cumparate de la furnizorul de hartie calculul suprafetei se face simplu prin inmultirea dimensiunilor (exprimate in metrii) ce definesc formatul',

	 'Divizor coala offset in coli de tiparit' => 
	 'Formatul masinii de tiparit de obicei este diferit de formatul colii offset achizitionate de la furnizor. Aceasta coala mare trebuie impartita la ghilotina
 pentru a obtine formate utilizabile pentru masina dvs. de tiparit. Divizorul colii offset este numarul la care se imparte coala furnizorului de hartie pentu a obtine
  coli de tiparit.',

  'Zona maxima de printare' => 
  'Aria tiparibila pe coala de tiparit de marimea formatului masinii de tipar. O portiune perimetrica nu poate fi utilizata la tiparire fiind rezervata actiunilor mecanice 
  de preluare a colii din stiva de alimentare cu foi sau inutilizabila din motive constructive. De obicei 1 cm de jur-imprejurul colii de tiparit.',

  'Coala de tiparit' =>
  'Colile de hartie  in formatul cel mai potrivit cu dimensiunile maxime de tiparit ale masinii.',

  'Prisoase coli de tiparit' =>
  'Numarul de coli de tiparit necesar potrivirii masini pentru executarea unei lucrari anume',

  'Factor granular' =>
  'Ghilotina dvs. are limite minime la care poate taia coala de tipar. Practic nu poate taia sub anumite dimensiuni. Acele dimensiuni repezinta cel mai mic 
  format pe care il puteti tipari. De cate ori incape pe coala de tiparit acel format minim este un numar cunoscut ca factor granular.',

  'Viteza tiparit coli / ora' => 'Numarul de coli de tiparit ce poate fi tiparit (doar pe o fata) de masina intr-o ora',

  'Cost ora de tiparire' => 'Cost ora de tiparit exprimat in valuta aleasa pentru calculator',

  'Cost placa tipar' => 'Costul unei placi tipografice in valuta aleasa',

  'TVA %' => 'Taxa pe Valoare Adaugata exprimata ca numar desemnand un procent. Exemplu: Se introduce 24 reprezentand 24 %',

  'Acronim TVA' => 'Prescurtare Taxa pe Valoare Adaugata. Ex: TVA',

  'Formate prestabilite' => 'Reprezinta o colectie de formate deschise, adica dimensiuni prestabilite ale lucrarii tiparite finale a clientului considerata in dimensiunile desfasurate.
	 Prima coloana cuprinde numarul de cate ori incape lucrarea finala pe coala de tiparit, cealalta coloana denumirea formatului.',

  'Gramaje hartie de tiparit' => 'Gramajele hartiei folosite in lucrari. Se foloseste coloana din dreapta.',

  'Cost pliere' => 'Costul operatiunii de indoire per indoire exprimat in valuta.',
);

$en_EN = array(
	'Introdu titlul' => 'A name that will appear as the title publicly displayed in the widget.',

	'Moneda' => 
	'Currency price calculations will be made. As for paper, plates or processing calculations are done in the same currency. If you choose a certain currency
and you buy paper in another currency will be converted paper currency cost choice for computer printing.',

	'Coala offset de la producator (in mp)' =>
	'Sheets purchased from supplier are basically two formats, 0.7 x 1 m and 0.64 x 0.88, with corresponding surfaces. Number who are interested here is
area size in square meters. Format is 0.7 x 1 m area of 0.7 square meters for 0.64 x 0.88 is 0.56 m.
For other formats provider purchased paper surface calculation is done simply by multiplying the size (expressed in meters) that define the format',

	 'Divizor coala offset in coli de tiparit' => 
	 'Format printing machine is usually different sheet format offset purchased from the supplier. This high school should be divided by the guillotine
  to obtain usable formats for your printing machine. Divider sheet offset is the number that divides the paper sheet for proper provider to obtain
   printed sheets.',

'Zona maxima de printare' => 
  'Printable aria of the sheet having dimensions almost as big as offset machine aria. A perimetral portion can\'t be used for printing due to that portion 
  is rezerved for mechanical actions for sheets takeover from the stack or non-usable by machine design.  Usually 1 cm all around the printed sheet.',

  'Coala de tiparit' =>
  'Sheets of paper in the most appropriate format printing with maximum dimensions of the car.',

  'Prisoase coli de tiparit' =>
  'Number of sheets printed need to run a car matching specific',

  'Factor granular' =>
  'Your Guillotine has minimum limits that can cut sheet printing. Basically can not cut below a certain size. Those dimensions repezinta the lowest
   format that you can print. Whenever that fit the minimum format printing sheet is a number known as granular factor.',

  'Viteza tiparit coli / ora' => 'The number of printed sheets that can be printed (only on one side) of the car in an hour',

  'Cost ora de tiparire' => 'Time cost printed currency chosen computer',

  'Cost placa tipar' => 'The cost of printing plates chosen currency',

  'TVA %' => 'Value Added Tax expressed as number designating a percentage. Example: Enter 24 or 24%',

  'Acronim TVA' => 'Abbreviation Value Added Tax. Ex: VAT',

  'Formate prestabilite' => 'Is a collection of open formats, ie the default size of the final printed paper sizes considered developed client.
The first column contains the number of times the final fit on the sheet of printed paper, the other column name format.',

  'Gramaje hartie de tiparit' => 'Weight in grams of the paper used in construction. Use the right column.',

  'Cost pliere' => 'Bending bending operation cost per currency.',
);

?>
<h2><?php _e('Ajutor', 'calculator-tipar') ?></h2>

<h3><?php _e('Introdu titlul', 'calculator-tipar') ?></h3>
<p>
	<?php echo ${$t}['Introdu titlul']; ?>
</p>

<h3><?php _e('Moneda', 'calculator-tipar') ?></h3>
<p>
	<?php echo ${$t}['Moneda']; ?>
</p>

<h3><?php _e('Coala offset de la producator (in mp)', 'calculator-tipar') ?></h3>
<p>
	<?php echo ${$t}['Coala offset de la producator (in mp)']; ?>
</p>

<h3><?php _e('Divizor coala offset in coli de tiparit', 'calculator-tipar') ?></h3>
<p>
	<?php echo ${$t}['Divizor coala offset in coli de tiparit']; ?>
</p>

<h3><?php _e('Zona maxima de printare', 'calculator-tipar') ?></h3>
<p>
	<?php echo ${$t}['Zona maxima de printare']; ?>
</p>

<h3><?php _e('Coala de tiparit', 'calculator-tipar') ?></h3>
<p>
	<?php echo ${$t}['Coala de tiparit']; ?>
</p>

<h3><?php _e('Prisoase coli de tiparit', 'calculator-tipar') ?></h3>
<p>
	<?php echo ${$t}['Prisoase coli de tiparit']; ?>
</p>

<h3><?php _e('Factor granular', 'calculator-tipar') ?></h3>
<p>
	<?php echo ${$t}['Factor granular']; ?>
</p>

<h3><?php _e('Viteza tiparit coli / ora', 'calculator-tipar') ?></h3>
<p>
	<?php echo ${$t}['Viteza tiparit coli / ora']; ?>
</p>

<h3><?php _e('Cost ora de tiparire', 'calculator-tipar') ?></h3>
<p>
	<?php echo ${$t}['Cost ora de tiparire']; ?>
</p>

<h3><?php _e('Cost placa tipar', 'calculator-tipar') ?></h3>
<p>
	<?php echo ${$t}['Cost placa tipar']; ?>
</p>

<h3><?php _e('TVA %', 'calculator-tipar') ?></h3>
<p>
	<?php echo ${$t}['TVA %']; ?>
</p>

<h3><?php _e('Acronim TVA', 'calculator-tipar') ?></h3>
<p>
	<?php echo ${$t}['Acronim TVA']; ?>
</p>	

<h3><?php _e('Formate prestabilite', 'calculator-tipar') ?></h3>
<p>
	<?php echo ${$t}['Formate prestabilite']; ?> 
</p>

<h3><?php _e('Gramaje hartie de tiparit', 'calculator-tipar') ?></h3>
<p>
	<?php echo ${$t}['Gramaje hartie de tiparit']; ?>
</p>

<h3><?php _e('Cost pliere', 'calculator-tipar') ?></h3>
<p>
	<?php echo ${$t}['Cost pliere']; ?>
</p>
