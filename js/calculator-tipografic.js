// closure
(
	// send $
	function($)
	{
		// wait for document to be ready
		$(document).ready(
			// finally work horse function
			function()
			{
				var ground = document.calculator_tipografic_ground,
				// handler to calculate price
				pret = function(formats, front, verso, paper_weight, copies_number, folds_number)
				{
				
					var 
					papersheet_price = (1 / ground.papersheet_square_cutted_on_number) * (ground.papersheet_square *  paper_weight * 1020) / 1000000, // price of printing sheet where all final products will be composed
					folds_unit_price  = ground.folds_price[folds_number], // price of number of bigs done on a single final product
					folds_all_price = copies_number * folds_unit_price, // price of bigs for all final poducts  
					films = ground.plate_price * (front + verso), // offset printing plate's price
					papersheet_copies = parseInt( (copies_number < formats ? formats : copies_number) / formats), // number of printing sheets needed
					k = (1.68 - Math.log(papersheet_copies)/20).toFixed(2), // factor used in moderate price with copies number

					_printing_time = (papersheet_copies + ground.papersheet_starting) / ground.velocity, // estimated number of hours required to print sheets on one side
					cuts = Math.floor(formats / 2) * Math.ceil(parseInt(papersheet_copies / 200)) * 0.25, // price for cutting sheets to final dimensions

					printing_time = (_printing_time < 1)
					? 1 * ground.printing_hour_price : 
					1 * ground.printing_hour_price + (papersheet_copies - 1000) / ground.velocity * ground.printing_hour_price, // estimated printing time price

					pf = (front == 0) ? 0 : printing_time, // face one printing price
					pv = (verso == 0) ? 0 : _printing_time*ground.printing_hour_price, // verso printing pice

					pret = k * (papersheet_price * papersheet_copies + (pf + pv) + films + folds_all_price + cuts); // final price

					return pret.toFixed(2);
				},

				error_message = function(message)
				{
					var template = '<p class="error">' + message + '</p>';

					// there are extra arguments passed to this function
					if (arguments.length > 1)
					{
						for(var i = 1; i < arguments.length; i++)
						{
							template = template.replace('$' + i, arguments[i]);
						}

					}

					return template;
				},

				show = function(price)
				{
					var 
					vat_val = (price * ground.vat / 100).toFixed(2),

					html = '<table>' +
						'<tr><td>'+ ground.price +'</td><td>' + price + '</td></tr>' + 
						'<tr><td>' + ground.vat_acronym + '</td><td>' + vat_val + '</td></tr>' + 
						'<tr><td>total</td><td>' + ( ( 1 + ground.vat / 100) * price ).toFixed(2) + ' ' + ground.currency + '</td></tr>' + 
					'</table>';

					$('#pret').html(html);
				},

				onclick = function(evt)
				{
					evt.preventDefault();
					
					var 
					formats = 1 * $('#formats').val(),
					front = 1 * $('#front').val(), 
					verso = 1 * $('#verso').val(), 
					paper_weight = 1 * $('#paper_weight').val(), 
					copies_number = 1 * $('#copies_number').val(), 
					folds_number = 1 * $('#folds_number option:selected').text();

					/* validate */
					// previous error messages be gone!
					if ($('p.error') != undefined) 
						$('p.error').remove();
					$('.error').removeClass();
					// previous result adios!
					$('#pret').empty();
					
					// formats
					var ok_formats = (typeof formats == "number") && (formats > 0 && formats <= ground.papersheet_maxim_formats);

					if ( ! ok_formats)
					{
						$('#formats').addClass('error').after(error_message(ground.error.formats, ground.papersheet_maxim_formats));
					}					
					// copies
					var ok_copies_number = (typeof copies_number == "number") && (copies_number > 0);

					if ( ! ok_copies_number)
					{
						$('#copies_number').addClass('error').after(error_message(ground.error.copies_number));
					}
					// conclusion
					if ( ! ok_formats || ! ok_copies_number)
						return false;
					try
					{
						show(pret(formats, front, verso, paper_weight, copies_number , folds_number));
					}
					catch(e)
					{
						throw e;
						return false;
					}

					return false;
				}

				$('#do_calculation').click(onclick);

				// big select
				var options = '';
				for(var i =0; i < ground.folds_price.length; i++)
					options += '<option value="' + ground.folds_price[i] + '">' + i + '</option>';
				$('#folds_number').html(options);

				// paper select
				options = '';
				for(var i =0; i < ground.paper_weights.length; i++)
					options += '<option value="' + ground.paper_weights[i] + '">' + ground.paper_weights[i] + '</option>';
				$('#paper_weight').html(options);

				// ground.format select
				options = '';
				for(var prop in ground.format)
					options += '<option value="' + prop + '">' + ground.format[prop] + '</option>';
				$('#format').html(options).change(function()
					{
						
						$('#formats').val($('option:selected', this).val());
					});

				$('#formats').val($('#format option:selected').val());
			}
		)
	}

)(jQuery);
