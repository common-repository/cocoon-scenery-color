( function( $, undefined ) {

	const KEY_COLOR_NAME = 'color_name';
	const KEY_ADD_STRING = '_new';
	const KEY_ADD_COPY_HEX = '_hex';
	const KEY_OVERWRITE_COLOR_FILE = 'overwrite_color_file';
	const KEY_COLOR_ONLY_SAVE = 'color_only_save';
	const KEY_SAMPLE_COLOR = 'sample_color';
	const KEY_SAMPLE_COLORS = 'sample_color_';
	const KEY_SAMPLE_TEXT = 'sample_text_';
	const KEY_SAMPLE_HEX = 'sample_hex_';
	const KEY_SAMPLE_BACKGROUND = 'background';
	const KEY_SAMPLE_FONT = 'font';
	const TYPE_HUE = 'hue_';
	const TYPE_SAT = 'sat_';
	const TYPE_VAL = 'val_';
	const SELECT_COLOR = 'select_color_theme';
	const CURRENT_COLOR_NAME = 'current_color_name';
	const CURRENT_COLOR_INDEX = 'current_color_index';
	const CURRENT_DARKMODE_NAME = 'current_darkmode_name';
	const CURRENT_MAINTENANCE_NAME = 'current_maintenance_name';
	// input
	const INPUT_TYPE_ATTR_TEXT = 'text';
	const INPUT_TYPE_ATTR_SELECT = 'select';
	const INPUT_TYPE_ATTR_COLOR = 'color';
	// element id
	const ELEMENT_ID_DEFAULT_JSON_DATA = 'default_json_data';
	const ELEMENT_ID_JSON_DATA = 'json-data';
	const ELEMENT_ID_MO_DATA = 'mo-data';
	const ELEMENT_ID_COLOR_PREVIEW = 'color_preview';
	const ELEMENT_ID_COLOR_PREVIEW_SPAN = 'color_preview_span';
	const ELEMENT_ID_SLUGMODE_FILES = 'slugmode-file';
	const FORM_ID_FORM_COLOR_CREATE = 'form_color_create';
	const CHECKBOX_ID_CHECKBOX_ALL_CHECK = 'checkbox_all_check';
	const CHECKBOX_NAME_NO_VALUE_CHECKBOXES = 'no_value_checkboxes';
	const CHECKBOX_NAME_NO_VALUE_CHECKBOXES_ARRAY = CHECKBOX_NAME_NO_VALUE_CHECKBOXES + '[]';
	const CHECKBOX_DARKMODE_USE = 'checkbox_darkmode_use';
	const CHECKBOX_SLUGMODE_USE = 'checkbox_slugmode_use';
	const CHECKBOX_MAINTENANCE_USE = 'checkbox_maintenance_use';
	const CHECKBOX_COLOR_ONLY_SAVE   = 'checkbox_color_only_save';
	const CHECKBOX_SAMPLE_LOCK = 'checkbox_sample_lock';
	const CHECKBOX_THREE_ELEMENTS = 'checkbox_three_elements';
	const CHECKBOX_MODE_SETTING_VISIBLED = 'checkbox_mode_setting_visibled';
	// class
	const CLASS_COPYALLCOLOR = 'copyAllColor';
	const CLASS_COPYSELECTOPTION = 'copySelectOption';
	const CLASS_COPYTEXT = 'copyText';
	const CLASS_COPYCOLOR = 'copyColor';
	const CLASS_COLORPALETTE = 'colorPalette';
	const CLASS_COPY_SAMPLE_COLOR = 'copySampleColor';
	const CLASS_SAMPLE_COLORPALETTE = 'sampleColorPalette';
	const CLASS_POSTBOX = 'postbox';
	const CLASS_CSCP_FORM_TABLE = 'cscp-form-table';
	const CLASS_COLOR_PREVIEW_TABLE = 'color-preview-table';
	const CLASS_NOT_COLOR = 'not_color';
	const CLASS_SAMPLE_HEX = 'sample_hex';
	const CLASS_COLOR_VISIBLED_HUE = 'visibled_hue';
	const CLASS_COLOR_VISIBLED_SAT = 'visibled_sat';
	const CLASS_COLOR_VISIBLED_VAL = 'visibled_val';
	const CLASS_NOT_COLOR_VISIBLED = 'visibled_not_color';
	const CLASS_MODE_SETTING_VISIBLED = 'mode_setting_visibled';
	// Hidden
	const HIDDEN_MODE_SETTING_VISIBLED = 'hidden_mode_setting_visibled';
	// Button
	const BUTTON_SITE_COLOR_CHANGE = 'submit_button_site_change';
	const BUTTON_COLOR_DELETE = 'submit_button_delete';
	const BUTTON_COLOR_CREATE = 'submit_button_create';
	const BUTTON_COLOR_DARKMODE = 'submit_button_darkmode';
	const BUTTON_COLOR_MAINTENANCE = 'submit_button_maintenance';
	const BUTTON_COLOR_SLUGMODE = 'submit_button_slugmode';
	const BUTTON_COPYALLCOLOR = 'button_copyallcolor';
	// Radio
	const RADIO_NAME_SAMPLE_APPLY = 'radio_sample_apply';
	const RADIO_VALUE_SAMPLE_APPLY_ALL = 'all';
	const RADIO_VALUE_SAMPLE_APPLY_OUTSIDE = 'outside';
	const RADIO_VALUE_SAMPLE_APPLY_INSIDE = 'inside';
	const RADIO_VALUE_SAMPLE_APPLY_CLEAR = 'clear';
	const RADIO_NAME_SAMPLE_COLORS = 'radio_sample_colors';
	const RADIO_NAME_SAMPLE_COLORS16_HUE = 'radio_sample_colors16_hue';
	const RADIO_NAME_SAMPLE_COLORS16_SAT = 'radio_sample_colors16_sat';
	const RADIO_NAME_SAMPLE_COLORS16_VAL = 'radio_sample_colors16_val';
	const RADIO_VALUE_SAMPLE_COLORS_BACKGROUND = 'background';
	const RADIO_VALUE_SAMPLE_COLORS_FONT = 'font';
	const RADIO_VALUE_SAMPLE_COLORS16 = 'colors16_';
	const RADIO_NAME_THREE_ELEMENTS = 'radio_three_elements';
	const RADIO_VALUE_THREE_ELEMENTS_HUE = 'hue';
	const RADIO_VALUE_THREE_ELEMENTS_SAT = 'sat';
	const RADIO_VALUE_THREE_ELEMENTS_VAL = 'val';
	const RADIO_VALUE_THREE_ELEMENTS_ALL = 'all';
	// ドロップダウンリスト
	const TEXT_SELECT = translateText('カラーを選択してください');
	// 投稿、固定ページ、カテゴリーのファイル接頭辞
	const PREFIX_POST = 'post-';
	const PREFIX_PAGE = 'page-';
	const PREFIX_CATEGORY = 'cate-';
	// カラー
	const COLOR_WHITE = '#ffffff';
	const COLOR_BLACK = '#000000';
	// CSSプロパティ
	const CSS_PROPERTY_BACKGROUND_COLOR = 'background-color';
	const CSS_PROPERTY_COLOR = 'color';
	const VERIFY_NONCE_STRING = 'cscp-option';
	// 色相環
	const degree_array = [ 0, 15, 36, 45, 60, 72, 90, 108, 120, 135, 144, 165, 180, 195, 216, 225, 240, 252, 270, 288, 300, 315, 324, 345 ];
	// 彩度
	const saturation_percent_array = [ 0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100 ];
	// 明度
	const value_percent_array = [ 100, 95, 90, 85, 80, 75, 70, 65, 60, 55, 50, 45, 40, 35, 30, 25, 20, 15, 10, 5, 0 ];

	function selectColorChange() {
		const $default_elem = $('#' + ELEMENT_ID_DEFAULT_JSON_DATA);
		const default_data = $.parseJSON( unescapeHTML($default_elem.text()) );
		const $data_elem = $('#' + ELEMENT_ID_JSON_DATA);
		const color_data = $.parseJSON( unescapeHTML($data_elem.text()) );
		const emptyString = '';
		const $slugmode_elem = $('#' + ELEMENT_ID_SLUGMODE_FILES);
		const slugmode_files = $.parseJSON( unescapeHTML($slugmode_elem.text()) );

		var $selectElem = $('#' + SELECT_COLOR);
		var index = $selectElem.prop('selectedIndex');
		var value;
		var current_color_name = color_data[index].color_name;

		for ( let i = 0; i < default_data.length; i++ ) {
			if ( default_data[i].id in color_data[index].json_data ) {
				value = color_data[index].json_data[default_data[i].id] ??= emptyString;
			} else {
				value = '';
			}
			if ( default_data[i].attr === INPUT_TYPE_ATTR_COLOR ) {
				$('#' + default_data[i].id).css(CSS_PROPERTY_BACKGROUND_COLOR, value);
			} else {
				$('#' + default_data[i].id).attr('value', value);
			}
		}

		$('#' + ELEMENT_ID_COLOR_PREVIEW_SPAN + ' p').remove();
		var srcFile = '';
		var altText = translateText('プレビュー画像がありません');
		var previewElem = $('#' + ELEMENT_ID_COLOR_PREVIEW);
		if ( color_data[index].preview_file !== '' ) {
			srcFile = color_data[index].preview_file;
			altText = '';
			$('#' + ELEMENT_ID_COLOR_PREVIEW_SPAN).append('<p>' + translateText('SILK（シルク）スキンで提供されているカラーファイルを元にしています。') + '</p>');
		}
		previewElem.prop('src' ,srcFile);
		previewElem.prop('alt' ,altText);


		$('input:hidden[name=' + CURRENT_COLOR_NAME + ']').val(current_color_name);

		set_button_disabled_prop( BUTTON_SITE_COLOR_CHANGE, false );
		set_button_disabled_prop( BUTTON_COLOR_DARKMODE, false );
		set_button_disabled_prop( BUTTON_COLOR_MAINTENANCE, false );
		set_button_disabled_prop( BUTTON_COLOR_CREATE, false );
		set_button_disabled_prop( BUTTON_COPYALLCOLOR, false );

		// [カラーの削除]ボタン
		var overwrite = false;
		if ( KEY_OVERWRITE_COLOR_FILE in color_data[index].json_data ) {
			overwrite = color_data[index].json_data[KEY_OVERWRITE_COLOR_FILE] ??= false;
		}
		set_button_disabled_prop( BUTTON_COLOR_DELETE, ! overwrite );

		var select_option = $selectElem.children('option');
		if ( select_option[select_option.length - 1].innerText === TEXT_SELECT ) {
			$selectElem.children('option:last-child').remove();
		}

		// ダークモード用［適用する］チェックボックス
		$('#' + CHECKBOX_DARKMODE_USE).prop('checked', $('#' + CURRENT_DARKMODE_NAME).attr('value') === current_color_name);
		// メンテナンスモード用［使用する］チェックボックス
		$('#' + CHECKBOX_MAINTENANCE_USE).prop('checked', $('#' + CURRENT_MAINTENANCE_NAME).attr('value') === current_color_name);

		// ［色情報のみ保存する］チェックボックス
		// （KEY_COLOR_ONLY_SAVE項目はjson_dataに必ず含まれている仕様）
		$('#' + CHECKBOX_COLOR_ONLY_SAVE).prop('checked', Boolean(color_data[index].json_data[KEY_COLOR_ONLY_SAVE] ??= false));
		// ［色情報のみ保存する］チェックボックスがチェック状態の時は、カラー以外をdisabled状態とする
		checkedColorOnlySave();

		// スラッグモード用カラーファイルを選択した場合はスラッグモード用ボタンを有効とする
		// （カラー名の接頭辞がスラッグモード用接頭辞に一致している場合はスラッグモード用ボタンを有効とする）
		var is_slug_file = current_color_name.startsWith(PREFIX_POST) || 
						   current_color_name.startsWith(PREFIX_PAGE) || 
						   current_color_name.startsWith(PREFIX_CATEGORY);
		set_button_disabled_prop( BUTTON_COLOR_SLUGMODE, ! is_slug_file );
		// スラッグモード用カラーファイルが存在している場合はスラッグモード用［使用する］チェックボックスをチェック状態とする
		var exist_slug_file = $.inArray(current_color_name, slugmode_files);
		$('#' + CHECKBOX_SLUGMODE_USE).prop('checked', -1 !== exist_slug_file );
	}

	function rgbToHexString( rgb ) {
		return rgb ? String('#' + rgb.match(/\d+/g).map(function(a){return ('0' + parseInt(a).toString(16)).slice(-2)}).join('')).substr(0, 7) : '#ffffff'
	}

	function set_button_disabled_prop( elementId, boolValue ) {
		$('#' + elementId).prop('disabled', boolValue);
	}

	function copyColor() {
		copyColorByElementId( $(this).attr('id') );
	}

	function toHex( dec ) {
	    return ('0' + dec.toString(16).toUpperCase()).substr(-2);
	}

	function rHextToDec( colorHex ) {
		var rHex = colorHex.slice(1, 3);
		return parseInt(rHex, 16);
	}

	function gHextToDec( colorHex ) {
		var gHex = colorHex.slice(3, 5);
		return parseInt(gHex, 16);
	}

	function bHextToDec( colorHex ) {
		var bHex = colorHex.slice(5, 7);
		return parseInt(bHex, 16);
	}

	function getComplementaryColorsString( _this ) {
		var colorHex = $(_this).val();
		var rDec = rHextToDec( colorHex );
		var gDec = gHextToDec( colorHex );
		var bDec = bHextToDec( colorHex );
		var maxDec = Math.max(rDec, gDec, bDec);
		var minDec = Math.min(rDec, gDec, bDec);
		var sumDec = (rDec === gDec && gDec === bDec) ? 255 : maxDec + minDec;
		rDec = sumDec - rDec;
		gDec = sumDec - gDec;
		bDec = sumDec - bDec;
		return '#' + toHex(rDec) + toHex(gDec) + toHex(bDec);
	}

	function getTitleRgbColor( colorHex ) {
		var rDec = rHextToDec( colorHex );
		var gDec = gHextToDec( colorHex );
		var bDec = bHextToDec( colorHex );
		return 'RGB( ' + String(rDec) + ', ' + String(gDec) + ', ' + String(bDec) + ' )';
	}

	function setTitleRgbColor( _this ) {
		var colorHex = $(_this).val();
		$(_this).attr('title', getTitleRgbColor( colorHex ));
	}

	function copyColorByElementId( elementId ) {
		if ( $('#' + BUTTON_COPYALLCOLOR).prop('disabled') ) return;

		const $data_elem = $('#' + ELEMENT_ID_JSON_DATA);
		const color_data = $.parseJSON( unescapeHTML($data_elem.text()) );
		const emptyString = '';
		var $selectElem = $('#' + SELECT_COLOR);
		var index = $selectElem.prop('selectedIndex');
		var value;

		$('#' + elementId + KEY_ADD_STRING).val(rgbToHexString($('#' + elementId).css(CSS_PROPERTY_BACKGROUND_COLOR)));
		value = color_data[index].json_data[elementId];
		$('input[name="' + CHECKBOX_NAME_NO_VALUE_CHECKBOXES_ARRAY + '"]').each( function(i) {
			if ( elementId === $(this).val() ) {
				$(this).prop('checked', emptyString === value);
				return false;
			}
		} );
		checkedColor();
	}

	function copyText() {
		copyTextByElementId( $(this).attr('id') );
	}

	function copyTextByElementId( elementId ) {
		$('#' + elementId + KEY_ADD_STRING).val($('#' + elementId).val());
	}

	function copySelectOption() {
		copySelectOptionByElementId( $(this).attr('id') );
	}

	function copySelectOptionByElementId( elementId ) {
		var $select = $('#' + elementId + KEY_ADD_STRING).children('option');
		for ( let i = 0; i < $select.length; i++ ) {
			if ( $select[i].value === $('#' + elementId).attr('value')) {
				$select[i].selected = true;
				break;
			}
		}
	}

	function copyAllColor() {
		const $default_elem = $('#' + ELEMENT_ID_DEFAULT_JSON_DATA);
		const default_data = $.parseJSON( unescapeHTML($default_elem.text()) );

		for ( let i = 0; i < default_data.length; i++ ) {
			var key_name = default_data[i].id;
			if ( default_data[i].attr === INPUT_TYPE_ATTR_COLOR ) {
				copyColorByElementId(key_name);
			} else if ( default_data[i].attr === INPUT_TYPE_ATTR_TEXT ) {
				copyTextByElementId(key_name);
			} else if ( default_data[i].attr === INPUT_TYPE_ATTR_SELECT ) {
				copySelectOptionByElementId(key_name);
			}
		}
	}

	function checkOnSubmitColorCreate() {
		return ( checkNewColorName() && checkOverwriteColorFile() );
	}

	function checkNewColorName() {
		var str = $('#' + KEY_COLOR_NAME + KEY_ADD_STRING).val();
		var resultNG = str.match( /^.*[(\\|/|:|\*|?|\"|<|>|\|)].*$/ );
		if( resultNG ) {
			alert(translateText('［カラーの名称］：ファイル名に指定できない文字が含まれています。'));
		}
		return ! resultNG;
	}

	function translateText( text ) {
		const $data_elem = $('#' + ELEMENT_ID_MO_DATA);
		const mo_data = $.parseJSON( unescapeHTML($data_elem.text()) );
		return mo_data[text] || text;
	}

	function escapeHTML( val ) {
		return $('<div>').text(val).html();
	};

	function unescapeHTML( val ) {
		return $('<div>').html(val).text();
	};

	function checkOverwriteColorFile() {
		const $data_elem = $('#' + ELEMENT_ID_JSON_DATA);
		const color_data = $.parseJSON( unescapeHTML($data_elem.text()) );
		var color_name = $('#' + KEY_COLOR_NAME + KEY_ADD_STRING).val();
		var overwrite = true;

		for ( let index = 0; index < color_data.length; index++ ) {
			if ( color_name === color_data[index].color_name ) {
				if ( KEY_OVERWRITE_COLOR_FILE in color_data[index].json_data ) {
					overwrite = Boolean(color_data[index].json_data[KEY_OVERWRITE_COLOR_FILE] ??= false);
				} else {
					overwrite = false;
				}
				break;
			}
		}
		if ( ! overwrite ) {
			alert(translateText('［カラーの名称］：カラーの原本は変更できません。名称を変更し新たなカラーとして作成してください。'));
		}
		return overwrite;
	}

	function checkedAllColor() {
		$('input[name="' + CHECKBOX_NAME_NO_VALUE_CHECKBOXES_ARRAY + '"]').prop('checked', this.checked);
	}

	function checkedColorOnlySave() {
		var $classElem = $('.' + CLASS_NOT_COLOR_VISIBLED);
		$('#' + CHECKBOX_COLOR_ONLY_SAVE).prop('checked') ? $classElem.hide() : $classElem.show();
	}

	function checkedModeSettingVisibled() {
		var modeSettingVisibled = $('#' + CHECKBOX_MODE_SETTING_VISIBLED).prop('checked');
		$('input:hidden[name="' + HIDDEN_MODE_SETTING_VISIBLED + '"]').val( modeSettingVisibled );
		$classElem = $('.' + CLASS_MODE_SETTING_VISIBLED);
		modeSettingVisibled ? $classElem.show() : $classElem.hide();
	}

	function checkedColor() {
		var $checkedCount = $('input[name="' + CHECKBOX_NAME_NO_VALUE_CHECKBOXES_ARRAY + '"]:checked').length;
		var $inputCount = $('input[name="' + CHECKBOX_NAME_NO_VALUE_CHECKBOXES_ARRAY + '"]:input').length;
		$('#' + CHECKBOX_ID_CHECKBOX_ALL_CHECK).prop('checked', $checkedCount === $inputCount);
	}

	function rgb2hsv ( r, g, b ) {
		r = r / 255;
		g = g / 255;
		b = b / 255;
		var max = Math.max( r, g, b );
		var min = Math.min( r, g, b );
		var diff = max - min;
		var h = 0;

		switch( min ) {
			case max : h = 0; break;
			case r   : h = (60 * ((b - g) / diff)) + 180; break;
			case g   : h = (60 * ((r - b) / diff)) + 300; break;
			case b   : h = (60 * ((g - r) / diff)) + 60;  break;
		}
		var s = max == 0 ? 0 : diff / max;
		var v = max;
		return { h: h, s: s, v: v };
	}

	function hsv2rgb( h, s, v ) {
	    h = h / 60;

	    var i = Math.floor(h),
	        f = h - i,
	        p = v * (1 - s),
	        q = v * (1 - f * s),
	        t = v * (1 - (1 - f) * s),
	        mod = i % 6,
	        r = [v, q, p, p, t, v][mod],
	        g = [t, v, v, q, p, p][mod],
	        b = [p, p, t, v, v, q][mod];

	    return { r: Math.round(r * 255), g: Math.round(g * 255), b: Math.round(b * 255) };
	}

	function hex2rgb ( hex ) {
		if ( hex.slice(0, 1) === "#" ) hex = hex.slice(1);
		if ( hex.length === 3 ) hex = hex.slice(0,1) + hex.slice(0,1) + hex.slice(1,2) + hex.slice(1,2) + hex.slice(2,3) + hex.slice(2,3);
		return [ hex.slice( 0, 2 ), hex.slice( 2, 4 ), hex.slice( 4, 6 ) ].map( function ( str ) {
			return parseInt( str, 16 );
		});
	}

	function hsv2rgbWithDegree( hsv, degree ) {
		var rgb = hsv2rgb( (hsv.h + degree) % 360, hsv.s, hsv.v );
		return '#' + toHex(rgb.r) + toHex(rgb.g) + toHex(rgb.b);
	}

	function hsv2rgbWithSaturation( hsv, saturation ) {
		var correctSaturation = (hsv.s * 100) % 5;
		correctSaturation = ( saturation === 100 && correctSaturation !== 0 ) ? 100 : correctSaturation + saturation;
		var rgb = hsv2rgb( hsv.h, correctSaturation / 100, hsv.v );
		return '#' + toHex(rgb.r) + toHex(rgb.g) + toHex(rgb.b);
	}

	function hsv2rgbWithValue( hsv, value ) {
		var correctValue = (hsv.v * 100) % 5;
		correctValue = ( value === 100 && correctValue !== 0 ) ? 100 : correctValue + value;
		var rgb = hsv2rgb( hsv.h, hsv.s, correctValue / 100 );
		return '#' + toHex(rgb.r) + toHex(rgb.g) + toHex(rgb.b);
	}

	function set16InputColorValue( index, color_value ) {
		$('#' + KEY_SAMPLE_COLORS + index).val( color_value );
	}

	function set16ColorsWithValue( colorHex ) {
		if ( $('#' + CHECKBOX_SAMPLE_LOCK).prop('checked') ) return;

		var rDec = rHextToDec( colorHex );
		var gDec = gHextToDec( colorHex );
		var bDec = bHextToDec( colorHex );
		var hsv = rgb2hsv ( rDec, gDec, bDec );
		var hex_array = $.map( value_percent_array, function( value, index ) {
				return hsv2rgbWithValue( hsv, value );
			});
		$.each( hex_array, function( index, value ) {
			$('#' + KEY_SAMPLE_COLORS + TYPE_VAL + (index + 1)).val( value );
			$('#' + KEY_SAMPLE_COLORS + TYPE_VAL + (index + 1)).attr('title', getTitleRgbColor( value ));
			$('#' + KEY_SAMPLE_HEX + TYPE_VAL + (index + 1)).val( value );
		});
	}

	function set16ColorsWithSaturation( colorHex ) {
		if ( $('#' + CHECKBOX_SAMPLE_LOCK).prop('checked') ) return;

		var rDec = rHextToDec( colorHex );
		var gDec = gHextToDec( colorHex );
		var bDec = bHextToDec( colorHex );
		var hsv = rgb2hsv ( rDec, gDec, bDec );
		var hex_array = $.map( saturation_percent_array, function( value, index ) {
				return hsv2rgbWithSaturation( hsv, value );
			});
		$.each( hex_array, function( index, value ) {
			$('#' + KEY_SAMPLE_COLORS + TYPE_SAT + (index + 1)).val( value );
			$('#' + KEY_SAMPLE_COLORS + TYPE_SAT + (index + 1)).attr('title', getTitleRgbColor( value ));
			$('#' + KEY_SAMPLE_HEX + TYPE_SAT + (index + 1)).val( value );
		});
	}

	function set16ColorsWithDegree( colorHex ) {
		if ( $('#' + CHECKBOX_SAMPLE_LOCK).prop('checked') ) return;

		var rDec = rHextToDec( colorHex );
		var gDec = gHextToDec( colorHex );
		var bDec = bHextToDec( colorHex );
		var hsv = rgb2hsv ( rDec, gDec, bDec );
		var hex_array = $.map( degree_array, function( value, index ) {
				return hsv2rgbWithDegree( hsv, value );
			});
		$.each( hex_array, function( index, value ) {
			$('#' + KEY_SAMPLE_COLORS + TYPE_HUE + (index + 1)).val( value );
			$('#' + KEY_SAMPLE_COLORS + TYPE_HUE + (index + 1)).attr('title', getTitleRgbColor( value ));
			$('#' + KEY_SAMPLE_HEX + TYPE_HUE + (index + 1)).val( value );
		});
	}

	function showColorHex( pTagId, colorHex ) {
		$('#' + KEY_SAMPLE_HEX + pTagId).val( String(colorHex).toUpperCase() );
	}

	function inputSampleHex() {
		var color_value = $(this).val();
		if( ! color_value.match( /#[0-9a-fA-F]{6}/ ) ) return;

		var suffix_string = getSuffixString( $(this).attr('id'), KEY_SAMPLE_HEX );
		var element = document.querySelector( '#' + KEY_SAMPLE_COLORS + suffix_string );
		$(element).val( color_value );
		inputSampleColorWithThis( $(element) );
	}

	function changeSampleHex() {
		var color_value = $(this).val();
		if( ! color_value.match( /#[0-9a-fA-F]{6}/ ) ) return;

		var suffix_string = getSuffixString( $(this).attr('id'), KEY_SAMPLE_HEX );
		var element = document.querySelector( '#' + KEY_SAMPLE_COLORS + suffix_string );
		$(element).val( color_value );
		changeSampleColorWithThis( $(element) );
	}

	function inputSampleColor() {
		inputSampleColorWithThis( $(this) );
	}

	function showSampleColorWithThreeElements( color_value ) {
		set16ColorsWithDegree( color_value );
		set16ColorsWithSaturation( color_value );
		set16ColorsWithValue( color_value );
		showSampleColor( color_value );
	}

	function inputSampleColorWithThis( _this ) {
		var suffix_string = getSuffixString( $(_this).attr('id'), KEY_SAMPLE_COLORS );
		var color_value = $(_this).val();
		var bg_font_value = $('input[name="' + RADIO_NAME_SAMPLE_COLORS + '"]:checked').val();

		switch ( suffix_string ) {
			case KEY_SAMPLE_BACKGROUND:
				showColorHex( KEY_SAMPLE_BACKGROUND, color_value );
				if ( bg_font_value === RADIO_VALUE_SAMPLE_COLORS_BACKGROUND ) {
					showSampleColorWithThreeElements( color_value );
				}
				break;
			case KEY_SAMPLE_FONT:
				showColorHex( KEY_SAMPLE_FONT, color_value );
				if ( bg_font_value === RADIO_VALUE_SAMPLE_COLORS_FONT ) {
					showSampleColorWithThreeElements( color_value );
				}
				break;
			default:
				showColorHex( suffix_string, color_value );
		}
	}

	function changeSampleColor() {
		changeSampleColorWithThis( $(this) );
	}

	function getPositionInSaturationPercentArray( color_value ) {
		return getPositionInPercentArray( color_value, saturation_percent_array, TYPE_SAT );
	}

	function getPositionInValuePercentArray( color_value ) {
		return getPositionInPercentArray( color_value, value_percent_array, TYPE_VAL );
	}

	function getPositionInPercentArray( color_value, percent_array, key ) {
		for ( let i = 1; i <= percent_array.length; i++ ) {
			if ( $('#' + KEY_SAMPLE_COLORS + key + i).val() === color_value ) {
				return i;
			}
		}
	}

	function changeSampleColorWithThis( _this ) {
		setTitleRgbColor( $(_this) );

		if ( $('#' + CHECKBOX_SAMPLE_LOCK).prop('checked') ) return;

		var suffix_string = getSuffixString( $(_this).attr('id'), KEY_SAMPLE_COLORS );
		var bg_font_value = $('input[name="' + RADIO_NAME_SAMPLE_COLORS + '"]:checked').val();
		if ( ! ((suffix_string === KEY_SAMPLE_BACKGROUND && bg_font_value === RADIO_VALUE_SAMPLE_COLORS_BACKGROUND ) ||
		        (suffix_string === KEY_SAMPLE_FONT && bg_font_value === RADIO_VALUE_SAMPLE_COLORS_FONT ) ) ) return;

		switch ( suffix_string ) {
			case KEY_SAMPLE_BACKGROUND:
			case KEY_SAMPLE_FONT:
				var saturationPosition = getPositionInSaturationPercentArray( $(_this).val() );
				var valuePosition = getPositionInValuePercentArray( $(_this).val() );
				$( 'input[name="' + RADIO_NAME_SAMPLE_COLORS16_HUE + '"]' ).val( [RADIO_VALUE_SAMPLE_COLORS16 + TYPE_HUE + '1'] );
				$( 'input[name="' + RADIO_NAME_SAMPLE_COLORS16_SAT + '"]' ).val( [RADIO_VALUE_SAMPLE_COLORS16 + TYPE_SAT + saturationPosition] );
				$( 'input[name="' + RADIO_NAME_SAMPLE_COLORS16_VAL + '"]' ).val( [RADIO_VALUE_SAMPLE_COLORS16 + TYPE_VAL + valuePosition] );
				break;
		}
	}

	function setSampleApplyAll( cssProperty, colorHex ) {
		$('#' + VERIFY_NONCE_STRING + '.' + CLASS_POSTBOX).css( cssProperty, colorHex );
	}

	function showSampleApplyOutside( cssProperty, colorHex ) {
		$('.' + CLASS_CSCP_FORM_TABLE).css( cssProperty, colorHex );
	}

	function showSampleApplyInside( cssProperty, colorHex ) {
		$('.' + CLASS_COLOR_PREVIEW_TABLE).css( cssProperty, colorHex );
	}

	function showSampleColor( colorHex ) {
		var apply_value = $('input[name="' + RADIO_NAME_SAMPLE_APPLY + '"]:checked').val();
		var bg_font_value = $('input[name="' + RADIO_NAME_SAMPLE_COLORS + '"]:checked').val();
		var css_property = bg_font_value === RADIO_VALUE_SAMPLE_COLORS_BACKGROUND ? 
				CSS_PROPERTY_BACKGROUND_COLOR : CSS_PROPERTY_COLOR;

		switch ( apply_value ) {
			case RADIO_VALUE_SAMPLE_APPLY_ALL:
				showSampleApplyOutside( css_property, colorHex );
				showSampleApplyInside( css_property, colorHex );
				break;
			case RADIO_VALUE_SAMPLE_APPLY_OUTSIDE:
				showSampleApplyOutside( css_property, colorHex );
				break;
			case RADIO_VALUE_SAMPLE_APPLY_INSIDE:
				showSampleApplyInside( css_property, colorHex );
				break;
		}
	}

	function changeRadioSampleApply() {
		var radio_value = $(this).val();
		if ( radio_value === RADIO_VALUE_SAMPLE_APPLY_CLEAR ) {
			showSampleApplyAllClear();
		}
	}

	function showSampleApplyAllClear() {
		showSampleApplyOutside( CSS_PROPERTY_BACKGROUND_COLOR, COLOR_WHITE );
		showSampleApplyOutside( CSS_PROPERTY_COLOR, COLOR_BLACK );
		showSampleApplyInside( CSS_PROPERTY_BACKGROUND_COLOR, COLOR_WHITE );
		showSampleApplyInside( CSS_PROPERTY_COLOR, COLOR_BLACK );
	}

	function changeRadioSampleColors16() {
		var suffix_string = getSuffixString( $(this).val(), RADIO_VALUE_SAMPLE_COLORS16 );
		var colorHex = $('#' + KEY_SAMPLE_COLORS + suffix_string).val();
		showSampleColor( colorHex );
	}

	function getPrefixString( original_string, suffix_string ) {
		return original_string.substr( 0, String(original_string).length - String(suffix_string).length );
	}

	function getSuffixString( original_string, prefix_string ) {
		return original_string.substr( String(prefix_string).length );
	}

	function copySampleColor() {
		var type_string;
		var radio_value = $( 'input[name="' + RADIO_NAME_THREE_ELEMENTS + '"]:checked' ).val();
		switch ( radio_value ) {
			case RADIO_VALUE_THREE_ELEMENTS_HUE:
				type_string = RADIO_NAME_SAMPLE_COLORS16_HUE;
				break;
			case RADIO_VALUE_THREE_ELEMENTS_SAT:
				type_string = RADIO_NAME_SAMPLE_COLORS16_SAT;
				break;
			case RADIO_VALUE_THREE_ELEMENTS_VAL:
				type_string = RADIO_NAME_SAMPLE_COLORS16_VAL;
				break;
		}
		var suffix_string = getSuffixString( $( 'input[name="' + type_string + '"]:checked' ).val(), RADIO_VALUE_SAMPLE_COLORS16 );
		var elementId = getPrefixString( $(this).attr('id'), KEY_ADD_COPY_HEX );
		var colorHex = $('#' + KEY_SAMPLE_COLORS + suffix_string).val();
		$('#' + elementId + KEY_ADD_STRING).val( colorHex );
	}

	function showThreeElements( radio_value ) {
		$('.' + CLASS_COLOR_VISIBLED_HUE).show();
		$('.' + CLASS_COLOR_VISIBLED_SAT).show();
		$('.' + CLASS_COLOR_VISIBLED_VAL).show();

		switch ( radio_value ) {
			case RADIO_VALUE_THREE_ELEMENTS_HUE:
				$('.' + CLASS_COLOR_VISIBLED_SAT).hide();
				$('.' + CLASS_COLOR_VISIBLED_VAL).hide();
				break;
			case RADIO_VALUE_THREE_ELEMENTS_SAT:
				$('.' + CLASS_COLOR_VISIBLED_HUE).hide();
				$('.' + CLASS_COLOR_VISIBLED_VAL).hide();
				break;
			case RADIO_VALUE_THREE_ELEMENTS_VAL:
				$('.' + CLASS_COLOR_VISIBLED_HUE).hide();
				$('.' + CLASS_COLOR_VISIBLED_SAT).hide();
				break;
		}
	}

	function changeRadioThreeElements() {
		checkedThreeElements();
	}

	function checkedThreeElements() {
		var radio_value = $('#' + CHECKBOX_THREE_ELEMENTS).prop('checked') ?
			RADIO_VALUE_THREE_ELEMENTS_ALL : $( 'input[name="' + RADIO_NAME_THREE_ELEMENTS + '"]:checked' ).val();
		showThreeElements( radio_value );
	}

	$(document).ready(function(){
		// changeイベント登録
		var $selectElem = $('#' + SELECT_COLOR);
		$selectElem.change( selectColorChange );
		// 現在のサイトカラーを表示
		var $hiddenElem = $('#' + CURRENT_COLOR_INDEX);
		if ( $hiddenElem.attr('value') === '-1' ) {
			// optionsの最後に追加
			var $option = $('<option>')
					.val('')
					.text(TEXT_SELECT)
					.prop('disabled', true)
					.prop('selected', true);
			$selectElem.append($option);
			set_button_disabled_prop( BUTTON_SITE_COLOR_CHANGE, true );
			set_button_disabled_prop( BUTTON_COLOR_DARKMODE, true );
			set_button_disabled_prop( BUTTON_COLOR_MAINTENANCE, true );
			set_button_disabled_prop( BUTTON_COLOR_DELETE, true );
			set_button_disabled_prop( BUTTON_COLOR_CREATE, true );
			set_button_disabled_prop( BUTTON_COLOR_SLUGMODE, true );
			set_button_disabled_prop( BUTTON_COPYALLCOLOR, true );
		} else {
			$selectElem.prop('selectedIndex', $hiddenElem.attr('value'));
			selectColorChange();
		}

		// イベント追加
		$('.' + CLASS_COPYALLCOLOR).click( copyAllColor );
		$('.' + CLASS_COPYSELECTOPTION).dblclick( copySelectOption );
		$('.' + CLASS_COPYTEXT).dblclick( copyText );
		$('.' + CLASS_COPYCOLOR).dblclick( copyColor );
		$('#' + FORM_ID_FORM_COLOR_CREATE).submit( checkOnSubmitColorCreate );
		$('#' + CHECKBOX_ID_CHECKBOX_ALL_CHECK).click( checkedAllColor );
		$('#' + CHECKBOX_COLOR_ONLY_SAVE).click( checkedColorOnlySave );
		$('input[name="' + CHECKBOX_NAME_NO_VALUE_CHECKBOXES_ARRAY + '"]').click( checkedColor );
		$('.' + CLASS_SAMPLE_COLORPALETTE).on( 'input', inputSampleColor );
		$('.' + CLASS_SAMPLE_COLORPALETTE).change( changeSampleColor );
		$( 'input[name="' + RADIO_NAME_SAMPLE_APPLY + '"]:radio' ).change( changeRadioSampleApply );
		$( 'input[name="' + RADIO_NAME_SAMPLE_COLORS16_HUE + '"]:radio' ).change( changeRadioSampleColors16 );
		$( 'input[name="' + RADIO_NAME_SAMPLE_COLORS16_SAT + '"]:radio' ).change( changeRadioSampleColors16 );
		$( 'input[name="' + RADIO_NAME_SAMPLE_COLORS16_VAL + '"]:radio' ).change( changeRadioSampleColors16 );
		$('.' + CLASS_SAMPLE_HEX).on( 'input', inputSampleHex );
		$('.' + CLASS_SAMPLE_HEX).change( changeSampleHex );
		$('.' + CLASS_COPY_SAMPLE_COLOR).click( copySampleColor );
		$( 'input[name="' + RADIO_NAME_THREE_ELEMENTS + '"]:radio' ).change( changeRadioThreeElements );
		$('#' + CHECKBOX_THREE_ELEMENTS).click( checkedThreeElements );
		$('#' + CHECKBOX_MODE_SETTING_VISIBLED).click( checkedModeSettingVisibled );

		showSampleApplyAllClear();
		showThreeElements( RADIO_VALUE_THREE_ELEMENTS_HUE );
		checkedModeSettingVisibled();
	});

} ) ( jQuery );
