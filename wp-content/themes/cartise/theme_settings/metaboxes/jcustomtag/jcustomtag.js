(function($) {

	$.fn.jcustomtag = function( options ) {		

		var _this = this,

			$_this = $(_this),

			_options = {

				placeholder : 'Enter tags ...',
				addTagButton : {

					id : 'btnTagAdder',
					class : 'btn btn-success btn-sm mtop10',
					name : 'btnTagAdder',
					text : 'Add tag'

				},

				src : {

					ajax : {}

				},

				array_tags_init: []

			},

			_src_tagsList = [], // mảng lưu trữ danh sách tag từ database
			tagsList = [], // mảng lưu trữ tag đã nhập

			bodauTiengViet = function(str) {

		        str = str.toLowerCase();
		        str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
		        str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
		        str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
		        str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
		        str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
		        str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
		        str = str.replace(/đ/g, "d");
		        return str;

			},

			renderTagsList = function( _tagsList ) {				

				$.each( _tagsList, function(i, tag_value) {	

					if ( tag_value !== '' ) {

						hideTagInput( tag_value ); // ẩn tag vừa nhập trong danh sách dropdown

						appendTagDOMToTList( tag_value );

						updateMetaTags();

					}						

				});				

			},

			// ẩn tag vừa nhập vào trong danh sách dropdown
			hideTagInput = function( tag_value ) {

				var $tagBox = $_this.parent();

					$_src_tagsList = $tagBox.find('._src_tagsList'),
					$_src_t = $_src_tagsList.find("li[data-value='" + tag_value + "']");

				if ( $_src_t.length > 0 && ! $_src_t.hasClass('hidden') ) {

					$_src_t.addClass('hidden');

				}

			},

			appendTagDOMToTList = function( tag_value ) {

				var $tagBox = $_this.parent(),
					$tagsList = $tagBox.find('.tagsList');

				$tagsList.append( ["<span class='tag'>",
								   		tag_value,
								  "		<button type='button' class='close'>×</button>",
								  "</span>"].join('') );

				$tagsList.find('.tag:last-child')
						 .data('tag', tag_value);

			},

			resetMetaTags = function() {

				$_this.val('');

			},

			updateMetaTags = function() {

				var $tagsInput = $_this;

				if ( $tagsInput.length > 0 ) {

					$tagsInput.val( tagsList.join(",") );

				}

			},

			generateTagError = function(msg) {

				var $tagBox = $_this.parent(),
					$tagErrorBox = $tagBox.prev('.tagErrorBox');

				if ( $tagErrorBox.length === 0 ) {

					$("<div class='tagErrorBox'></div>").insertBefore( $tagBox );

					$tagErrorBox = $tagBox.prev('.tagErrorBox');

				}

				$tagErrorBox.html( msg );

			},		

			onAddTagClick = function(e) {

				e.preventDefault();

				var $tagBox = $_this.parent(),
					$tagInput = $tagBox.find('._txtTagInput'),								

					tag_index = -1,

					value = $tagInput.val().toString().trim();

				if ( value !== '' ) {

					tag_index = tagsList.findIndex(function(e) { return e.toString() === value });

					// tag chưa tồn tại thì mới thêm ?
					// tag không phân biệt hoa thường
					if ( tag_index === -1 ) {

						hideTagInput( value );

						appendTagDOMToTList( value );

						tagsList.push( value );

						updateMetaTags();
						
						generateTagError( "" );

					}

					else {

						generateTagError( "Tag đã được thêm vào, mời chọn tag khác." );


					}

				}

				else {

					generateTagError( "Mời nhập nội dung cho tag." );


				}

				$tagInput.val('')
						 .focus();

			},

			onTagHintClick = function(e) {

				e.preventDefault();

				var $tagBox = $_this.parent(),
					$_src_tagsList = $tagBox.find('._src_tagsList');

				$tagBox.find('._txtTagInput')
					   .val( $(this).closest('li').data('value').toString() );

				onAddTagClick(e);

				$_src_tagsList.removeClass('active');

			},

			setupTagsListObj = function( _src_tagsList, _tags_init, action ) {

				var $tagName = $_this,	
					$tagBox = $tagName.parent();			

				if ( Object.keys( _src_tagsList ).length > 0 ) {

					var $_src_tagsList = $tagBox.find('._src_tagsList'),
						$tagsList = $tagBox.find('.tagsList');

					if ( $_src_tagsList.length === 0 ) {

						$_src_tagsList = $("<ul class='_src_tagsList dropdown-menu'></ul>");
			 			$_src_tagsList.appendTo( $tagBox );

			 		}

			 		$_src_tagsList.html('');
			 		$tagsList.html('');

			 		$.each( _src_tagsList, function(i, tag) {

			 			var html = ["<li data-value='{tag_name}'>",
			 						"	<a href='#'>{tag_name}</a>",
			 						"</li>"].join('');

			 			html = html.replace(/\{tag_name\}/ig, tag['name']);

			 			$_src_tagsList.append( html );

			 		});	

			 		if ( action !== 'reinit' ) {

				 		if ( _tags_init.length > 0 ) {

				 			renderTagsList( _tags_init );

				 		}

				 	}

				 	else {

				 		tagsList = [];

				 		resetMetaTags();

				 	}

			 		$_src_tagsList.find('a')
			 					  .unbind('click', onTagHintClick)
			 					  .bind( 'click', onTagHintClick );
				 	

			 	}

			},

			_jcustomtag = {

				$tagName : $_this,

				ready: function() {

					var $tagName = this.$tagName,	
						$tagBox = null,

						tagFocus = function() {

						},

						tagUnfocus = function() {							

						},

						onTagKeyUp = function(e) {

							var event = e || window.event,
								$_src_tagsList = $tagBox.find('._src_tagsList');

							// enter key
							if ( event.which === 13 ) {

								event.preventDefault();

							}

							// esc key
							else if ( event.which === 27 ) {

								// ẩn danh sách source tag
								if ( $_src_tagsList.length > 0 && $_src_tagsList.hasClass('active') ) {

									$_src_tagsList.removeClass('active')

								}

							} 

							else {

								if ( $_src_tagsList.length > 0 ) {

									var value =  $(this).val();

									if ( value !== '' ) {

										value = bodauTiengViet( value );

										var	_tag_typehead = _src_tagsList.filter(function(v) {

											var t_v = bodauTiengViet( v['name'] );

											return t_v.indexOf( value ) !== -1;

										});

										if ( _tag_typehead.length > 0 ) {

											$_src_tagsList.find('li')
														  .each(function(j, e) {

												var tag_value = $(e).data('value').toString(),
													_tag_value = bodauTiengViet( tag_value );

												//console.log( _tag_value + '-' + value );

												// ẩn những tag element không chứa value
												if ( _tag_value.indexOf( value ) === -1 ) {

													if ( ! $(e).hasClass('hidden') ) {

														$(e).addClass('hidden');

													}

												}

												else {													

													// tag_value đã được thêm trước đó ?
													if ( tagsList.indexOf( tag_value ) !== -1 ) {

														// ẩn tag này đi
														if ( ! $(e).hasClass('hidden') ) {

															$(e).addClass('hidden');

														}

													}

													else {

														// hiện tag này lên
														if ( $(e).hasClass('hidden') ) {

															$(e).removeClass('hidden');

														}

													}

												}

												if ( ! $_src_tagsList.hasClass('active') ) {

													var length = $_src_tagsList.find('li:not(.hidden)').length;

													if ( length > 0 ) {

														$_src_tagsList.addClass('active');

													}

												}

											});

										}

										else {

											if ( $_src_tagsList.hasClass('active') ) {

												$_src_tagsList.removeClass('active');

											}

										}

									}

									else {

										if ( $_src_tagsList.hasClass('active') ) {

											$_src_tagsList.removeClass('active');

										}

									}

								}

							}

						},

						onTagClickOutside = function(e) {

							var container = $tagName.closest('.tags')
													.find('._src_tagsList');

						    // if the target of the click isn't the container nor a descendant of the container
						    if ( ! container.is(e.target) && 
						    	 container.has(e.target).length === 0) {

						        container.removeClass('active');

						    }

						},			

						onTagRemoveClick = function(e) {

							e.preventDefault();

							var $tag = $(this).closest('span.tag'),							

								$_src_tagsList = $tagBox.find('._src_tagsList');

								tag_name = $tag.data('tag'),

								$_tag = $_src_tagsList.find("li[data-value='" + tag_name + "']"),

								tag_index = tagsList.findIndex(function(v) { return v === tag_name });

							$tag.remove();

							tagsList.splice(tag_index, 1);	

							// bỏ ẩn tag trong danh sách xổ xuống nếu có
							if ( $_tag.length > 0 && $_tag.hasClass('hidden') ) {

								$_tag.removeClass('hidden');

							}

							updateMetaTags();

						};
						

					$.extend( _options, options );

					$tagName.addClass('hidden')
							.wrap('<div class="tags"></div>');

					$tagBox = $tagName.parent();

					$tagBox.append("<div class='tagsList'></div>")
						   .append("<input class='_txtTagInput' type='text' placeholder='" + _options['placeholder'] + "'>")						   
						   .on('focus', '._txtTagInput', tagFocus)
						   .on('blur', '._txtTagInput', tagUnfocus)
						   .on('keyup', '._txtTagInput', onTagKeyUp)
						   .on('click touchstart', '.close', onTagRemoveClick);

					$(document).on('mouseup touchstart', onTagClickOutside);

					var $ajaxTagBox = $tagName.closest('.field').find('.ajaxLoadTags');

					$ajaxTagBox.addClass('active');

					setTimeout(function() {

						if ( Object.keys( _options['src']['ajax'] ).length > 0 ) {											

							$.ajax( _options['src']['ajax'] )
							 .done(function( data ) {

							 	_src_tagsList = data;

							 	tagsList = _options['array_tags_init'];	

							 	setupTagsListObj( _src_tagsList, tagsList, '' );

							 	$ajaxTagBox.removeClass('active');

							});						

						}

						else {

							tagsList = _options['array_tags_init'];							 		

					 		if ( tagsList.length > 0 ) {

					 			renderTagsList( tagsList );

					 		}

					 		$ajaxTagBox.removeClass('active');

						}

					}, 200);

				}

			};

			_jcustomtag.ready();		

			return {

				render: function() {

					renderTagsList( tagsList );
					
				},

				appendTag : function(tag) {

					tagsList.push( tag );

				},

				appendTagsList : function(tags) {

					$.each( tags, function(i, e) {

						tagsList.push( e );

					});

				},

				getTagsList : function() {

					return tagsList;

				},

				reloadWithNewAjax: function(ajax_settings) {

					var $ajaxTagBox = $_this.closest('.field').find('.ajaxLoadTags');

					$ajaxTagBox.addClass('active');

					setTimeout(function() {

						$.ajax( ajax_settings )
						 .done(function(data) {

						 	setupTagsListObj( data, [], 'reinit' );

						 	$ajaxTagBox.removeClass('active');

						});

					}, 200);

				}

			}

	};

}(jQuery))