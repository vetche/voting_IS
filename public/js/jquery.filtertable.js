/**
 * jquery.filterTable
 *
 * This plugin will add a search filter to tables. When typing in the filter,
 * any rows that do not contain the filter will be hidden.
 *
 * Utilizes bindWithDelay() if available. https://github.com/bgrins/bindWithDelay
 *
 * @version v1.5.4
 * @author Sunny Walker, swalker@hawaii.edu
 * @license MIT
 * //modified
 */

(function( $ ){
	String.prototype.removeAccents = function(){
		return this
			.replace( /[áàãâä]/gi, "a" )
			.replace( /[éè¨êě]/gi, "e" )
			.replace( /[íìïî]/gi, "i" )
			.replace( /[óòöôõ]/gi, "o" )
			.replace( /[úùüûů]/gi, "u" )
			.replace( /[ý]/gi, "y" )
			.replace( /[çč]/gi, "c" )
			.replace( /[ď]/gi, "d" )
			.replace( /[ĺľ]/gi, "l" )
			.replace( /[ñň]/gi, "n" )
			.replace( /[ŕř]/gi, "r" )
			.replace( /[š]/gi, "s" )
			.replace( /[ť]/gi, "t" )
			.replace( /[ž]/gi, "z" )
	};

	//var jversion = $.fn.jquery.split('.'), jmajor = parseFloat(jversion[0]), jminor = parseFloat(jversion[1]);
	//if (jmajor<2 && jminor<8) { // build the pseudo selector for jQuery < 1.8
	//    $.expr[':'].filterTableFind = function(a, i, m) { // build the case insensitive filtering functionality as a pseudo-selector expression
	//        return $(a).text().removeAccents().toLowerCase().indexOf(m[3].removeAccents().toLowerCase())>=0;
	//    };
	//} else { // build the pseudo selector for jQuery >= 1.8
	$.expr[ ':' ].filterTableFind = jQuery.expr.createPseudo( function( arg ){
		return function( el ){
			var elContent = $( el ).text().removeAccents().toLowerCase();
			var searchExpr = arg.removeAccents().toLowerCase();
			var searchParts = searchExpr.split( ' ' );
			var selectedTags = $( '.input-group-addon' );

			//filter rows by selected tags
			if( selectedTags.length > 0 ){
				var pointTags = $( el ).siblings().andSelf().find( 'small' );

				if( pointTags.length == 0 ){
					return false;
				}

				var matchAllTags = true;
				$.each( selectedTags, function( key, tag ){
					if( pointTags.text().removeAccents().toLowerCase().indexOf( tag.id ) == -1 ){
						matchAllTags = false;
						return false;
					}
				} );

				if( !matchAllTags ) return false;
			}

			if( searchParts.length > 1 ){
				var matchAllParts = true;
				$.each( searchParts, function( key, part ){
					if( elContent.indexOf( part ) == -1 ){
						matchAllParts = false;
						return false;
					}
				} );

				if( !matchAllParts ) return false;
			} else {
				return elContent.indexOf( searchExpr ) >= 0;
			}

			return true;
		};
	} );
	//}

	$.fn.filterTable = function( options ){ // define the filterTable plugin
		var defaults = { // start off with some default settings
			    autofocus: true,               // make the filter input field autofocused (not recommended for accessibility)
			    callback: null,                // callback function: function(term, table){}
			    containerClass: 'col-sm-12 input-group',      // class to apply to the container
			    containerTag: 'div',               // tag name of the container
			    hideTFootOnFilter: false,               // if true, the table's tfoot(s) will be hidden when the table is filtered
			    highlightClass: 'alt',               // class applied to cells containing the filter term
			    inputSelector: null,                // use the element with this selector for the filter input field instead of creating one
			    inputName: '',                  // name of filter input field
			    inputType: 'search',            // tag name of the filter input tag
			    inputClass: 'form-control',      // class of the filter input tag
			    label: 'Search:',           // text to precede the filter input tag
			    labelClass: 'control-label',     // class of the label tag
			    minRows: 8,                   // don't show the filter on tables with less than this number of rows
			    placeholder: '',                  // HTML5 placeholder text for the filter field
			    preventReturnKey: true,                // prevent the return key in the filter input field from trigger form submits
			    quickList: [],                  // list of phrases to quick fill the search
			    quickListClass: 'quick label label-info',             // class of each quick list item
			    quickListGroupTag: 'div',                  // tag surrounding quick list items (e.g., ul)
			    quickListTag: 'a',                 // tag type of each quick list item (e.g., a or li)
			    visibleClass: 'visible'            // class applied to visible rows
		    },
		    hsc = function( text ){ // mimic PHP's htmlspecialchars() function
			    return text.replace( /&/g, '&amp;' ).replace( /"/g, '&quot;' ).replace( /</g, '&lt;' ).replace( />/g, '&gt;' );
		    },
		    settings = $.extend( {}, defaults, options ); // merge the user's settings into the defaults

		var doFiltering = function( table, q ){ // handle the actual table filtering
			q = q.trim();
			var tbody = table.find( 'tbody' ); // cache the tbody element
			if( q === '' && $( '.input-group-addon' ).length == 0 ){ // if the filtering query is blank
				tbody.find( 'tr' ).show().addClass( settings.visibleClass ); // show all rows
				tbody.find( 'td' ).removeClass( settings.highlightClass ); // remove the row highlight from all cells
				if( settings.hideTFootOnFilter ){ // show footer if the setting was specified
					table.find( 'tfoot' ).show();
				}
			} else { // if the filter query is not blank
				tbody.find( 'tr' ).hide().removeClass( settings.visibleClass ); // hide all rows, assuming none were found
				if( settings.hideTFootOnFilter ){ // hide footer if the setting was specified
					table.find( 'tfoot' ).hide();
				}
				tbody.find( 'td' ).removeClass( settings.highlightClass ).filter( ':filterTableFind("' + q.replace( /(['"])/g, '\\$1' ) + '")' ).addClass( settings.highlightClass ).closest( 'tr' ).show().addClass( settings.visibleClass ); // highlight (class=alt) only the cells that match the query and show their rows
			}
			if( settings.callback ){ // call the callback function
				settings.callback( q, table );
			}
		}; // doFiltering()

		return this.each( function(){
			var t = $( this ), // cache the table
			    tbody = t.find( 'tbody' ), // cache the tbody
			    container = null, // placeholder for the filter field container DOM node
			    quicks = null, // placeholder for the quick list items
			    filter = null, // placeholder for the field field DOM node
			    created_filter = true; // was the filter created or chosen from an existing element?
			if( t[ 0 ].nodeName === 'TABLE' && tbody.length > 0 && (settings.minRows === 0 || (settings.minRows > 0 && tbody.find( 'tr' ).length > settings.minRows)) && !t.prev().hasClass( settings.containerClass ) ){ // only if object is a table and there's a tbody and at least minRows trs and hasn't already had a filter added
				if( settings.inputSelector && $( settings.inputSelector ).length === 1 ){ // use a single existing field as the filter input field
					filter = $( settings.inputSelector );
					container = filter.parent(); // container to hold the quick list options
					created_filter = false;
				} else { // create the filter input field (and container)
					container = $( '<' + settings.containerTag + ' />' ); // build the container tag for the filter field
					if( settings.containerClass !== '' ){ // add any classes that need to be added
						container.addClass( settings.containerClass );
					}
					//container.prepend(settings.label+' '); // add the label for the filter field
					filter = $( '<input type="' + settings.inputType + '" placeholder="' + settings.placeholder + '" name="' + settings.inputName + '" class="' + settings.inputClass + '" />' ); // build the filter field
					if( settings.preventReturnKey ){ // prevent return in the filter field from submitting any forms
						filter.on( 'keydown', function( ev ){
							if( (ev.keyCode || ev.which) === 13 ){
								ev.preventDefault();
								return false;
							}
						} );
					}
				}
				if( settings.autofocus ){ // add the autofocus attribute if requested
					filter.attr( 'autofocus', true );
				}
				if( $.fn.bindWithDelay ){ // does bindWithDelay() exist?
					filter.bindWithDelay( 'keyup', function(){ // bind doFiltering() to keyup (delayed)
						doFiltering( t, $( this ).val() );
					}, 200 );
				} else { // just bind to onKeyUp
					filter.bind( 'keyup', function(){ // bind doFiltering() to keyup
						doFiltering( t, $( this ).val() );
					} );
				} // keyup binding block
				filter.bind( 'click search', function(){ // bind doFiltering() to click and search events
					doFiltering( t, $( this ).val() );
				} );
				if( created_filter ){ // add the filter field to the container if it was created by the plugin
					container.append( filter );
				}
				if( settings.quickList.length > 0 ){ // are there any quick list items to add?
					quicks = settings.quickListGroupTag ? $( '<' + settings.quickListGroupTag + ' />' ) : container;
					$.each( settings.quickList, function( index, value ){ // for each quick list item...
						var q = $( '<' + settings.quickListTag + ' class="' + settings.quickListClass + '" />' ); // build the quick list item link
						q.text( hsc( value ) ); // add the item's text
						if( q[ 0 ].nodeName === 'A' ){
							q.attr( 'href', '#' ); // add a (worthless) href to the item if it's an anchor tag so that it gets the browser's link treatment
						}
						q.bind( 'click', function( e ){ // bind the click event to it
							e.preventDefault(); // stop the normal anchor tag behavior from happening
							var filterId = value.removeAccents().toLowerCase();
							if( $( '#' + filterId ).length == 0 && $( '.input-group-addon' ).length < 10 ){ //we won't allow the same tag to be used twice or more than 10 tags at the same time
								var filterTag = $( '<span/>', {
									id: filterId,
									class: 'input-group-addon',
									text: value,
									style: 'cursor:pointer',

									click: function(){
										filterTag.remove();
										filter.focus().trigger( 'click' );
									},
									mouseenter: function(){
										var origWidth = filterTag.width();
										filterTag.html( '<span class="glyphicon glyphicon-remove"></span>' );
										filterTag.outerWidth( origWidth );
									},
									mouseleave: function(){
										filterTag.html( value );
									}
								} );

								filter.after( filterTag );
								//filter.val(value).focus().trigger('click'); // send the quick list value over to the filter field and trigger the event
								filter.focus().trigger( 'click' ); // send the quick list value over to the filter field and trigger the event
							}
						} );
						quicks.append( q ); // add the quick list link to the quick list groups container
					} ); // each quick list item
					//if (quicks!==container) {
					//    container.append(quicks); // add the quick list groups container to the DOM if it isn't already there
					//}
				} // if quick list items
				if( created_filter ){ // add the filter field and quick list container to just before the table if it was created by the plugin
					t.before( container );
					container.before( '<label class="' + settings.labelClass + '">' + settings.label + '</label>' );
					if( quicks !== null && quicks !== container ){
						container.after( quicks ); // add the quick list groups container to the DOM if it isn't already there
					}
				}
			} // if the functionality should be added
		} ); // return this.each
	}; // $.fn.filterTable
})( jQuery );
