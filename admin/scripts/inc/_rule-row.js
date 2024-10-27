
jQuery(document).ready( function(e) {

	$ = jQuery;

	//
	//	General Variables
	//

 	var rule_options = {
 		'title' : {
 			'contains' 	: 'text',
 			'exactly'	: 'text',
 			'does not contain'	: 'text'
 		},
 		'content' : {
 			'contains' 	: 'text',
 			'exactly'	: 'text',
 			'does not contain'	: 'text'
 		},
 		'category' : {
 			'is'	: 'categories',
 			'is not': 'categories',
 			'is child of'	: 'categories'
 		}, 
 		'post' : {
 			'is'	: 'posts',
 			'is not'	: 'posts',
 			'type'	: 'post_types',
 			'has tag'	: 'tags',
 			'has category' : 'categories',
 			'is child of'	: 'categories'
 		},
 		'date' : {
 			'is after'  : 'date',
 			'is before' : 'date'
 		},
 	};
 	var advert_rules = $(".advert-rules");

 	//
 	//	Helpers
 	//

 	// Generates and prints the options markup for a dropdown
 	function dropdown_options( field, options, value ) {
 		
 		var select = '',
 			markup = '';

 		options.forEach( function(opt) {
 			if ( value && value === opt ) {
 				select = ' selected';
 			}
 			markup += "<option val='" + opt + "'" + select + ">" + opt + "</option>";
 			select = '';
 		});

 		$(field).html(markup);

 	}

 	// Returns an array of key values from the passed object
 	function get_options( data ) {
 		var options = [];
		for( var prop in data ) {
			options.push(prop);
		}
		return options;
 	}

 	// Set the dropdown options for the passed row
 	function set_dropdowns( row ) {

 		var field = {
 			func 	 : $(row).find(".rule-func"),
 			operator : $(row).find(".rule-operator")
 		}

 		var options = {
 			func 	 : get_options(rule_options),
 			operator : get_options(rule_options[ field.func.val() ]) || get_options(rule_options[0])
 		};

 		dropdown_options( field.func, options.func, field.func.val() );
 		dropdown_options( field.operator, options.operator, field.operator.val() );

 	}

 	// Returns the result field type
 	function result_type( row ) {

 		var val = {
 			func : $(row).find(".rule-func").val(),
 			operator : $(row).find(".rule-operator").val()
 		}

 		if ( rule_options[ val.func ] ) {
 			if ( rule_options[ val.func ][ val.operator] ) {
 				return rule_options[ val.func ][ val.operator ];
 			}
 		}

 		return 'text';

 	}

 	// Sets the result field
 	function set_result( row ) {
 		
 		var field = $(row).find(".rule-result");
 		var value = $(field).val();
 		var type  = result_type(row);
 		var row_i = row_index( row );

 		var create = {

 			categories : function() {
 				
 				$.post(admg.ajax_url, { _ajax_nonce: admg.advert_nonce, action: 'admg_categories' }, function( options ) {
 					options = JSON.parse(options);
					set( create._dropdown( options, 'term_id', 'name' ) );
				});
 				
 			},

 			posts : function() {
 				
 				$.post(admg.ajax_url, { _ajax_nonce: admg.advert_nonce, action: 'admg_posts' }, function( options ) {
 					options = JSON.parse(options);
					set( create._dropdown( options, 'ID', 'post_title' ) );
				});
 				
 			},

 			pages : function() {
 				
 				$.post(admg.ajax_url, { _ajax_nonce: admg.advert_nonce, action: 'admg_pages' }, function( options ) {
 					options = JSON.parse(options);
					set( create._dropdown( options, 'ID', 'post_title' ) );
				});
 				
 			},

 			tags : function() {
 				
 				$.post(admg.ajax_url, { _ajax_nonce: admg.advert_nonce, action: 'admg_tags' }, function( options ) {
 					options = JSON.parse(options);
					set( create._dropdown( options, 'term_id', 'name' ) );
				});
 	
 			},

 			post_types : function() {
 				
 				$.post(admg.ajax_url, { _ajax_nonce: admg.advert_nonce, action: 'admg_post_types' }, function( options ) {
 					options = JSON.parse(options);
 					console.log(options);
					set( create._dropdown( options, 'name', 'name' ) );
				});
 	
 			},

 			date : function() {
 				set( create._input('text') ).datetimepicker({
					dayOfWeekStart : 1,
					lang:'en',
				});
 			},

 			text : function() {
 				set( create._input('text') );
 			},

 			_input : function(type) {
 				var input = document.createElement('input');
 				input.type = type;
 				return input;
 			},

 			_dropdown : function( options, val_prop, label_prop ) {

 				if ( options.length == 0 ) {
 					return create._input('text');
 				}

 				var select = document.createElement('select');
 				for( var i in options ) {
 					var option = document.createElement("option");
 					option.value = options[i][val_prop];
 					option.textContent = options[i][label_prop];
 					select.appendChild(option);
 				}

 				return select;
 				
 			},
 		}

 		function set( new_field ) {

 			if ( row_i < 0 )
 				row_i = 0;

 			// Bootstrap it ( Default class & name attributes )
 			new_field.className = new_field.className + " advert-field rule-result";
 			new_field.name = "rule[" + row_i + "][result]";

	 		// Apply it
	 		$(field).replaceWith( new_field );

	 		// Set its value
	 		if ( value !== 'undefined' )
	 			$(new_field).val( value );

	 		// Return the DOM element as reference
	 		return $(new_field);

 		}

 		// Defaults to 'text'
 		if ( typeof create[type] === undefined )
 			type = 'text';

 		// Disable the current field 
 		$(field).prop("disabled", true);

 		// Create and set the new field
 		if ( create.hasOwnProperty( type ) ) {
 			create[type]();
 		} else {
 			create.text();
 		}

 	}

 	// Returns the index of a row (for use in HTML's name attribute)
 	function row_index( row ) {
 		var rows = get_rows();
 		var index = -1;
 		$(rows).each( function(i) {
 			if ( $(this).is( row ) )
 				index = i;
 		});
 		return index;
 	}

 	// Returns all the rows
 	function get_rows( within ) {
 		if ( !$(within) ) {
 			within = advert_rules;
 		}
 		return $(within).find(".rule");
 	}

 	// Remove's the passed row
 	function remove_row(row) {

 		var group = $(row).closest(".rule-group");
 		var siblings = get_rows( group );

 		// Remove the row
 		$(row).remove();

 		// Remove the group (If empty) 		
 		if ( siblings.length === 1 ) {
 			$(group).remove();
 		}
 	}

 	// Adds a row
	function add_row( to, cb ) {

		disable_rules();

		$.ajax({
			method: "POST",
			url: admg.ajax_url,
			data: { 
				_ajax_nonce: admg.advert_nonce,
				action: "admg_advert_rule_markup",
				index: get_rows().length
			}
		})
		.success( function( data ) {

			$(to).append(data);
			
			if ( cb ) {
				// Call the callback, pass the new row as reference
				var rows = get_rows(to);
				cb( rows[ rows.length - 1 ] );
			}
			
			enable_rules();

		});
		
	}

	// Sets the rule panel to ".loading"
	function disable_rules() {
		$(advert_rules).closest(".main").addClass("loading");
	}

	// Remove the .loading class
	function enable_rules() {
		$(advert_rules).closest(".main").removeClass("loading");
	}

	// Returns all the groups
 	function get_groups() {
 		return $(advert_rules).find(".rule-group");
 	}

	// Adds a group
	function add_group() {

		// Generate an index for the group ($rule::parent)
		var index = 0;
		var groups = get_groups();
		$(groups).each( function() {
			if ( $(this).data("group") >= index ) {
				index = $(this).data("group") + 1;
			}
		});

		var markup = '\
		<table class="form-table rule-group" data-group="' + index + '">\
		    <tbody>\
		    	\
		   	</tbody>\
		</table>';

		// Append it
		$(advert_rules).append(markup);
		var group = $(advert_rules).children(".rule-group").last();

		var to = $(group).find("tbody");
		add_row( to,  function( row ) {
 			bind_row(row);
 		} );

	}

	// Sets the fields within a row and binds their events
	function bind_row( row ) {

		var group = $(row).closest(".rule-group").data("group");

		set_dropdowns( row );
		set_dropdowns( row );
 		set_result( row );

 		$(row).find(".rule-parent").val( group );

		$(row).find("select").change( function() {
 			set_dropdowns( $(this).closest(".rule") );
 			set_result( row );
 		});

		$(row).find(".rule-remove").click( function() {
 			remove_row( $(this).closest(".rule") );
 		});

 		$(row).find(".rule-add").click( function() {
	 		var to = $(this).closest("tbody");
	 		add_row( to, function( row ) {
	 			bind_row(row);
	 		});
	 	});

	}

 	//
 	//	Main
 	//

 	$(advert_rules).find(".rule").each( function() {
 		bind_row(this);
 	});

 	$(".group-add").click( function() {
 		add_group();
 	});
});


