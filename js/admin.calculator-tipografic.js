// UI for admin
(
	// send $
	function($)
	{
		// wait for document to be ready
		$(document).ready(
			// finally work horse function
			function()
			{
				var get = function()
				{
					var 
					scripts = document.getElementsByTagName('script'),
					query = '';

					for(var i in scripts)
					{
						if (scripts[i].src && scripts[i].src.match(/admin\.calculator\-tipografic.js\?/))
						{
							query = scripts[i].src.replace(/^[^\?]+\??/,'');
							query = query.replace(/\&ver=[0-9\.]+/, '');
					
							return query;
						}

					}


					return query;
				}

				// url to img send as query appended to this script
				var
				img = get(), 
				EMPTY = '------uncommon value, preferable unique, designed to represent empty-------',
				// edit here images path for icons used for action links
				ADD = img + 'Add.png',
				X = img + 'Cross.png',
				MUV = img + 'Up-Down.png',
				O = img + 'UFO.png';
				
				var convert_all = function()
				{
					var options = {headlinks : true,};

					convert($('#format'), options);
					convert($('#paper_weights'), options);
					convert($('#folds_price'), options);
					//convert($('#error'), options);

					// hide values which user cannot use
				}
				// convert a selectbox options into rows of pairs key values with editable inputs
				var convert = function(jselectbox, options)
				{
					if (typeof options == 'undefined')
						options = {headlinks : true,};
					// create editable inputs
					var 
					template = 
					'<li class="unit"><input id=":id[val][]" value=":val" /><input id=":id[text][]" value=":text" />' + 
					'<div class="actions"><a rel="delete" title="delete"><img src="' + X + '" /></a> <a rel="move"  title="move"><img src="' + MUV + '" /></a></div></li>',
					a = '<a rel="' + jselectbox.attr('id') + '"  title="add"> <img src="' + ADD + '" /></a>',
					back = '<a rel="' + jselectbox.attr('id') + '"  title="back to original"> <img src="' + O + '" /> </a>',
					ja = $(a),
					jback = $(back);

					ja.css('cursor', 'pointer');
					jback.css('cursor', 'pointer');

					// add a new row
					options.headlinks && 
					ja.on('click', function()
						{
							var 
							j = $(this),
							inputs = template.
							replace(/\:id/g, j.attr('rel')).
							replace(':val', '').
							replace(':text', '');

							$('ul.convert-row[rel="' + j.attr('rel') + '"]').show().
							prepend(inputs).find('a').css('cursor', 'pointer').end().find('a[rel="delete"]').on('click', function(){$(this).closest('li').remove();});
						});
					// clear row and rebuild it with original values
					options.headlinks && 
					jback.on('click', function()
						{
							var 
							j = $(this);
							// get ride of row
							$('ul.convert-row[rel="' + j.attr('rel') + '"]').remove();
							// build a fresh one from hidden selectbox
							convert($('#' + j.attr('rel')), {headlinks : false});
							//apply sortable to new generated row
							$('ul.convert-row[rel="' + j.attr('rel') + '"]').sortable(
							{
			            		revert: true
			        		})
						});

					options.headlinks &&
					jselectbox.prev('label').after(jback).after(ja);

					//template inputs container
					var layout = '<ul class="convert-row" rel="' + jselectbox.attr('id') + '">:content</ul>',
					// iterate on every option
					inputs = '',
					out = '';

					jselectbox.find('option').each(function()
					{
						var 
						joption = $(this),
						id  = joption.parent().attr('id'),
						val = joption.val(),
						text = joption.text();

						inputs += template.
						replace(/\:id/g, id).
						replace(':val', val).
						replace(':text', text);

					});			
					
					out = layout.replace(':content', inputs);
					jout = $(out);
					jout.find('a[rel="delete"]').on('click', function(){if (confirm('delete ?')) $(this).closest('li').remove();})
					jselectbox.after(jout).hide();

					jout.find('a').css('cursor', 'pointer');

					$('#paper_weights\\\[val\\\]\\\[\\\]').attr('readonly', 'readonly');
					
				}

				convert_all();

				$('ul.convert-row')
				.sortable(
				{
            		revert: true
        		});

				$('label').click(function(){
					var 
					i = $('label').index(this),
					j = $(this);

					if (j.next().is('input'))
						j.next().toggle();
					else
					{
						//HACK go after +, 0, hidden select elements
						$(this).next().next().next().next().toggle();
						//$(this).siblings('ul.convert-row:eq(' + i + ')').toggle();
					}
				});

				// show/hide help paragraphes
				$('div.calculator-tipar-offset.calculator-tipar-offset-col3 h3').next().hide().end().css('cursor', 'pointer').click(
					function ()
					{
						$(this).next().toggle();
					}
				);

				$('#save-configuration').on('click', function(){


					var
					btn_save = $(this),
					format = $('[id^=format\\\[]'),
					paper_weights = $('[id^=paper_weights\\\[]'),
					folds_price = $('[id^=folds_price\\\[]'),
					error = $('[id^=error\\\[]'),
					prev = '';

					// css loader
					btn_save.addClass('loading');

					var _format = {};
					prev = '';
					format.each(function(i)
					{
						var
						j = $(this);

						if (i % 2 == 0)
						{
							prev = j.val();
							_format[prev] = null;						
							
						}
						else
						{
							_format[prev] = j.val();
						}
					})

					var _paper_weights = [];
					prev = '';
					paper_weights.each(function(i)
					{
						var 
						j = $(this);
						if (i % 2 != 0)
						{
							_paper_weights.push(j.val());
						}
					})

					var _folds_price = {};
					prev = '';
					folds_price.each(function(i)
					{
						var
						j = $(this);

						if (i % 2 == 0)
						{
							prev = j.val();
							_folds_price[prev] = null;						
							
						}
						else
						{
							_folds_price[prev] = j.val();
						}
					})

					/*var _error = {};
					prev = '';
					error.each(function(i)
					{
						var 
						j = $(this);

						if (i % 2 == 0)
						{
							_error[j.val()] = '';
							prev = j.val();
						}
						else
						{
							_error[prev] = j.val();
						}
					})*/

					// replace null values with invalid ones
					var empty = function ()
					{
						for(var p in arguments)
						{
							var obj = arguments[p];
							for(var p in obj)
							{
								// empty keys will be discarded by $.param, give them an invalid value
								if (p == '')
								{
									obj[EMPTY] = obj[p]
									delete obj[p];
								}
							}
							
						}
					}

					empty(_format, _folds_price);

					var
					data = {'format' : _format, 'paper_weights' : _paper_weights, 'folds_price' : _folds_price, /*'error' : _error,*/ 'action' : 'update_options'},
					url = ajaxurl;
					// modification on design element affects this VERY IMPORTANT code line
					data = $(this).closest('form').serialize() + '&' + $.param(data);
// console.log(_format);
// console.log(data);

					var clear = function()
					{
						btn_save.removeClass('loading');
						$('input.error').removeClass();
						$('ul.error').remove();
					}

					$.post(url, data)
					.success(function(response)
					{
						clear();
					})
					.error(function(obj)
					{
						clear();

						btn_save.addClass('error');
						$('ul.error').remove();

						eval('var error = ' + obj.responseText);
						var key;
						for(var id in error)
						{
							$('#' + id).addClass('error');
							//console.log($('#' + id + ' ul.error'))
							//TODO: do error makeup here !!!!!!
							var arr = [];
							for(var p in error[id])
							{	
								error[id][p]['msg'] = (error[id][p]['msg'] + '').replace(EMPTY, '');
								arr = arr.concat(error[id][p]['msg']);
							}

							var 
							val_ = [],
							_val = [];
							for(var p in error[id])
							{	
								if (error[id][p]['val'] == EMPTY)
									error[id][p]['val'] = '';
								_val= _val.concat(error[id][p]['val']);
							}

							var which = 'val';
							if ( (key = id.slice(0, -1 * '_keys'.length)) && key.match(/^(format|folds_price)$/) )
							{	
								id = key;
							}
							else
							{
								which = 'text';
							}
							
							// find ofending input box
							$('ul[rel=' + id + ']').find('li input[id*="[' + which + '][]"]').filter(
								function(i)
								{ 
									return true && ($.inArray($(this).val(), _val) != -1);
								}
							).addClass('error');
								

							$('#' + id).after('<ul class="error"><li>' + arr.join('</li><li>') + '</li></ul>');
						}
					});

					return false;
				})
			}
		)
	}

)(jQuery);