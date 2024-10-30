<?php
/**
	Plugin Name: Cocoon Scenery Color
	Plugin URI: https://lacaraterra.site/cocoon-scenery-color-plugin/
	Description: WordPressテーマ「Cocoon」で定義しているカラーなどの情報を簡単に設定することができます。本プラグインを更新する場合は事前に本プラグインのバックアップ機能を使用して作成したカラーファイルをバックアップしてください。詳しくは<a href='https://lacaraterra.site/cocoon-scenery-color-plugin/#backup'>本プラグインのサイト</a>をご覧ください。
	Version: 1.3.6
	Author: nshuuo36
	Author URI: https://lacaraterra.site/
	License: GPLv2 or later
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;  // Exit if accessed directly
}

if ( ! class_exists( 'Cocoon_Scenery_Color' ) ) :

	require_once __DIR__ . '/lib/class-file-archive.php';
	require_once __DIR__ . '/lib/class-log-message.php';

	class Cocoon_Scenery_Color {

		const FOLDER_NAME_DEFAULTS         = 'defaults';
		const FOLDER_NAME_OPTIONS          = 'options';
		const FOLDER_NAME_LANGUAGES        = 'languages';
		const FOLDER_NAME_DOWNLOAD         = 'download';
		const FOLDER_NAME_IMAGES           = 'images';
		const FILE_COLOR_PREVIEW_LIST_CSS  = 'color-preview-list.css';
		const FILE_SELECT_COLOR_JS         = 'scenery-color.js';
		const FILE_OPTIONS_ZIP             = 'options.zip';
		const FILE_SAMPLE_COLOR_ALL        = 'sample-color-all.png';
		const FILE_SAMPLE_COLOR_OUTSIDE    = 'sample-color-outside.png';
		const FILE_SAMPLE_COLOR_INSIDE     = 'sample-color-inside.png';
		const JSON_FILE_EXTENSION          = '.json';
		const CSS_FILE_EXTENSION           = '.css';
		const FILE_CSCP_DARKMODE_CSS       = 'cscp-darkmode'   . self::CSS_FILE_EXTENSION;
		const FILE_CSCP_MAINTEMODE_CSS     = 'cscp-maintemode' . self::CSS_FILE_EXTENSION;
		// カラー設定値
		const FILE_COLOR_DEFAULT_JSON      = 'color-default.json';
		// カレントカラー
		const FILE_SCENERY_COLOR_INFO_JSON = 'scenery-color-info' . self::JSON_FILE_EXTENSION;
		const MAINTENANCE_MODE             = 'maintenance_mode';
		const DARKMODE_MODE                = 'darkmode_mode';
		const MAINTE_IP_ADDRESSES          = 'mainte_ip_addresses';
		const CURRENT_COLOR_NAME           = 'current_color_name';
		const CURRENT_COLOR_INDEX          = 'current_color_index';
		const SLUG_MODE                    = 'slug_mode';
		const MODE_SETTING_VISIBLED        = 'mode_setting_visibled';
		// ダークモード用CSSファイル
		const CURRENT_DARKMODE_NAME        = 'current_darkmode_name';
		// メンテナンスモード用CSSファイル
		const CURRENT_MAINTENANCE_NAME     = 'current_maintenance_name';
		// アップロードファイル上限サイズ(単位：MB)
		const UPLOAD_MAX_FILE_SIZE_MB      = 'upload_max_file_size_mb';

		// theme_modsデータ格納ファイル
		const THEME_MODS_FILE                         = 'theme-mods';
		const THEME_MODS_FILE_COLOR_DESCRIPTION       = 'DBの現在値です';
		const THEME_MODS_FILE_COLOR_DESCRIPTION_DOUBT = 'ブラウザの表示を更新してください';
		// element id, class
		const ELEMENT_ID_DEFAULT_JSON_DATA            = 'default_json_data';
		const ELEMENT_ID_JSON_DATA                    = 'json-data';
		const ELEMENT_ID_MO_DATA                      = 'mo-data';
		const ELEMENT_ID_SLUGMODE_FILES               = 'slugmode-file';
		const ELEMENT_ID_COLOR_PREVIEW                = 'color_preview';
		const ELEMENT_ID_COLOR_PREVIEW_SPAN           = 'color_preview_span';
		const FORM_ID_FORM_MAINTE_SETTING             = 'form_mainte_setting';
		const FORM_ID_FORM_DARKMODE_SETTING           = 'form_darkmode_setting';
		const FORM_ID_FORM_SLUGMODE_SETTING           = 'form_slugmode_setting';
		const FORM_ID_FORM_COLOR_CHANGE               = 'form_color_change';
		const FORM_ID_FORM_COLOR_CREATE               = 'form_color_create';
		const FORM_ID_FORM_COLOR_DARKMODE             = 'form_color_darkmode';
		const FORM_ID_FORM_COLOR_SLUGMODE             = 'form_color_slugmode';
		const FORM_ID_FORM_COLOR_MAINTENANCE          = 'form_color_maintenance';
		const FORM_ID_FORM_COLOR_DELETE               = 'form_color_delete';
		const CHECKBOX_ID_CHECKBOX_ALL_CHECK          = 'checkbox_all_check';
		const CHECKBOX_NAME_NO_VALUE_CHECKBOXES       = 'no_value_checkboxes';
		const CHECKBOX_NAME_NO_VALUE_CHECKBOXES_ARRAY = self::CHECKBOX_NAME_NO_VALUE_CHECKBOXES . '[]';
		const CLASS_CSCP_FORM_TABLE                   = 'cscp-form-table';
		const CLASS_CSCP_FORM_TABLE_BACKUP            = 'cscp-form-table-backup';
		const CLASS_COPYALLCOLOR                      = 'copyAllColor';
		const CLASS_TD_COPY_SAMPLE_COLOR              = 'copy_sample_color';
		const CLASS_COPY_SAMPLE_COLOR                 = 'copySampleColor';
		const CLASS_COPYSELECTOPTION                  = 'copySelectOption';
		const CLASS_COPYTEXT                          = 'copyText';
		const CLASS_COPYCOLOR                         = 'copyColor';
		const CLASS_COLORPALETTE                      = 'colorPalette';
		const CLASS_NO_VALUE                          = 'novalue';
		const CLASS_SAMPLE_COLORPALETTE               = 'sampleColorPalette';
		const CLASS_SAMPLE_COLOR                      = 'sample_color';
		const CLASS_SAMPLE_COLORS16                   = 'sample_colors16';
		const CLASS_SAMPLE_COLOR_GUIDE                = 'sample_color_guide';
		const CLASS_SAMPLE_HEX                        = 'sample_hex';
		const CLASS_SELECT_OPTION                     = 'select_option';
		const CLASS_NOT_COLOR                         = 'not_color';
		const CLASS_COLOR_VISIBLED_HUE                = 'visibled_hue';
		const CLASS_COLOR_VISIBLED_SAT                = 'visibled_sat';
		const CLASS_COLOR_VISIBLED_VAL                = 'visibled_val';
		const CLASS_LABEL_MARGIN_RIGHT                = 'label_margin_right';
		const CLASS_NOT_COLOR_VISIBLED                = 'visibled_not_color';
		const CLASS_MODE_SETTING_VISIBLED             = 'mode_setting_visibled';
		// 隠しフィールド名
		const HIDDEN_MAINTE_SETTING        = 'submit_hidden_mainte_setting';
		const HIDDEN_DARKMODE_SETTING      = 'submit_hidden_darkmode_setting';
		const HIDDEN_SLUGMODE_SETTING      = 'submit_hidden_slugmode_setting';
		const HIDDEN_SITE_COLOR_CHANGE     = 'submit_hidden_site_change';
		const HIDDEN_COLOR_CREATE          = 'submit_hidden_create';
		const HIDDEN_DARKMODE              = 'submit_hidden_darkmode';
		const HIDDEN_SLUGMODE              = 'submit_hidden_slugmode';
		const HIDDEN_MAINTENANCE           = 'submit_hidden_maintenance';
		const HIDDEN_COLOR_DELETE          = 'submit_hidden_delete';
		const HIDDEN_BACKUP                = 'submit_hidden_backup';
		const HIDDEN_RESTORE               = 'submit_hidden_restore';
		const HIDDEN_MODE_SETTING_VISIBLED = 'hidden_mode_setting_visibled';
		const VERIFY_NONCE_STRING          = 'cscp-option';
		// ファイル情報
		const COLOR_FILE_NAME              = 'file_name';
		const COLOR_PREVIEW_FILE           = 'preview_file';
		const COLOR_JSON_DATA              = 'json_data';
		// 入力値
		const INPUT_NEW_COLOR_NAME         = 'color_name_new';
		const INPUT_NEW_COLOR_DESC         = 'color_description';
		const INPUT_COLOR_NAME             = 'color_name';
		const SIZE_MB                      = 1024 * 1024;
		const INPUT_MAX_FILE_SIZE_MB       = 5;
		// 入力値の属性
		const INPUT_TYPE_ATTR_TEXT         = 'text';
		const INPUT_TYPE_ATTR_SELECT       = 'select';
		const INPUT_TYPE_ATTR_COLOR        = 'color';
		const INPUT_TYPE_FILE_NAME         = self::FOLDER_NAME_OPTIONS;
		// button
		const BUTTON_MAINTE_SETTING        = 'submit_button_mainte_setting';
		const BUTTON_DARKMODE_SETTING      = 'submit_button_darkmode_setting';
		const BUTTON_SLUGMODE_SETTING      = 'submit_button_slugmode_setting';
		const BUTTON_SITE_COLOR_CHANGE     = 'submit_button_site_change';
		const BUTTON_COLOR_CREATE          = 'submit_button_create';
		const BUTTON_COLOR_DARKMODE        = 'submit_button_darkmode';
		const BUTTON_COLOR_SLUGMODE        = 'submit_button_slugmode';
		const BUTTON_COLOR_MAINTENANCE     = 'submit_button_maintenance';
		const BUTTON_COLOR_DELETE          = 'submit_button_delete';
		const BUTTON_COPYALLCOLOR          = 'button_copyallcolor';
		// checkbox
		const CHECKBOX_MAINTE_SETTING        = 'checkbox_mainte_setting';
		const CHECKBOX_DARKMODE_SETTING      = 'checkbox_darkmode_setting';
		const CHECKBOX_SLUGMODE_SETTING      = 'checkbox_slugmode_setting';
		const CHECKBOX_SITE_COLOR_CHANGE     = 'checkbox_site_color_change';
		const CHECKBOX_DARKMODE_USE          = 'checkbox_darkmode_use';
		const CHECKBOX_SLUGMODE_USE          = 'checkbox_slugmode_use';
		const CHECKBOX_MAINTENANCE_USE       = 'checkbox_maintenance_use';
		const CHECKBOX_COLOR_ONLY_SAVE       = 'checkbox_color_only_save';
		const CHECKBOX_SAMPLE_LOCK           = 'checkbox_sample_lock';
		const CHECKBOX_THREE_ELEMENTS        = 'checkbox_three_elements';
		const CHECKBOX_MODE_SETTING_VISIBLED = 'checkbox_mode_setting_visibled';
		// textbox
		const TEXTBOX_IP_ADDRESSES           = 'textbox_ip_addresses';
		// radio
		const RADIO_NAME_SAMPLE_APPLY               = 'radio_sample_apply';
		const RADIO_VALUE_SAMPLE_APPLY_ALL          = 'all';
		const RADIO_VALUE_SAMPLE_APPLY_OUTSIDE      = 'outside';
		const RADIO_VALUE_SAMPLE_APPLY_INSIDE       = 'inside';
		const RADIO_VALUE_SAMPLE_APPLY_CLEAR        = 'clear';
		const RADIO_NAME_SAMPLE_COLORS              = 'radio_sample_colors';
		const RADIO_NAME_SAMPLE_COLORS16_HUE        = 'radio_sample_colors16_hue';
		const RADIO_NAME_SAMPLE_COLORS16_SAT        = 'radio_sample_colors16_sat';
		const RADIO_NAME_SAMPLE_COLORS16_VAL        = 'radio_sample_colors16_val';
		const RADIO_VALUE_SAMPLE_COLORS_BACKGROUND  = 'background';
		const RADIO_VALUE_SAMPLE_COLORS_FONT        = 'font';
		const RADIO_VALUE_SAMPLE_COLORS16           = 'colors16_';
		const RADIO_NAME_THREE_ELEMENTS             = 'radio_three_elements';
		const RADIO_VALUE_THREE_ELEMENTS_HUE        = 'hue';
		const RADIO_VALUE_THREE_ELEMENTS_SAT        = 'sat';
		const RADIO_VALUE_THREE_ELEMENTS_VAL        = 'val';
		const RADIO_VALUE_THREE_ELEMENTS_ALL        = 'all';
		// カラー名
		const COLOR_THEME_SELECT_COLOR   = 'selectColor';
		// キー
		const KEY_COLOR_DESCRIPTION      = 'color_description';
		const KEY_COLOR_NAME             = 'color_name';
		const KEY_OVERWRITE_COLOR_FILE   = 'overwrite_color_file';
		const KEY_COLOR_ONLY_SAVE        = 'color_only_save';
		const KEY_ADD_STRING             = '_new';
		const KEY_ADD_COPY_HEX           = '_hex';
		const KEY_SAMPLE_COLORS          = 'sample_color_';
		const KEY_SAMPLE_TEXT            = 'sample_text_';
		const KEY_SAMPLE_HEX             = 'sample_hex_';
		const KEY_SAMPLE_BACKGROUND      = 'background';
		const KEY_SAMPLE_FONT            = 'font';
		const TYPE_HUE                   = 'hue_';
		const TYPE_SAT                   = 'sat_';
		const TYPE_VAL                   = 'val_';
		// JSON encode
		const JSON_ENCODE_TO_FILE        = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE;
		const JSON_ENCODE_TO_HTML        = JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP;
		// cron
		const CSCP_CRON_HANDLE           = 'cscp_cron_handle';
		// 投稿、固定ページ、カテゴリーのファイル接頭辞
		const PREFIX_POST                = 'post-';
		const PREFIX_PAGE                = 'page-';
		const PREFIX_CATEGORY            = 'cate-';
		const PREFIX_LENGTH              = 5;
		// 色
		const COLOR_DEFAULT              = '#F8F8F8';
		const COLOR_WHITE                = '#FFFFFF';
		// メッセージ
		const MESSAGE_CLICK_PALETTE      = 'クリックでカラーパレットを表示します。';
		const MESSAGE_INPUT_RRGGBB       = '#RRGGBB(HEX)形式で入力できます。';

		// private static
		private static $instance = false;
		// private
		private $plugin_name;
		private $plugin_options_dir;
		private $scenery_color_info_json_path;
		private $mo_data = array();
		private $error_message = '';

		/**
		 * インスタンスの生成
		 */
		public static function instance() : object {
			if ( ! self::$instance ) {
				self::$instance = new self();
			}
			self::$instance->initialize();
			return self::$instance;
		}

		/**
		 * デフォルトコンストラクタ
		 */
		private function __construct() {
			// Do nothing.
		}

		/**
		 * インスタンス生成後の初期化処理
		 */
		private function initialize() : void {
			$this->plugin_name                  = dirname( plugin_basename( __FILE__ ) );
			$this->plugin_options_dir           = plugin_dir_path( __FILE__ ) . self::FOLDER_NAME_OPTIONS . '/';
			$this->scenery_color_info_json_path = plugin_dir_path( __FILE__ ) . self::FILE_SCENERY_COLOR_INFO_JSON;

			if ( is_admin() ) {
				add_action( 'after_setup_theme', array( $this, 'setup' ) );
			} else {
				// ダークモード用、メンテナンスモード用CSSの出力
				add_action( 'wp_enqueue_scripts', array( $this, 'register_mode_plugin_styles' ), PHP_INT_MAX );
			}
			// cronの設定
			$param_count = 1;	// パラメータ数
			add_action( self::CSCP_CRON_HANDLE, array( $this, 'cron_execute' ), 10, $param_count );
			// プラグインのアップグレード用設定
			$param_count = 4;	// パラメータ数
			add_filter( 'upgrader_source_selection', array( $this, 'upgrader_source_selection' ), 10, $param_count );
			$param_count = 3;	// パラメータ数
			add_filter( 'upgrader_post_install', array( $this, 'upgrader_post_install' ), 10, $param_count );
		}

		/**
		 * WordPress管理画面メニュー追加等
		 */
		public function setup() : void {
			if ( ! defined( 'THEME_NAME' ) || 'cocoon' !== THEME_NAME ) {
				return;
			}

			$this->load_textdomain();
			add_action( 'admin_menu',            array( $this, 'set_plugin_sub_menu' ) );
			add_action( 'admin_init',            array( $this, 'register_plugin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
		}

		/**
		 * プラグイン更新処理前のフック処理
		 * 本プラグインのバックアップ処理を行います。
		 * @param  string      $source        アップグレードファイル（zipファイル）の解凍後のプラグイン名までのパス
		 * @param  string      $remote_source アップグレードファイル（zipファイル）のパス
		 * @param  WP_Upgrader $upgrader      WP_Upgraderインスタンス
		 * @param  array       $hook_extra    フック時のパラメータ
		 * @return string                     アップグレードファイル（zipファイル）の解凍後のプラグイン名までのパス
		 */
		public function upgrader_source_selection( $source, $remote_source, $upgrader, $hook_extra ) : string {
			// 本プラグインのバックアップ処理
			if ( $this->plugin_name === basename( $source ) ) {										// 本プラグイン
				if ( $this->is_plugin_installer_overwrite_process( $upgrader ) ) {	// プラグインのアップグレード処理
					// バックアップファイルの作成
					$file_archive = new CSCP_FileArchive();
					$file_archive->create_backup_file();
					// バックアップファイルのテンポラリフォルダへの移動
					// キャッシュパスへの移動
					$backup_file = plugin_dir_path( __FILE__ ) . self::FOLDER_NAME_DOWNLOAD . '/' . self::FILE_OPTIONS_ZIP;
					$upload_file = get_theme_cache_path() . self::FILE_OPTIONS_ZIP;
					rename( $backup_file, $upload_file );
				}
			}
			return $source;
		}

		/**
		 * プラグインのアップグレード処理の判定
		 * プラグインのアップグレード処理かどうかを判定します。
		 * @param  WP_Upgrader $upgrader WP_Upgraderインスタンス
		 * @return bool                  true：アップグレード処理（＝options.zipファイルを作成する）、false：ユーザー確認有りのアップグレード処理（＝options.zipファイルを作成しない）
		 */
		private function is_plugin_installer_overwrite_process( $upgrader ) : bool {
			$result = false;
			if ( $upgrader ) {
				if ( $upgrader->skin instanceof Plugin_Installer_Skin ) {
					if ( isset( $upgrader->skin->overwrite ) && '' !== $upgrader->skin->overwrite ) {
						$result = true;
					}
				} else {
					$result = true;
				}
			}
			return $result;
		}

		/**
		 * プラグイン更新処理後のフック処理
		 * 本プラグインのリストア処理を行います。
		 * @param  bool  $response   Installation response.
		 * @param  array $hook_extra Extra arguments passed to hooked filters.
		 * @param  array $result     Installation result data.
		 * @return bool              Installation response.
		 */
		public function upgrader_post_install( $response, $hook_extra, $result ) : bool {
			// 本プラグインのリストア処理
			if ( $this->plugin_name === basename( $result['source'] ) ) {
				// リストア処理
				$file_archive = new CSCP_FileArchive();
				$upload_file = get_theme_cache_path() . self::FILE_OPTIONS_ZIP;
				$file_archive->do_restore_upload( $upload_file );
			}
			return $response;
		}

		/**
		 * 翻訳データの取得
		 */
		public function load_textdomain() : void {
			load_plugin_textdomain( $this->plugin_name, false, $this->plugin_name . '/' . self::FOLDER_NAME_LANGUAGES );
			// 翻訳データの取得
			$this->mo_data = $this->get_translation();
		}

		/**
		 * ESCAPE処理後の説明文の生成処理呼び出し
		 * @param  string $caption ESCAPE処理前の説明文
		 */
		private function my_generate_tips_tag( $caption, $help_page_tag = '' ) : void {
			generate_tips_tag( $this->get_esc_translate( $caption ) . $help_page_tag );
		}

		/**
		 * esc_html_e関数の呼び出し
		 * @param  string $text ESCAPE処理対象文字列
		 */
		private function echo_esc_translate( $text ) : void {
			esc_html_e( $text, $this->plugin_name );
		}

		/**
		 * esc_html__関数の呼び出し
		 * @param  string $text ESCAPE処理対象文字列
		 * @return string       ESCAPE処理された文字列
		 */
		private function get_esc_translate( $text ) : string {
			return esc_html__( $text, $this->plugin_name );
		}

		/**
		 * プラグイン用サブメニューの設定
		 */
		public function set_plugin_sub_menu() : void {
			add_submenu_page(
				THEME_SETTINGS_PAFE,
				$this->get_esc_translate( 'カラー設定' ),
				$this->get_esc_translate( 'サイトカラー設定' ),
				'manage_options',
				'cocoon-scenery-color-config',
				array( $this, 'show_form' )
			);
		}

		/**
		 * CSSファイルの設定
		 */
		public function register_plugin_styles() : void {
			wp_register_style( 'sapporocolor-style', plugins_url( 'sapporocolor.css', __FILE__ ) );
			wp_enqueue_style( 'sapporocolor-style' );
			wp_register_style( 'color-preview-list-style', plugins_url( self::FILE_COLOR_PREVIEW_LIST_CSS, __FILE__ ) );
			wp_enqueue_style( 'color-preview-list-style' );
		}

		/**
		 * JavaScriptファイルの設定
		 */
		public function register_plugin_scripts( $hook_suffix ) : void {
			wp_enqueue_script( 'scenery-color-script', plugins_url( self::FILE_SELECT_COLOR_JS, __FILE__ ), array( 'jquery' ), '', true );
		}

		/**
		 * 稼働中のwp-cronプロセスの有無確認
		 * @return string ''：稼働中のwp-cronプロセス無し、''以外：稼働中のwp-cronプロセスのフック名
		 */
		private function check_another_cron_process_running() : string {
			$result   = '';
			$gmt_time = microtime( true );
			$lock     = get_transient( 'doing_cron' );
			if ( $lock + WP_CRON_LOCK_TIMEOUT > $gmt_time ) {
				$crons = wp_get_ready_cron_jobs();
				if ( ! empty( $crons ) ) {
					$result = array_keys( $crons[array_keys($crons)[0]] )[0];
				}
			}
			return $result;
		}

		/**
		 * wp-cronを呼び出す処理
		 * @param  string $mode_name ダークモード、メンテナンスモード時は、その判別名。スラッグモード時は、スラッグモード用CSSファイル名（拡張子除く）。
		 * @return bool true：cron実行イベント生成成功、false：cron実行イベント生成失敗
		 */
		private function do_cron_schedule_event( $mode_name ) : bool {
			$result = false;
			// wp-cronの実行
			// イベントがない場合にだけ登録します
			if ( ! wp_next_scheduled( self::CSCP_CRON_HANDLE ) ) {
				// wp-cron eventの登録
				wp_schedule_single_event( time(), self::CSCP_CRON_HANDLE, array( $mode_name ) );
			  // wp-cronの即時実行
			  $result = spawn_cron();
			}
			return $result;
		}

		/**
		 * wp-cronから呼び出される処理
		 * @param  string $mode_name ダークモード、メンテナンスモード時は、その判別名。スラッグモード時は、スラッグモード用CSSファイル名（拡張子除く）。
		 */
		public function cron_execute( $mode_name ) : void {
			// カレントカラー情報の取得
			$color_info_json_data = $this->read_json_file( $this->scenery_color_info_json_path );
			// 現在のCocoonオプション値を元にしたCSSファイルデータの取得
			$css_custom = $this->get_css_custom();
			// ファイル名の構築
			$css_file = '';
			if ( self::CURRENT_DARKMODE_NAME === $mode_name || self::CURRENT_MAINTENANCE_NAME === $mode_name ) {
				// ダークモードかメンテナンスモード
				$current_mode_name = $this->get_array_key_exists( $mode_name, $color_info_json_data );
				if ( '' !== $current_mode_name ) {
					$css_file = $this->plugin_options_dir . $this->get_css_filename( $mode_name );
				}
			} else {
				// スラッグモード
				$css_file = $this->plugin_options_dir . $mode_name . self::CSS_FILE_EXTENSION;
			}
			// CSSファイルの作成
			if ( '' !== $css_file ) {
				file_put_contents( $css_file, $css_custom );
			}

			// DB設定をカレントカラーに戻す
			// カラー情報ファイルのパス組み立て
			$current_color_file = $this->plugin_options_dir . $color_info_json_data[ self::CURRENT_COLOR_NAME ] . self::JSON_FILE_EXTENSION;
			if ( file_exists( $current_color_file ) ) {
				// カラー設定の保存
				$is_wp_cron = true;
				$this->update_option( $current_color_file, $is_wp_cron );
			}
		}

		/**
		 * ダークモード用、メンテナンスモード用、スラッグモード用CSSの出力
		 */
		public function register_mode_plugin_styles() : void {
			// カレント情報の取得
			if ( ! file_exists( $this->scenery_color_info_json_path ) ) {
				return;
			}
			$color_info_json_data = $this->read_json_file( $this->scenery_color_info_json_path );

			// メンテナンスモード用CSSの出力
			if ( $this->can_output_maintenance_plugin_styles( $color_info_json_data ) ) {
				$this->output_plugin_styles( self::CURRENT_MAINTENANCE_NAME, $color_info_json_data );
			}
			// スラッグモード用CSSの出力
			$slug_css_file = $this->can_output_slug_plugin_styles( $color_info_json_data );
			if ( $slug_css_file ) {
				$this->output_plugin_styles( self::SLUG_MODE, $color_info_json_data, $slug_css_file );
			}
			// ダークモード用CSSの出力
			if ( $this->can_output_darkmode_plugin_styles( $color_info_json_data ) ) {
				$this->output_plugin_styles( self::CURRENT_DARKMODE_NAME, $color_info_json_data );
			}
		}

		/**
		 * メンテナンスモード用CSSの出力有無チェック
		 * @param  array  $color_info_json_data カレントカラー情報
		 * @return bool true：メンテナンスモード用CSS出力有り、false：メンテナンスモード用CSS出力無し
		 */
		private function can_output_maintenance_plugin_styles( $color_info_json_data ) : bool {
			// メンテナンスモードでは無い場合は出力しない
			if ( ! $this->get_array_key_exists( self::MAINTENANCE_MODE, $color_info_json_data ) ) {
				return false;
			}
			// サイト確認用IPアドレスで指定されているIPアドレスであれば出力しない
			if ( $this->is_mainte_ip_address( $this->get_array_key_exists( self::MAINTE_IP_ADDRESSES, $color_info_json_data ) ) ) {
				return false;
			}
			return true;
		}

		/**
		 * ダークモード用CSSの出力有無チェック
		 * @param  array  $color_info_json_data カレントカラー情報
		 * @return bool true：ダークモード用CSS出力有り、false：ダークモード用CSS出力無し
		 */
		private function can_output_darkmode_plugin_styles( $color_info_json_data ) : bool {
			// ダークモードでは無い場合は出力しない
			if ( ! $this->get_array_key_exists( self::DARKMODE_MODE, $color_info_json_data ) ) {
				return false;
			}
			// メンテナンスモードで且つサイト確認用IPアドレスで指定されているIPアドレスであればダークモード用CSSは出力しない
			if ( $this->get_array_key_exists( self::MAINTENANCE_MODE, $color_info_json_data ) &&
			     $this->is_mainte_ip_address( $this->get_array_key_exists( self::MAINTE_IP_ADDRESSES, $color_info_json_data ) ) ) {
				return false;
			}
			return true;
		}

		/**
		 * CSSファイル名の取得
		 * @param  string $mode_name ダークモード用、メンテナンスモード用の判別名
		 * @return string CSSファイル名
		 */
		private function get_css_filename( $mode_name ) : string {
			return self::CURRENT_DARKMODE_NAME === $mode_name ? self::FILE_CSCP_DARKMODE_CSS : self::FILE_CSCP_MAINTEMODE_CSS;
		}

		/**
		 * CSSの出力
		 * @param  string $mode_name            ダークモード用、メンテナンスモード用、スラッグモード用の判別名
		 * @param  array  $color_info_json_data カレントカラー情報
		 * @param  string $css_file             CSSファイル（フルパス付き）
		 */
		private function output_plugin_styles( $mode_name, $color_info_json_data, $css_file = '' ) : void {
			// スラッグモード用以外はCSSファイルをチェック
			if ( self::SLUG_MODE !== $mode_name ) {
				// 出力するCSSファイル名が格納されていない場合は処理しない
				if ( ! $this->get_array_key_exists( $mode_name, $color_info_json_data ) ) {
					return;
				}
				// 出力するCSSファイルが存在しない場合は処理しない
				$css_file = $this->plugin_options_dir . $this->get_css_filename( $mode_name );
				if ( ! file_exists( $css_file ) ) {
					return;
				}
			}

			// 出力するCSSデータの取得
			$css_data = file_get_contents( $css_file );

			// ダークモード用はダークモード用CSS定義で囲む @media (prefers-color-scheme: dark) {}
			if ( self::CURRENT_DARKMODE_NAME === $mode_name ) {
				$css_data = '@media (prefers-color-scheme: dark) {' . $css_data . '}';
			}

			// CSSを追加するCSS(ハンドル)名の取得
			$handle = '';
			// ref:\cocoon-master\lib\utils.php wp_add_css_custome_to_inline_style()
			if (get_skin_url()) {
			  //スキンがある場合
			  $skin_keyframes_url = get_theme_skin_keyframes_url();
			  if ($skin_keyframes_url) {
			    // wp_add_inline_style( THEME_NAME.'-skin-keyframes', $css_custom );
			    $handle = THEME_NAME.'-skin-keyframes';
			  } else {
			    // wp_add_inline_style( THEME_NAME.'-skin-style', $css_custom );
			    $handle = THEME_NAME.'-skin-style';
			  }
			} else {
			  //スキンを使用しない場合
			  // wp_add_inline_style( THEME_NAME.'-style', $css_custom );
			  $handle = THEME_NAME.'-style';
			}

			// CSSデータの追加出力
			wp_add_inline_style( $handle, $css_data );
		}

		/**
		 * アクセス元IPアドレスの取得
		 * @return string アクセス元IPアドレス
		 */
		private function get_remote_ip_addr() : string {
			$ip_addr = '';
			if ( isset( $_SERVER['REMOTE_ADDR'] )
			and WP_Http::is_ip_address( $_SERVER['REMOTE_ADDR'] ) ) {
				$ip_addr = $_SERVER['REMOTE_ADDR'];
			}
			return $ip_addr;
		}

		/**
		 * サイトにアクセスされた(訪問者の)IPアドレスがサイト確認用IPアドレスに含まれているかのチェック
		 * @param  string $mainte_ip_addresses サイト確認用IPアドレス
		 * @return bool true：サイト確認用IPアドレスに含まれている、false：サイト確認用IPアドレスに含まれていない
		 */
		private function is_mainte_ip_address( $mainte_ip_addresses ) : bool {
			// サイトにアクセスされた(訪問者の)IPアドレスの取得
			$ip_addr = $this->get_remote_ip_addr();
			// サイト確認用IPアドレスをカンマで分解
			$ip_address_array = explode( ',', $mainte_ip_addresses );
			return in_array( $ip_addr, $ip_address_array );
		}

		/**
		 * サイト確認用IPアドレス欄に入力されたIPアドレスの正規なIPアドレスの取得
		 * @param  string $mainte_ip_addresses サイト確認用IPアドレス
		 * @return string                      不正な文字列等を除去した後のIPアドレス
		 */
		private function get_valid_ip_address( $mainte_ip_addresses ) : string {
			$valid_ip_addresses = array();
			// サイト確認用IPアドレスをカンマで分解
			$ip_address_array = explode( ',', $mainte_ip_addresses );
			foreach ( $ip_address_array as $ip_address ) {
				if ( WP_Http::is_ip_address( $ip_address ) ) {
					$valid_ip_addresses[] = $ip_address;
				}
			}
			return implode( ',', $valid_ip_addresses );
		}

		/**
		 * スラッグモード用カラー情報ファイルかのチェック
		 * @param  $file  カラー情報ファイル名（カラーの名称）
		 * @return bool   true：スラッグモード用カラー情報ファイル、false：スラッグモード用カラー情報ファイル以外
		 */
		private function is_slugmode_color_file( $file ) : bool {
			$prefix = mb_substr( $file, 0, self::PREFIX_LENGTH );
			return self::PREFIX_POST === $prefix || self::PREFIX_PAGE === $prefix || self::PREFIX_CATEGORY === $prefix;
		}

		/**
		 * スラッグモード用CSSの出力有無チェック
		 * @param  array  $color_info_json_data カレントカラー情報
		 * @return string ''以外：スラッグモード用CSS出力ファイル名、''：スラッグモード用CSS出力無し
		 */
		private function can_output_slug_plugin_styles( $color_info_json_data ) : string {
			$slug_css_file = '';
			// スラッグモードでは無い場合は出力しない
			if ( ! $this->get_array_key_exists( self::SLUG_MODE, $color_info_json_data ) ) {
				return $slug_css_file;
			}
			// メンテナンスモードで且つサイト確認用IPアドレスで指定されているIPアドレスであればスラッグモード用CSSは出力しない
			if ( $this->get_array_key_exists( self::MAINTENANCE_MODE, $color_info_json_data ) &&
			     $this->is_mainte_ip_address( $this->get_array_key_exists( self::MAINTE_IP_ADDRESSES, $color_info_json_data ) ) ) {
				return $slug_css_file;
			}
			// スラッグの取得
			$slugs = $this->get_slug();
			// スラッグモード用CSSファイルの有無確認
			foreach ( $slugs as $slug ) {
				$file_name = $this->plugin_options_dir . $slug . self::CSS_FILE_EXTENSION;
				if ( file_exists( $file_name ) ) {
					$slug_css_file = $file_name;
					break;
				}
			}
			return $slug_css_file;
		}

		/**
		 * 指定されたスラッグモード用CSSファイルの有無チェック
		 * @return bool true：CSSファイル有り、false：CSSファイル無し
		 */
		private function exist_slug_css( $prefix ) : bool {
			return glob( $this->plugin_options_dir . $prefix . '*' . self::CSS_FILE_EXTENSION ) ? true : false;
		}

		/**
		 * スラッグモード用CSSファイルの取得
		 * @return array スラッグモード用CSSファイルの配列
		 */
		private function get_slug_css_files() : array {
			$post_files = glob( $this->plugin_options_dir . self::PREFIX_POST .     '*' . self::CSS_FILE_EXTENSION );
			$page_files = glob( $this->plugin_options_dir . self::PREFIX_PAGE .     '*' . self::CSS_FILE_EXTENSION );
			$cate_files = glob( $this->plugin_options_dir . self::PREFIX_CATEGORY . '*' . self::CSS_FILE_EXTENSION );
			return array_merge ( array_merge( $post_files, $page_files ) , $cate_files );
		}

		/**
		 * スラッグモード用CSSファイルのファイル名（カラー名）取得
		 * @return array スラッグモード用CSSファイル名の配列
		 */
		private function get_slug_css() : array {
			$files = $this->get_slug_css_files();
			if ( $files ) {
				// ファイル名（カラー名）の取得
				array_walk( $files, 
					function ( &$file ) {
						$file = basename( $file, self::CSS_FILE_EXTENSION );
					}
				);
			}
			return $files;
		}

		/**
		 * スラッグの取得
		 * @return array  スラッグ
		 */
		private function get_slug() : array {
			$slugs = array();
			// 投稿
			if ( is_single() ) {
				$prefix = self::PREFIX_POST;
				// 投稿のスラッグ取得
				$queried_object = get_queried_object();
				if ( $queried_object ) {
					$slugs[] = $prefix . urldecode( $queried_object->post_name );
					// カテゴリー用CSSファイル有無確認
					if ( $this->exist_slug_css( self::PREFIX_CATEGORY ) ) {
						// カテゴリーのスラッグ取得
						$slugs = $this->get_post_categories_slug( $slugs );
					}
				}
			// 固定ページ
			} elseif ( is_page() ) {
				$prefix = self::PREFIX_PAGE;
				// スラッグモード用CSSファイル有無確認
				if ( ! $this->exist_slug_css( $prefix ) ) {
					return $slugs;
				}
				// 固定ページのスラッグ取得
				$queried_object = get_queried_object();
				if ( $queried_object ) {
					// 親固定ページのスラッグ取得
					$slugs = $this->get_pages_slug( $queried_object->ID, urldecode( $queried_object->post_name ) );
				}
			// カテゴリー
			} elseif ( is_category() ) {
				$prefix = self::PREFIX_CATEGORY;
				// スラッグモード用CSSファイル有無確認
				if ( ! $this->exist_slug_css( $prefix ) ) {
					return $slugs;
				}
				// カテゴリーのスラッグ取得
				$queried_object = get_queried_object();
				if ( $queried_object ) {
					// カテゴリーのスラッグ取得
					$slugs = $this->get_category_categories_slug( $queried_object->term_id, urldecode( $queried_object->slug ) );
				}
			}
			// 重複したスラッグを除外
			$slugs = array_unique( $slugs );
			return $slugs;
		}

		/**
		 * 投稿（記事）に属するカテゴリーのスラッグ取得
		 * カテゴリーのスラッグは、親子関係（祖先）のカテゴリーをそれぞれ取得します。
		 * @param  array  $slugs 取得したスラッグ
		 * @return array         親子関係（祖先）のカテゴリーのスラッグ群
		 */
		private function get_post_categories_slug( $slugs ) : array {
			$cate_slugs = array();
			$temp_slugs = array();
			$categories = get_the_category();
			if ( $categories ) {
				foreach ( $categories as $category ) {
					$slug = '';
					// 親子関係（祖先）カテゴリーのスラッグ取得
					$ancestors_ids = get_ancestors( $category->term_id, 'category' );
					// 親→子の順へ変換
	 				$ancestors_ids = array_reverse( $ancestors_ids );
					$hierarchy_no  = 0;
					foreach ( $ancestors_ids as $ancestors_id ) {
						$temp_category = get_category( $ancestors_id );
						$slug          = $slug . ('' !== $slug ? '-' : '') . urldecode( $temp_category->slug );
						$temp_slugs[]  = array(
														'term_id'      => $temp_category->term_id,
														'slug'         => self::PREFIX_CATEGORY . $slug,
														'hierarchy_no' => ++$hierarchy_no,
														);
					}
					$slug         = $slug . ('' !== $slug ? '-' : '') . urldecode( $category->slug );
					$temp_slugs[] = array(
													'term_id'      => $category->term_id,
													'slug'         => self::PREFIX_CATEGORY . $slug,
													'hierarchy_no' => ++$hierarchy_no,
													);
				}
				// 階層が深い方から浅い方へ、スラッグの昇順で並び替える
				$hierarchy_no = array_column( $temp_slugs, 'hierarchy_no' );
				$slug         = array_column( $temp_slugs, 'slug' );
				array_multisort( $hierarchy_no, SORT_DESC, SORT_NUMERIC, $slug, SORT_ASC, SORT_STRING, $temp_slugs );
				$cate_slugs   = array_column( $temp_slugs, 'slug' );
			}
			return array_merge( $slugs, $cate_slugs );
		}

		/**
		 * カテゴリーに属するカテゴリーのスラッグ取得
		 * カテゴリーのスラッグは、親子関係（祖先）のカテゴリーをそれぞれ取得します。
		 * @param  int    $term_id       カテゴリーのID
		 * @param  string $category_slug カテゴリーのスラッグ
		 * @return array                 親子関係（祖先）のカテゴリーのスラッグ群
		 */
		private function get_category_categories_slug( $term_id, $category_slug ) : array {
			$cate_slugs = array();
			$temp_slugs = array();
			$slug = '';
			// 親子関係（祖先）カテゴリーのスラッグ取得
			$ancestors_ids = get_ancestors( $term_id, 'category' );
			// 親→子の順へ変換
			$ancestors_ids = array_reverse( $ancestors_ids );
			$hierarchy_no  = 0;
			foreach ( $ancestors_ids as $ancestors_id ) {
				$temp_category = get_category( $ancestors_id );
				$slug          = $slug . ('' !== $slug ? '-' : '') . urldecode( $temp_category->slug );
				$temp_slugs[]  = array(
												'term_id'      => $temp_category->term_id,
												'slug'         => self::PREFIX_CATEGORY . $slug,
												'hierarchy_no' => ++$hierarchy_no,
												);
			}
			$slug         = $slug . ('' !== $slug ? '-' : '') . $category_slug;
			$temp_slugs[] = array(
											'term_id'      => $term_id,
											'slug'         => self::PREFIX_CATEGORY . $slug,
											'hierarchy_no' => ++$hierarchy_no,
											);
			// 階層が深い方から浅い方へ、スラッグの昇順で並び替える
			$hierarchy_no = array_column( $temp_slugs, 'hierarchy_no' );
			$slug         = array_column( $temp_slugs, 'slug' );
			array_multisort( $hierarchy_no, SORT_DESC, SORT_NUMERIC, $slug, SORT_ASC, SORT_STRING, $temp_slugs );
			$cate_slugs   = array_column( $temp_slugs, 'slug' );
			return $cate_slugs;
		}

		/**
		 * 固定ページのスラッグ取得
		 * 固定ページのスラッグは、親子関係（祖先）の固定ページをそれぞれ取得します。
		 * @param  int    $page_id   固定ページのID
		 * @param  string $page_slug 固定ページのスラッグ
		 * @return array             親子関係（祖先）の固定ページのスラッグ群
		 */
		private function get_pages_slug( $page_id, $page_slug ) : array {
			$slugs[]    = self::PREFIX_PAGE . $page_slug;
			$page_slugs = array();
			$temp_slugs = array();
			$page_ids   = get_post_ancestors( $page_id );
			if ( $page_ids ) {
				// 親があれば自身のみのスラッグは削除
				$slugs = array();
				// 親→子の順へ変換
 				$page_ids = array_reverse( $page_ids );
				$slug = '';
				foreach ( $page_ids as $page_id ) {
					// 親子関係（祖先）固定ページのスラッグ取得
					$post = get_post( $page_id );
					$slug = $slug . ('' !== $slug ? '-' : '') . urldecode( $post->post_name );
					$temp_slugs[] = self::PREFIX_PAGE . $slug;
				}
				if ( '' !== $slug ) {
					$temp_slugs[] = self::PREFIX_PAGE . $slug . ('' !== $slug ? '-' : '') . $page_slug;
				}
				// 階層が深い方から浅い方へ、スラッグの昇順で並び替える
				$page_slugs = array_reverse( $temp_slugs );
			}
			return array_merge( $slugs, $page_slugs );
		}

		/**
		 * カラー情報ファイルは"色情報のみを保存する"オプションが適用されているかの判定
		 * カラー情報ファイル内に"site_font_family"～"page_breadcrumbs_position"の項目が一つも定義されていないかを判定します。
		 * @param  array $json_data     カラー情報ファイル内のキー名
		 * @return bool true："色情報のみを保存する"オプションが適用されている、false："色情報のみを保存する"オプションが適用されていない
		 */
		private function is_color_use_only( $json_data ) : bool {
			$check_keys = [ 'site_font_family', 'site_font_size', 'site_font_weight', 'comment_information_message', 'site_date_format', 
											'header_area_height', 'mobile_header_area_height', 'header_layout_type', 'tagline_position', 'notice_type', 
											'sidebar_position', 'appeal_area_display_type', 'sidebar_display_type', 'footer_display_type', 'category_tag_display_type', 
											'category_tag_display_position', 'related_entry_type', 'related_entry_count', 'post_navi_type', 'single_breadcrumbs_position', 
											'page_breadcrumbs_position' ];
			foreach ( $check_keys as $key => $value ) {
				if ( array_key_exists( $value, $json_data ) ) {
					return false;
				}
			}
			return true;
		}

		/**
		 * 管理画面の表示
		 */
		public function show_form() : void {
			$default_json_data        = array();
			$color_info_json_data     = array();
			$output_json_data         = array();
			$mainte_ip_addresses      = '';
			$current_color_name       = '';
			$current_darkmode_name    = '';
			$current_maintenance_name = '';
			$current_color_index      = -1;
			$wp_cron_called           = false;
			
			// デフォルト定義ファイルの取得
			$default_json_data = $this->read_json_file( plugin_dir_path( __FILE__ ) . self::FOLDER_NAME_DEFAULTS . '/' . self::FILE_COLOR_DEFAULT_JSON );
			// カレントカラー情報の取得
			$color_info_json_data = $this->read_json_file( $this->scenery_color_info_json_path );
			// アクセス元IPアドレスの取得
			$my_ip_address = $this->get_remote_ip_addr();
			
			// -------------------------------
			// POST処理
			// -------------------------------
			if ( isset( $_POST[ self::HIDDEN_MAINTE_SETTING ] ) &&
			   wp_verify_nonce( $this->sanitize_http_post_field( self::HIDDEN_MAINTE_SETTING ), self::VERIFY_NONCE_STRING ) ) {
				// -------------------------------
				// ［メンテナンスモードの設定］処理
				// -------------------------------
				// モード設定の表示状態取得
				$mode_setting_visibled = $this->sanitize_http_post_field_boolean( self::HIDDEN_MODE_SETTING_VISIBLED );
				$color_info_json_data[ self::MODE_SETTING_VISIBLED ] = $mode_setting_visibled;
				// メンテナンスモード適用ON/OFF取得＆格納
				$color_info_json_data[ self::MAINTENANCE_MODE ] = isset( $_POST[ self::CHECKBOX_MAINTE_SETTING ] );
				// サイト確認用IPアドレスの取得＆格納
				$color_info_json_data[ self::MAINTE_IP_ADDRESSES ] = $this->get_valid_ip_address( preg_replace("/( |　)/", "", $this->sanitize_http_post_field( self::TEXTBOX_IP_ADDRESSES ) ) );
				$this->write_scenery_color_info_file( $color_info_json_data );
				$this->print_options_saved_message();
			} elseif ( isset( $_POST[ self::HIDDEN_DARKMODE_SETTING ] ) &&
			   wp_verify_nonce( $this->sanitize_http_post_field( self::HIDDEN_DARKMODE_SETTING ), self::VERIFY_NONCE_STRING ) ) {
				// -------------------------------
				// ［ダークモードの設定］処理
				// -------------------------------
				// モード設定の表示状態取得
				$mode_setting_visibled = $this->sanitize_http_post_field_boolean( self::HIDDEN_MODE_SETTING_VISIBLED );
				$color_info_json_data[ self::MODE_SETTING_VISIBLED ] = $mode_setting_visibled;
				// ダークモード有効化ON/OFF取得＆格納
				$color_info_json_data[ self::DARKMODE_MODE ] = isset( $_POST[ self::CHECKBOX_DARKMODE_SETTING ] );
				$this->write_scenery_color_info_file( $color_info_json_data );
				$this->print_options_saved_message();
			} elseif ( isset( $_POST[ self::HIDDEN_SLUGMODE_SETTING ] ) &&
			   wp_verify_nonce( $this->sanitize_http_post_field( self::HIDDEN_SLUGMODE_SETTING ), self::VERIFY_NONCE_STRING ) ) {
				// -------------------------------
				// ［スラッグモードの設定］処理
				// -------------------------------
				// モード設定の表示状態取得
				$mode_setting_visibled = $this->sanitize_http_post_field_boolean( self::HIDDEN_MODE_SETTING_VISIBLED );
				$color_info_json_data[ self::MODE_SETTING_VISIBLED ] = $mode_setting_visibled;
				// スラッグモード有効化ON/OFF取得＆格納
				$color_info_json_data[ self::SLUG_MODE ] = isset( $_POST[ self::CHECKBOX_SLUGMODE_SETTING ] );
				$this->write_scenery_color_info_file( $color_info_json_data );
				$this->print_options_saved_message();
			} elseif ( isset( $_POST[ self::HIDDEN_SITE_COLOR_CHANGE ] ) &&
			   isset( $_POST[ self::COLOR_THEME_SELECT_COLOR ] ) &&
			   wp_verify_nonce( $this->sanitize_http_post_field( self::HIDDEN_SITE_COLOR_CHANGE ), self::VERIFY_NONCE_STRING ) ) {
				// -------------------------------
				// ［サイトカラーの変更］処理
				// -------------------------------
				// モード設定の表示状態取得
				$mode_setting_visibled = $this->sanitize_http_post_field_boolean( self::HIDDEN_MODE_SETTING_VISIBLED );
				// カラー情報ファイルのパス組み立て
				$color_file = $this->plugin_options_dir . $this->sanitize_http_post_field( self::COLOR_THEME_SELECT_COLOR );
				if ( file_exists( $color_file ) ) {
					// カラー設定の保存
					$this->update_option( $color_file );
					$this->print_options_saved_message();
				}
				// カラー名の取得
				$change_color_name = basename( $this->sanitize_http_post_field( self::COLOR_THEME_SELECT_COLOR ), self::JSON_FILE_EXTENSION );
				// カレントカラーの格納
				$color_info_json_data[ self::CURRENT_COLOR_NAME ]    = $change_color_name;
				$color_info_json_data[ self::MODE_SETTING_VISIBLED ] = $mode_setting_visibled;
				// JSON形式データのファイルへの書き込み
				$this->write_scenery_color_info_file( $color_info_json_data );
			} elseif ( isset( $_POST[ self::HIDDEN_COLOR_CREATE ] ) &&
				isset( $_POST[ self::INPUT_NEW_COLOR_NAME ] ) &&
				wp_verify_nonce( $this->sanitize_http_post_field( self::HIDDEN_COLOR_CREATE ), self::VERIFY_NONCE_STRING ) ) {
				// -------------------------------
				// ［カラーの作成］処理
				// -------------------------------
				// モード設定の表示状態取得
				$mode_setting_visibled = $this->sanitize_http_post_field_boolean( self::HIDDEN_MODE_SETTING_VISIBLED );
				// POSTデータの取得
				foreach ( $default_json_data as $array_data ) {
					$output_json_data[ $array_data['id'] ] = $this->strip_slashes( $this->sanitize_http_post_field( $array_data['id'] . self::KEY_ADD_STRING ) );
				}
				// 値無しチェックボックスがONの場合は値を削除
				if ( isset( $_POST[ self::CHECKBOX_NAME_NO_VALUE_CHECKBOXES ] ) ) {
					foreach ( $this->sanitize_http_post_field( self::CHECKBOX_NAME_NO_VALUE_CHECKBOXES ) as $id ) {
						$output_json_data[ $id ] = '';
					}
				}
				
				// 色情報のみを保存する場合は色情報以外を削除
				if ( isset( $_POST[ self::CHECKBOX_COLOR_ONLY_SAVE ] ) ) {
					foreach ( $default_json_data as $array_data ) {
						if ( $array_data['cocoon_option'] && self::INPUT_TYPE_ATTR_COLOR !== $array_data['attr'] ) {
							unset( $output_json_data[ $array_data['id'] ] );
						}
					}
					// 「色情報のみ保存する」は有効とする
					$output_json_data[ self::KEY_COLOR_ONLY_SAVE ] = '1';
				} else {
					// 「色情報のみ保存する」は無効とする
					$output_json_data[ self::KEY_COLOR_ONLY_SAVE ] = '';
				}

				// カラー名の取得
				$create_color_name = $this->sanitize_http_post_field( self::INPUT_NEW_COLOR_NAME );
				// ファイル名の構築
				$new_file_name = $this->plugin_options_dir . $create_color_name . self::JSON_FILE_EXTENSION;
				// カラー作成できたものはファイル削除を有効とする
				$output_json_data[ self::KEY_OVERWRITE_COLOR_FILE ] = '1';
				$exist_file = file_exists( $new_file_name );
				// JSON形式データのファイルへの書き込み
				$this->write_json_file( $new_file_name, $output_json_data );

				// サイトカラーの変更
				if ( isset( $_POST[ self::CHECKBOX_SITE_COLOR_CHANGE ] ) ) {
					// カラー設定の保存
					$this->update_option( $new_file_name );
					$this->print_options_saved_message();
					// カレントカラーの格納
					$color_info_json_data[ self::CURRENT_COLOR_NAME ] = $create_color_name;
				}
				$color_info_json_data[ self::MODE_SETTING_VISIBLED ] = $mode_setting_visibled;
				// JSON形式データのファイルへの書き込み
				$this->write_scenery_color_info_file( $color_info_json_data );
				$this->print_color_created_message( $exist_file );
			} elseif ( isset( $_POST[ self::HIDDEN_SLUGMODE ] ) &&
			  isset( $_POST[ self::CURRENT_COLOR_NAME ] ) &&
			  wp_verify_nonce( $this->sanitize_http_post_field( self::HIDDEN_SLUGMODE ), self::VERIFY_NONCE_STRING ) ) {
				// -------------------------------
				// ［スラッグモードでの使用／解除］処理
				// -------------------------------
				// モード設定の表示状態取得
				$mode_setting_visibled = $this->sanitize_http_post_field_boolean( self::HIDDEN_MODE_SETTING_VISIBLED );
				// 選択されたカラー名の取得
				$slugmode_color_name = $this->sanitize_http_post_field( self::CURRENT_COLOR_NAME );
				// スラッグモードとして使用する場合
				if ( isset( $_POST[ self::CHECKBOX_SLUGMODE_USE ] ) ) {
					// 稼働中のwp-cronプロセスの有無確認
					$another_running_process = $this->check_another_cron_process_running();
					if ( '' !== $another_running_process ) {
						$this->print_cron_running_message( $another_running_process );
					} else {
						// カラー情報ファイルのパス組み立て
						$color_file = $this->plugin_options_dir . $slugmode_color_name . self::JSON_FILE_EXTENSION;
						if ( file_exists( $color_file ) ) {
							// カラー設定の保存
							$this->update_option( $color_file );
						}
						// JSON形式データのファイルへの書き込み
						$color_info_json_data[ self::MODE_SETTING_VISIBLED ] = $mode_setting_visibled;
						// カレントカラー情報ファイルの更新
						$this->write_scenery_color_info_file( $color_info_json_data );

						// スラッグモード用CSSファイルの作成
						// wp-cronの実行
						if ( $this->do_cron_schedule_event( $slugmode_color_name ) ) {
							$wp_cron_called = true;
							// 空のスラッグモード用CSSファイル作成
							// 本来のスラッグモード用CSSファイルはcron処理で作成されるので、
							// ファイルの有無をこの後で判断する場合に間に合わない為に作成します。
							touch( $this->plugin_options_dir . $slugmode_color_name . self::CSS_FILE_EXTENSION );
							$this->print_options_saved_message();
						} else {
							$this->print_cron_running_message();
						}
					}
				} else {
					// スラッグモードでの使用を解除する場合
					// スラッグモード用CSSファイルの削除
					$css_file = $this->plugin_options_dir . $slugmode_color_name . self::CSS_FILE_EXTENSION;
					if ( file_exists( $css_file ) ) {
						$this->delete_file( $css_file );
						// スラッグモード用CSSファイルの有無取得
						$exist_slug_files = $this->get_slug_css_files();
						if ( ! $exist_slug_files ) {
							// スラッグモード用CSSファイルが1ファイルも無い場合は［スラッグモード設定］欄を無効化する
							$color_info_json_data[ self::SLUG_MODE ]             = false;
							$color_info_json_data[ self::MODE_SETTING_VISIBLED ] = $mode_setting_visibled;
							// カレントカラー情報ファイルの更新
							$this->write_scenery_color_info_file( $color_info_json_data );
						}
						$this->print_options_saved_message();
					}
				}
			} elseif ( isset( $_POST[ self::HIDDEN_DARKMODE ] ) &&
			  isset( $_POST[ self::CURRENT_COLOR_NAME ] ) &&
			  wp_verify_nonce( $this->sanitize_http_post_field( self::HIDDEN_DARKMODE ), self::VERIFY_NONCE_STRING ) ) {
				// -------------------------------
				// ［ダークモードの使用／解除］処理
				// -------------------------------
				// モード設定の表示状態取得
				$mode_setting_visibled = $this->sanitize_http_post_field_boolean( self::HIDDEN_MODE_SETTING_VISIBLED );
				// 選択されたカラー名の取得
				$darkmode_color_name = $this->sanitize_http_post_field( self::CURRENT_COLOR_NAME );
				// ダークモードとして使用する場合
				if ( isset( $_POST[ self::CHECKBOX_DARKMODE_USE ] ) ) {
					// 稼働中のwp-cronプロセスの有無確認
					$another_running_process = $this->check_another_cron_process_running();
					if ( '' !== $another_running_process ) {
						$this->print_cron_running_message( $another_running_process );
					} else {
						// カラー情報ファイルのパス組み立て
						$color_file = $this->plugin_options_dir . $darkmode_color_name . self::JSON_FILE_EXTENSION;
						if ( file_exists( $color_file ) ) {
							// カラー設定の保存
							$this->update_option( $color_file );
						}
						// JSON形式データのファイルへの書き込み
						$color_info_json_data[ self::CURRENT_DARKMODE_NAME ] = $darkmode_color_name;
						$color_info_json_data[ self::MODE_SETTING_VISIBLED ] = $mode_setting_visibled;
						// カレントカラー情報ファイルの更新
						$this->write_scenery_color_info_file( $color_info_json_data );

						// ダークモード用CSSファイルの作成
						// wp-cronの実行
						if ( $this->do_cron_schedule_event( self::CURRENT_DARKMODE_NAME ) ) {
							$wp_cron_called = true;
							$this->print_options_saved_message();
						} else {
							$this->print_cron_running_message();
						}
					}
				} else {
					// ダークモードを解除する場合
					$current_darkmode_name = $this->get_array_key_exists( self::CURRENT_DARKMODE_NAME, $color_info_json_data );
					// ［適用する］チェックボックスがOFFへ変更されている場合はダークモードを解除
					if ( $current_darkmode_name === $darkmode_color_name ) {
						// CSSファイルの削除
						$this->delete_file( $this->plugin_options_dir . self::FILE_CSCP_DARKMODE_CSS );
						// カレントカラー情報ファイルの更新
						$color_info_json_data[ self::CURRENT_DARKMODE_NAME ] = '';
						$color_info_json_data[ self::DARKMODE_MODE ]         = false;
						$color_info_json_data[ self::MODE_SETTING_VISIBLED ] = $mode_setting_visibled;
					// JSON形式データのファイルへの書き込み
						$this->write_scenery_color_info_file( $color_info_json_data );
						$this->print_options_saved_message();
					}
				}
			} elseif ( isset( $_POST[ self::HIDDEN_MAINTENANCE ] ) &&
			  isset( $_POST[ self::CURRENT_COLOR_NAME ] ) &&
			  wp_verify_nonce( $this->sanitize_http_post_field( self::HIDDEN_MAINTENANCE ), self::VERIFY_NONCE_STRING ) ) {
				// -------------------------------
				// ［メンテナンスモードでの使用／解除］処理
				// -------------------------------
				// モード設定の表示状態取得
				$mode_setting_visibled = $this->sanitize_http_post_field_boolean( self::HIDDEN_MODE_SETTING_VISIBLED );
				// 選択されたカラー名の取得
				$maintenance_color_name = $this->sanitize_http_post_field( self::CURRENT_COLOR_NAME );
				// メンテナンスモードとして使用する場合
				if ( isset( $_POST[ self::CHECKBOX_MAINTENANCE_USE ] ) ) {
					// 稼働中のwp-cronプロセスの有無確認
					$another_running_process = $this->check_another_cron_process_running();
					if ( '' !== $another_running_process ) {
						$this->print_cron_running_message( $another_running_process );
					} else {
						// カラー情報ファイルのパス組み立て
						$color_file = $this->plugin_options_dir . $maintenance_color_name . self::JSON_FILE_EXTENSION;
						if ( file_exists( $color_file ) ) {
							// カラー設定の保存
							$this->update_option( $color_file );
						}
						// JSON形式データのファイルへの書き込み
						$color_info_json_data[ self::CURRENT_MAINTENANCE_NAME ] = $maintenance_color_name;
						$color_info_json_data[ self::MODE_SETTING_VISIBLED ]    = $mode_setting_visibled;
						$this->write_scenery_color_info_file( $color_info_json_data );

						// メンテナンスモード用CSSファイルの作成
						// wp-cronの実行
						if ( $this->do_cron_schedule_event( self::CURRENT_MAINTENANCE_NAME ) ) {
							$wp_cron_called = true;
							$this->print_options_saved_message();
						} else {
							$this->print_cron_running_message();
						}
					}
				} else {
					// メンテナンスモードを解除する場合
					$current_maintenance_name = $this->get_array_key_exists( self::CURRENT_MAINTENANCE_NAME, $color_info_json_data );
					// ［適用する］チェックボックスがOFFへ変更されている場合はメンテナンスモードを解除
					if ( $current_maintenance_name === $maintenance_color_name ) {
						// CSSファイルの削除
						$this->delete_file( $this->plugin_options_dir . self::FILE_CSCP_MAINTEMODE_CSS );
						// カレントカラー情報ファイルの更新
						$color_info_json_data[ self::CURRENT_MAINTENANCE_NAME ] = '';
						$color_info_json_data[ self::MAINTENANCE_MODE ]         = false;
						$color_info_json_data[ self::MODE_SETTING_VISIBLED ]    = $mode_setting_visibled;
						// JSON形式データのファイルへの書き込み
						$this->write_scenery_color_info_file( $color_info_json_data );
						$this->print_options_saved_message();
					}
				}
			} elseif ( isset( $_POST[ self::HIDDEN_COLOR_DELETE ] ) &&
			  isset( $_POST[ self::CURRENT_COLOR_NAME ] ) &&
			  wp_verify_nonce( $this->sanitize_http_post_field( self::HIDDEN_COLOR_DELETE ), self::VERIFY_NONCE_STRING ) ) {
				// -------------------------------
				// ［カラーの削除］処理
				// -------------------------------
				// モード設定の表示状態取得
				$mode_setting_visibled = $this->sanitize_http_post_field_boolean( self::HIDDEN_MODE_SETTING_VISIBLED );
				// カラー名の取得
				$delete_color_name = $this->sanitize_http_post_field( self::CURRENT_COLOR_NAME );
				// ファイルの削除
				$this->delete_file( $this->plugin_options_dir . $delete_color_name . self::JSON_FILE_EXTENSION );
				// カレントカラー情報の削除
				$is_write = false;
				// カレントカラーの削除
				if ( $delete_color_name === $color_info_json_data[ self::CURRENT_COLOR_NAME ] ) {
					$color_info_json_data[ self::CURRENT_COLOR_NAME ] = '';
					$color_info_json_data[ self::MODE_SETTING_VISIBLED ] = $mode_setting_visibled;
					$is_write = true;
				}
				// ダークモード用カラーの削除
				if ( $delete_color_name === $this->get_array_key_exists( self::CURRENT_DARKMODE_NAME, $color_info_json_data ) ) {
					$color_info_json_data[ self::CURRENT_DARKMODE_NAME ] = '';
					$color_info_json_data[ self::DARKMODE_MODE ] = false;
					$is_write = true;
					$this->delete_file( $this->plugin_options_dir . self::FILE_CSCP_DARKMODE_CSS );
				}
				// メンテナンスモード用カラーの削除
				if ( $delete_color_name === $this->get_array_key_exists( self::CURRENT_MAINTENANCE_NAME, $color_info_json_data ) ) {
					$color_info_json_data[ self::CURRENT_MAINTENANCE_NAME ] = '';
					$color_info_json_data[ self::MAINTENANCE_MODE ] = false;
					$is_write = true;
					$this->delete_file( $this->plugin_options_dir . self::FILE_CSCP_MAINTEMODE_CSS );
				}
				// スラッグモード用カラーであればCSSファイル削除
				if ( $this->is_slugmode_color_file( $delete_color_name ) ) {
					// CSSファイルの削除
					$css_file = $this->plugin_options_dir . $delete_color_name . self::CSS_FILE_EXTENSION;
					if ( file_exists( $css_file ) ) {
						$this->delete_file( $css_file );
						// スラッグモード用CSSファイルの有無取得
						$exist_slug_files = $this->get_slug_css_files();
						if ( ! $exist_slug_files ) {
							// スラッグモード用CSSファイルが1ファイルも無い場合は［スラッグモード設定］欄を無効化する
							$color_info_json_data[ self::SLUG_MODE ] = false;
							// カレントカラー情報ファイルの更新
							$is_write = true;
						}
					}
				}
				if ( $is_write ) {
					// JSON形式データのファイルへの書き込み
					$this->write_scenery_color_info_file( $color_info_json_data );
				}
				$this->print_color_deleted_message();
			} elseif ( isset( $_POST[ self::HIDDEN_RESTORE ] ) && $_FILES['options']['name'] != '' &&
			  wp_verify_nonce( $this->sanitize_http_post_field( self::HIDDEN_RESTORE ), self::VERIFY_NONCE_STRING ) ) {
				// -------------------------------
				// ［リストア］処理
				// -------------------------------
				// アップロードファイル上限サイズの取得
				$upload_max_file_size = $this->get_upload_max_file_size( $color_info_json_data );
				// アップロード処理
	 			$zip_result = $this->do_restore_upload( $upload_max_file_size );
	 			if ( $zip_result ) {
					$this->print_restore_message();
	 			} else {
					$this->print_restore_error_message();
	 			}
			}

			// theme_modsデータの取得
			$in_theme_mods      = $this->get_color_in_theme_mods( $default_json_data );
			$in_theme_mods_pure = $in_theme_mods;
			if ( isset( $in_theme_mods ) ) {
				// theme_modsデータ値がカレントカラー情報ファイルと一致しない場合はリロード表示とする
				$theme_mods_description = $this->is_theme_mods_latest( $in_theme_mods, $color_info_json_data[ self::CURRENT_COLOR_NAME ] ) ?
																	self::THEME_MODS_FILE_COLOR_DESCRIPTION : 
																	($wp_cron_called ? self::THEME_MODS_FILE_COLOR_DESCRIPTION_DOUBT : self::THEME_MODS_FILE_COLOR_DESCRIPTION);
				// カラー説明の追加
				$in_theme_mods[ self::KEY_COLOR_DESCRIPTION ] = $this->get_esc_translate( $theme_mods_description );
				// カラーファイルはREAD ONLYとする
				$in_theme_mods[ self::KEY_OVERWRITE_COLOR_FILE ] = '';
				// JSON形式データのファイルへの書き込み
				$this->write_json_file( $this->plugin_options_dir . self::THEME_MODS_FILE . self::JSON_FILE_EXTENSION, $in_theme_mods );
			}

			// カラー情報ファイル(JSON)の読込み
			$data_array = array();
			$files      = glob( $this->plugin_options_dir . '*' . self::JSON_FILE_EXTENSION );

			if ( $files ) {
				foreach ( $files as $file ) {
					// プレビュー画像ファイル名の取得
					$preview_files = glob( $this->plugin_options_dir . basename( $file, self::JSON_FILE_EXTENSION ) . '.{jpeg,jpg,png}', GLOB_BRACE );
					$preview_file  = '';
					if ( ! empty( $preview_files ) ) {
						$preview_file = plugins_url( self::FOLDER_NAME_OPTIONS . '/' . basename( $preview_files[0] ), __FILE__ );
					}
					// JSON形式ファイルの取得
					$json_data = $this->read_json_file( $file );
					// "色情報のみを保存する"項目が無い場合は追加
					if ( ! array_key_exists( self::KEY_COLOR_ONLY_SAVE, $json_data ) ) {
						// カラー情報ファイルに"色情報のみを保存する"オプションが適用されているかを判定
						$json_data[ self::KEY_COLOR_ONLY_SAVE ] = $this->is_color_use_only( $json_data );
					}
					// カラー情報ファイルで定義されていない名前(キー)はtheme_modsデータの値とします
					$this->fill_json_data( $in_theme_mods_pure, $json_data );
					// "カラーの名称"が無い場合は追加
					$color_name = basename( $file, self::JSON_FILE_EXTENSION );
					if ( ! array_key_exists( self::INPUT_COLOR_NAME, $json_data ) ) {
						$json_data[ self::INPUT_COLOR_NAME ] = $color_name;
					}
					$color_data   = array(
						self::COLOR_FILE_NAME    => basename( $file ),
						self::INPUT_COLOR_NAME   => $color_name,
						self::COLOR_PREVIEW_FILE => $preview_file,
						self::COLOR_JSON_DATA    => $json_data,
					);
					$data_array[] = $color_data;
				}

				// カレントカラーの取得
				$current_color_name       = '';
				$maintenance_mode         = false;
				$darkmode_mode            = false;
				$slugmode_mode            = false;
				$mainte_ip_addresses      = '';
				$current_darkmode_name    = '';
				$current_maintenance_name = '';
				$mode_setting_visibled    = true;
				$upload_max_file_size     = self::SIZE_MB * self::INPUT_MAX_FILE_SIZE_MB;
				$file = $this->scenery_color_info_json_path;
				if ( file_exists( $file ) ) {
					$color_info_json_data     = $this->read_json_file( $this->scenery_color_info_json_path );
					$current_color_name       = $color_info_json_data[ self::CURRENT_COLOR_NAME ];
					$maintenance_mode         = $this->get_array_key_exists( self::MAINTENANCE_MODE, $color_info_json_data );
					$darkmode_mode            = $this->get_array_key_exists( self::DARKMODE_MODE, $color_info_json_data );
					$slugmode_mode            = $this->get_array_key_exists( self::SLUG_MODE, $color_info_json_data );
					$mainte_ip_addresses      = $this->get_array_key_exists( self::MAINTE_IP_ADDRESSES, $color_info_json_data );
					$current_darkmode_name    = $this->get_array_key_exists( self::CURRENT_DARKMODE_NAME, $color_info_json_data );
					$current_maintenance_name = $this->get_array_key_exists( self::CURRENT_MAINTENANCE_NAME, $color_info_json_data );
					$mode_setting_visibled    = $this->get_array_key_exists( self::MODE_SETTING_VISIBLED, $color_info_json_data, $mode_setting_visibled );
					$upload_max_file_size     = $this->get_upload_max_file_size( $color_info_json_data );
				}
				for ( $index = 0; $index < count( $data_array ); $index++ ) {
					if ( $current_color_name === $data_array[ $index ][ self::INPUT_COLOR_NAME ] ) {
						$current_color_index = $index;
						break;
					}
				}
				// JavaScript用
				printf( '<input type="hidden" id="%s" value="%s">', esc_attr( self::CURRENT_COLOR_INDEX ),      esc_attr( $current_color_index ) );
				printf( '<input type="hidden" id="%s" value="%s">', esc_attr( self::CURRENT_DARKMODE_NAME ),    esc_attr( $current_darkmode_name ) );
				printf( '<input type="hidden" id="%s" value="%s">', esc_attr( self::CURRENT_MAINTENANCE_NAME ), esc_attr( $current_maintenance_name ) );

				// JSONデータをHTMLとして出力
				$this->print_script_json_data( $default_json_data, self::ELEMENT_ID_DEFAULT_JSON_DATA );
				$this->print_script_json_data( $data_array, self::ELEMENT_ID_JSON_DATA );
				$this->print_script_json_data( $this->mo_data, self::ELEMENT_ID_MO_DATA );
				// スラッグモード用CSSファイルのファイル名（カラー名）取得
				$slug_files = $this->get_slug_css();
				$this->print_script_json_data( $slug_files, self::ELEMENT_ID_SLUGMODE_FILES );
				// スラッグモード用CSSファイルの有無取得
				$exist_slug_files = $this->get_slug_css_files();
				?>

		<div class="wrap admin-settings">
		  <h1><?php $this->echo_esc_translate( 'サイトカラー設定' ); ?></h1>
		  <div class="metabox-holder">
			<div id="<?php echo esc_attr( self::VERIFY_NONCE_STRING ); ?>" class="postbox">
			  <h2 class="hndle"><?php $this->echo_esc_translate( 'サイトカラーの設定' ); ?></h2>
			  <div class="inside">
				<p><?php $this->echo_esc_translate( 'サイトのカラーを設定します。' ); ?><br />
				   <?php $this->echo_esc_translate( 'Cocoonで定義しているカラーなどの情報を簡単に設定することができます。' ); ?><br />
				   <?php $this->echo_esc_translate( '設定したカラーなどの情報をファイルとして保存することができます。' ); ?><br />
				   <?php $this->echo_esc_translate( 'ファイルとして保存した情報をもとにあらたなファイルを作成することもできます。' ); ?><br />
				   <?php $this->echo_esc_translate( '※Cocoon設定の一部が変更されますので、事前にバックアップファイルを取得してください。' ); ?><br />
					<?php
					// スキン制御確認
					if ( $this->has_skin_option( $default_json_data ) ) {
						printf( '<strong>%s</strong>', $this->get_esc_translate( 'スキン制御:' ) );
						$this->echo_esc_translate( '※スキンでCocoon設定値を制御している場合は、実際にサイトを表示する際にはここでの設定値よりもスキンの設定値が優先して使われます。' );
					}
					?>
				</p>
				<table class="<?php echo esc_attr( self::CLASS_CSCP_FORM_TABLE ); ?>">
				  <tbody>
					<?php
					if ( ! empty( $files ) ) {
						$form_attr_mainte_setteing   = sprintf( 'form="%s"', esc_attr( self::FORM_ID_FORM_MAINTE_SETTING ) );
						$form_attr_darkmode_setteing = sprintf( 'form="%s"', esc_attr( self::FORM_ID_FORM_DARKMODE_SETTING ) );
						$form_attr_slugmode_setteing = sprintf( 'form="%s"', esc_attr( self::FORM_ID_FORM_SLUGMODE_SETTING ) );
						$form_attr_change            = sprintf( 'form="%s"', esc_attr( self::FORM_ID_FORM_COLOR_CHANGE ) );
						$form_attr_create            = sprintf( 'form="%s"', esc_attr( self::FORM_ID_FORM_COLOR_CREATE ) );
						$form_attr_darkmode          = sprintf( 'form="%s"', esc_attr( self::FORM_ID_FORM_COLOR_DARKMODE ) );
						$form_attr_slugmode          = sprintf( 'form="%s"', esc_attr( self::FORM_ID_FORM_COLOR_SLUGMODE ) );
						$form_attr_maintenance       = sprintf( 'form="%s"', esc_attr( self::FORM_ID_FORM_COLOR_MAINTENANCE ) );
						$form_attr_delete            = sprintf( 'form="%s"', esc_attr( self::FORM_ID_FORM_COLOR_DELETE ) );
						?>
					<form action="" method="POST" id="<?php echo esc_attr( self::FORM_ID_FORM_MAINTE_SETTING ); ?>"></form>
					<form action="" method="POST" id="<?php echo esc_attr( self::FORM_ID_FORM_DARKMODE_SETTING ); ?>"></form>
					<form action="" method="POST" id="<?php echo esc_attr( self::FORM_ID_FORM_SLUGMODE_SETTING ); ?>"></form>
					<form action="" method="POST" id="<?php echo esc_attr( self::FORM_ID_FORM_COLOR_CHANGE ); ?>"></form>
					<form action="" method="POST" id="<?php echo esc_attr( self::FORM_ID_FORM_COLOR_CREATE ); ?>"></form>
					<form action="" method="POST" id="<?php echo esc_attr( self::FORM_ID_FORM_COLOR_DARKMODE ); ?>"></form>
					<form action="" method="POST" id="<?php echo esc_attr( self::FORM_ID_FORM_COLOR_SLUGMODE ); ?>"></form>
					<form action="" method="POST" id="<?php echo esc_attr( self::FORM_ID_FORM_COLOR_MAINTENANCE ); ?>"></form>
					<form action="" method="POST" id="<?php echo esc_attr( self::FORM_ID_FORM_COLOR_DELETE ); ?>"></form>

					<tr>
					  <th scope="row"><?php generate_label_tag( 'none', $this->get_esc_translate( 'モード設定' ) ); ?></th>
						<td>
						<input type="checkbox" <?php echo $mode_setting_visibled ? 'checked' : ''; ?> id="<?php echo esc_attr( self::CHECKBOX_MODE_SETTING_VISIBLED ); ?>" name="<?php echo esc_attr( self::CHECKBOX_MODE_SETTING_VISIBLED ); ?>" title="<?php $this->echo_esc_translate( 'モード設定欄を表示します。' ); ?>">
						<?php generate_label_tag( esc_attr( self::CHECKBOX_MODE_SETTING_VISIBLED ), $this->get_esc_translate( 'モード設定欄の表示' ) ); ?>
						<?php $this->my_generate_tips_tag( 'メンテナンスモード、ダークモード、スラッグモードの各設定欄を表示します。' ); ?>
						</td>
					</tr>
					<?php printf( '<tr class="%s">', esc_attr( self::CLASS_MODE_SETTING_VISIBLED ) ); ?>
					  <th scope="row"><?php generate_label_tag( '', $this->get_esc_translate( 'メンテナンスモード設定' ) ); ?></th>
						<td>
						<p class="setting_read"><?php $this->echo_esc_translate( 'メンテナンスモードで操作することができます。' ); ?>
					  <?php echo get_help_page_tag( 'https://lacaraterra.site/cocoon-scenery-color-plugin/#maintemode' ); ?></p>
							<div>
							<input type="checkbox" <?php echo $maintenance_mode ? 'checked' : ''; ?> <?php echo '' === $current_maintenance_name ? 'disabled="disabled"' : ''; ?> id="<?php echo esc_attr( self::CHECKBOX_MAINTE_SETTING ); ?>" name="<?php echo esc_attr( self::CHECKBOX_MAINTE_SETTING ); ?>" <?php echo $form_attr_mainte_setteing; ?> title="<?php $this->echo_esc_translate( '解除する場合はチェックを外してください。' ); ?>">
							<?php generate_label_tag( esc_attr( self::CHECKBOX_MAINTE_SETTING ), $this->get_esc_translate( '適用する　※メンテナンスモードで使用するカラー名：' ) ); ?>
							<?php 
							if ( '' !== $current_maintenance_name ) {
								echo esc_attr( $current_maintenance_name ); 
							} else {
								$this->echo_esc_translate( '指定されていません。［メンテナンスモードでの使用/解除］ボタン操作で指定してください。' );
							}
							?>
							<?php $this->my_generate_tips_tag( 'メンテナンスモードを適用するか、適用を解除します。' ); ?>
							</div>
							<div>
							<?php $this->echo_esc_translate( 'サイト確認用IPアドレス：' ) ?>
							<input type="text" class="colorbar" <?php echo '' === $current_maintenance_name ? 'readonly' : ''; ?> id="<?php echo esc_attr( self::TEXTBOX_IP_ADDRESSES ); ?>" name="<?php echo esc_attr( self::TEXTBOX_IP_ADDRESSES ); ?>" value="<?php echo esc_attr( $mainte_ip_addresses ); ?>" <?php echo $form_attr_mainte_setteing; ?> title="<?php $this->echo_esc_translate( '複数のIPアドレスをカンマ（,）区切りで入力することができます。' ); ?>" >
							</div>
							<div>
							<?php $this->echo_esc_translate( '（参考）この管理画面にアクセスしている今のIPアドレス：' ) ?>
							<input type="text" readonly value="<?php echo esc_attr( $my_ip_address ); ?>" title="<?php $this->echo_esc_translate( 'このIPアドレスを［サイト確認用IPアドレス］へコピーしてください。' ); ?>">
							<?php $this->my_generate_tips_tag( '設定したIPアドレスからアクセスされた場合は、メンテナンスモード用のCSSを出力しないため、編集中のカラー内容を確認することができます。' ); ?>
							</div>
							<div>
							<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_MODE_SETTING_VISIBLED ); ?>" <?php echo $form_attr_mainte_setteing; ?>>
							<input type="hidden" name="<?php echo esc_attr( self::MAINTE_IP_ADDRESSES ); ?>" id="<?php echo esc_attr( self::MAINTE_IP_ADDRESSES ); ?>" value="<?php echo esc_attr( $mainte_ip_addresses ); ?>" <?php echo $form_attr_mainte_setteing; ?>>
							<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_MAINTE_SETTING ); ?>" value="<?php echo wp_create_nonce( self::VERIFY_NONCE_STRING ); ?>" <?php echo $form_attr_mainte_setteing; ?>>
							<input type="submit" class="button" <?php echo '' === $current_maintenance_name ? 'disabled="disabled"' : ''; ?> id="<?php echo esc_attr( self::BUTTON_MAINTE_SETTING ); ?>" value="<?php $this->echo_esc_translate( 'メンテナンスモードの適用/解除' ); ?>" <?php echo $form_attr_mainte_setteing; ?> title="<?php $this->echo_esc_translate( 'メンテナンスモードを適用するか、適用を解除します。' ); ?>">
							<?php $this->my_generate_tips_tag( 'メンテナンスモードを適用するか、適用を解除します。' ); ?>
							</div>
						</td>
					</tr>
					<?php printf( '<tr class="%s">', esc_attr( self::CLASS_MODE_SETTING_VISIBLED ) ); ?>
					  <th scope="row"><?php generate_label_tag( '', $this->get_esc_translate( 'ダークモード設定' ) ); ?></th>
						<td>
						<p class="setting_read"><?php $this->echo_esc_translate( 'ダークモード用として指定したカラー情報をCSSで出力することができます。' ); ?>
					  <?php echo get_help_page_tag( 'https://lacaraterra.site/cocoon-scenery-color-plugin/#darkmode' ); ?></p>
							<div>
							<input type="checkbox" <?php echo $darkmode_mode ? 'checked' : ''; ?> <?php echo '' === $current_darkmode_name ? 'disabled="disabled"' : ''; ?> id="<?php echo esc_attr( self::CHECKBOX_DARKMODE_SETTING ); ?>" name="<?php echo esc_attr( self::CHECKBOX_DARKMODE_SETTING ); ?>" <?php echo $form_attr_darkmode_setteing; ?> title="<?php $this->echo_esc_translate( '無効とする場合はチェックを外してください。' ); ?>">
							<?php generate_label_tag( esc_attr( self::CHECKBOX_DARKMODE_SETTING ), $this->get_esc_translate( '有効とする　※ダークモードで使用するカラー名：' ) ); ?>
							<?php 
							if ( '' !== $current_darkmode_name ) {
								echo esc_attr( $current_darkmode_name ); 
							} else {
								$this->echo_esc_translate( '指定されていません。［ダークモードでの使用/解除］ボタン操作で指定してください。' );
							}
							?>
							<?php $this->my_generate_tips_tag( 'ダークモード用CSS出力機能を有効とするか、無効とします。' ); ?>
							</div>
							<div>
							<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_MODE_SETTING_VISIBLED ); ?>" <?php echo $form_attr_darkmode_setteing; ?>>
							<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_DARKMODE_SETTING ); ?>" value="<?php echo wp_create_nonce( self::VERIFY_NONCE_STRING ); ?>" <?php echo $form_attr_darkmode_setteing; ?>>
							<input type="submit" class="button" <?php echo '' === $current_darkmode_name ? 'disabled="disabled"' : ''; ?> id="<?php echo esc_attr( self::BUTTON_DARKMODE_SETTING ); ?>" value="<?php $this->echo_esc_translate( 'ダークモードの有効/無効' ); ?>" <?php echo $form_attr_darkmode_setteing; ?> title="<?php $this->echo_esc_translate( 'ダークモード用CSS出力機能を有効とするか、無効とします。' ); ?>">
							<?php $this->my_generate_tips_tag( 'ダークモード用CSS出力機能を有効とするか、無効とします。' ); ?>
							</div>
						</td>
					</tr>
					<?php printf( '<tr class="%s">', esc_attr( self::CLASS_MODE_SETTING_VISIBLED ) ); ?>
					  <th scope="row"><?php generate_label_tag( '', $this->get_esc_translate( 'スラッグモード設定' ) ); ?></th>
						<td>
						<p class="setting_read"><?php $this->echo_esc_translate( 'スラッグモード用として指定したカラー情報をCSSで出力することができます。' ); ?>
					  <?php echo get_help_page_tag( 'https://lacaraterra.site/cocoon-scenery-color-plugin/#slugmode' ); ?></p>
							<div>
							<input type="checkbox" <?php echo $slugmode_mode ? 'checked' : ''; ?> <?php echo ! $exist_slug_files ? 'disabled="disabled"' : ''; ?> id="<?php echo esc_attr( self::CHECKBOX_SLUGMODE_SETTING ); ?>" name="<?php echo esc_attr( self::CHECKBOX_SLUGMODE_SETTING ); ?>" <?php echo $form_attr_slugmode_setteing; ?> title="<?php $this->echo_esc_translate( '無効とする場合はチェックを外してください。' ); ?>">
							<?php generate_label_tag( esc_attr( self::CHECKBOX_SLUGMODE_SETTING ), $this->get_esc_translate( '有効とする' ) ); ?>
							<?php $this->my_generate_tips_tag( 'スラッグモード用CSS出力機能を有効とするか、無効とします。' ); ?>
							</div>
							<div>
							<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_MODE_SETTING_VISIBLED ); ?>" <?php echo $form_attr_slugmode_setteing; ?>>
							<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_SLUGMODE_SETTING ); ?>" value="<?php echo wp_create_nonce( self::VERIFY_NONCE_STRING ); ?>" <?php echo $form_attr_slugmode_setteing; ?>>
							<input type="submit" class="button" <?php echo ! $exist_slug_files ? 'disabled="disabled"' : ''; ?> id="<?php echo esc_attr( self::BUTTON_SLUGMODE_SETTING ); ?>" value="<?php $this->echo_esc_translate( 'スラッグモードの有効/無効' ); ?>" <?php echo $form_attr_slugmode_setteing; ?> title="<?php $this->echo_esc_translate( 'スラッグモード用CSS出力機能を有効とするか、無効とします。' ); ?>">
							<?php $this->my_generate_tips_tag( 'スラッグモード用CSS出力機能を有効とするか、無効とします。' ); ?>
							</div>
						</td>
					</tr>
					<tr>
					  <th scope="row"><?php generate_label_tag( '', $this->get_esc_translate( 'カラー設定' ) ); ?></th>
						<td>
						<p class="setting_read"><?php $this->echo_esc_translate( '全ての投稿、固定ページに設定したカラーが適用されます。' ); ?></p>
						<div id="choiceColorTheme" class="color_custum_select">
						<select id='select_color_theme' name="<?php echo esc_attr( self::COLOR_THEME_SELECT_COLOR ); ?>" class='color_theme_select_pre' <?php echo $form_attr_change; ?> title="<?php $this->echo_esc_translate( 'カラーを選択します。' ); ?>">
						<?php
						foreach ( $data_array as $color_data ) {
							?>
							<option value='<?php echo esc_attr( $color_data[ self::COLOR_FILE_NAME ] ); ?>'> <?php echo esc_html( $color_data[ self::INPUT_COLOR_NAME ] ) . ( array_key_exists( self::INPUT_NEW_COLOR_DESC, $color_data[ self::COLOR_JSON_DATA ] ) ? '：' . esc_html( $color_data[ self::COLOR_JSON_DATA ][ self::INPUT_NEW_COLOR_DESC ] ) : '' ); ?></option>
							<?php
						}
						?>
						</select>
						</td>
					</tr>

					<tr>
						<th scope="row"></th>
						<td>
						<table class="color-preview-table">
							<tr>
							<td class="title"><b><?php $this->echo_esc_translate( 'プレビュー' ); ?></b><?php echo get_help_page_tag( 'https://lacaraterra.site/cocoon-scenery-color-plugin/#where' ); ?></td>
							<td class="colorbar">
								<button id="<?php echo esc_attr( self::BUTTON_COPYALLCOLOR ); ?>" class="colorbar <?php echo esc_attr( self::CLASS_COPYALLCOLOR ); ?>" type="button" title="<?php $this->echo_esc_translate( '個別にコピーする場合は各項目をダブルクリックします。' ); ?>"><?php $this->echo_esc_translate( '一括コピー →' ); ?></button>
							</td>
							<td><b><?php $this->echo_esc_translate( 'カラーの作成' ); ?></b></td>
							<td class="<?php echo esc_attr( self::CLASS_NO_VALUE ); ?>"><label><input type="checkbox" name="allChecked" id="<?php echo esc_attr( self::CHECKBOX_ID_CHECKBOX_ALL_CHECK ); ?>" title="<?php $this->echo_esc_translate( '全て選択または解除します。' ); ?>"><?php $this->echo_esc_translate( '全選択' ); ?></label></td>
							<td class="<?php echo esc_attr( self::CLASS_TD_COPY_SAMPLE_COLOR ); ?>"/>
							<td colspan="3"><b><?php $this->echo_esc_translate( '〔参考〕配色サンプル' ); ?></b></td>
							</tr>
							<?php
							$color_attr          = sprintf( 'type="color" class="%s" value="%s" %s',
																							esc_attr( self::CLASS_COLORPALETTE ), esc_attr( self::COLOR_DEFAULT ), $form_attr_create );
							$sample_color_attr   = sprintf( 'type="color" class="%s" value="%s"',
																							esc_attr( self::CLASS_SAMPLE_COLORPALETTE ), esc_attr( self::COLOR_WHITE ) );
							$second_column_title = sprintf( 'title="%s"', $this->get_esc_translate( 'ダブルクリックで右側へコピーします。' ) );
							$class_select_option = self::CLASS_SELECT_OPTION;
							$class_not_color     = self::CLASS_NOT_COLOR;
							$row_number          = 1;
							$sample_color_number = 1;
							$sample_degrees      = [ 0, 15, 36, 45, 60, 72, 90, 108, 120, 135, 144, 165, 180, 195, 216, 225, 240, 252, 270, 288, 300, 315, 324, 345 ];
							$sample_polygons     = [ '起点', '', '10', '8', '6', '5,10', '4,8', '10', '3,6', '8', '5,10', '', '4,6,8,10', '', '5,10', '8', '3,6', '10', '4,8', '5,10', '6', '8', '10', '' ];
							$sample_saturations  = [ '0-5%', '5-10%', '10-15%', '15-20%', '20-25%', '25-30%', '30-35%', '35-40%', '40-45%', '45-50%',
							                         '50-55%', '55-60%', '60-65%', '65-70%', '70-75%', '75-80%', '80-85%', '85-90%', '90-95%', '95-99%', '100%' ];
							$sample_values       = [ '100%', '99-95%', '95-90%', '90-85%', '85-80%', '80-75%', '75-70%', '70-65%', '65-60%', '60-55%', '55-50%',
							                         '50-45%', '45-40%', '40-35%', '35-30%', '30-25%', '25-20%', '20-15%', '15-10%', '10-5%', '5-0%' ];
							$input_hexarea_size  = 'maxlength=7 size=7';
							
							foreach ( $default_json_data as $array_data ) {
								$key           = $array_data['id'];
								$name          = $this->get_esc_translate( $array_data['name'] );
								$value         = $array_data['value'];
								$attr          = $array_data['attr'];
								$title         = $this->get_esc_translate( $array_data['title'] );
								$visible       = $array_data['visible'];
								$cocoon_option = $array_data['cocoon_option'];
								if ( ! $visible ) {
									printf( '<input type="hidden" name="%s" id="%s" value="%s">', esc_attr( $key ), esc_attr( $key ), esc_attr( $value ) );
									printf( '<input type="hidden" name="%s%s" id="%s%s" value="" %s>', esc_attr( $key ), esc_attr( self::KEY_ADD_STRING ), esc_attr( $key ), esc_attr( self::KEY_ADD_STRING ), $form_attr_create );
									continue;
								}

								// 行設定
								printf( '<tr%s>',
												$cocoon_option && $attr !== self::INPUT_TYPE_ATTR_COLOR ? ' class="' . esc_attr( self::CLASS_NOT_COLOR_VISIBLED ) . '"' : '' );

								// 1列目（タイトル）
								printf( '<td class="title" title="%s">%s%s</td>', esc_attr( $key ), ( $this->exist_value_in_skin_option( $cocoon_option, $key ) ? '<strong>' . $this->get_esc_translate( 'スキン制御:' ) . '</strong>' : '' ), ( $name === '' ? esc_html( $key ) : esc_html( $name ) ) );
								// 2列目（現在値：カラー、フォントファミリー、説明等）
								if ( self::INPUT_TYPE_ATTR_TEXT === $attr || self::INPUT_TYPE_ATTR_SELECT === $attr ) {
									printf( '<td class="colorbar">' );
									printf(
										'<input type="text" class="colorbar %s" readonly id="%s" name="%s" %s value=""></td>',
										( $attr === self::INPUT_TYPE_ATTR_SELECT ? esc_attr( self::CLASS_COPYSELECTOPTION ) : esc_attr( self::CLASS_COPYTEXT ) ),
										esc_attr( $key ),
										esc_attr( $key ),
										$second_column_title
									);
								} else {
									printf( '<td class="colorbar %s" id="%s" %s></td>', esc_attr( self::CLASS_COPYCOLOR ), esc_attr( $key ), $second_column_title );
								}
								// 3列目（カラーの作成）
								if ( self::INPUT_TYPE_ATTR_TEXT === $attr ) {
									printf(
										'<td><input type="text" class="colorbar %s" id="%s%s" name="%s%s" %s value="" title="%s" %s></td>',
										( $cocoon_option ? $class_not_color : ''),
										esc_attr( $key ),
										esc_attr( self::KEY_ADD_STRING ),
										esc_attr( $key ),
										esc_attr( self::KEY_ADD_STRING ),
										$form_attr_create,
										$title,
										( $key === self::KEY_COLOR_NAME ? 'required' : '' )
									);
								} elseif ( self::INPUT_TYPE_ATTR_SELECT === $attr ) {
									printf(
										'<td><select id="%s%s" name="%s%s" class="%s %s" title="%s" %s>%s</select></td>',
										esc_attr( $key ),
										esc_attr( self::KEY_ADD_STRING ),
										esc_attr( $key ),
										esc_attr( self::KEY_ADD_STRING ),
										$class_select_option,
										( $cocoon_option ? $class_not_color : ''),
										$title,
										$form_attr_create,
										$this->generate_option_tag( $key )
									);
								} else {
									printf(
										'<td><input id="%s%s" name="%s%s" title="%s" %s></td>',
										esc_attr( $key ),
										esc_attr( self::KEY_ADD_STRING ),
										esc_attr( $key ),
										esc_attr( self::KEY_ADD_STRING ),
										$this->get_esc_translate( self::MESSAGE_CLICK_PALETTE ) . $title,
										$color_attr
									);
								}
								// 4列目（カラー値の値無しチェックボックス）
								if ( self::INPUT_TYPE_ATTR_COLOR === $attr ) {
									printf(
										'<td class="%s"><label class="%s"><input class="%s" type="checkbox" name="%s" value="%s" title="%s" %s>%s</label></td>',
										esc_attr( self::CLASS_NO_VALUE ),
										esc_attr( self::CLASS_NO_VALUE ),
										esc_attr( self::CLASS_NO_VALUE ),
										esc_attr( self::CHECKBOX_NAME_NO_VALUE_CHECKBOXES_ARRAY ),
										esc_attr( $key ),
										$this->get_esc_translate( '値として　\'\'　を格納します。' ),
										$form_attr_create,
										$this->get_esc_translate( '値無し' )
									);
									printf(
										'<td class="%s1"><button id="%s%s" class="%s" type="button" title="%s">%s</button></td>',
										esc_attr( self::CLASS_TD_COPY_SAMPLE_COLOR ),
										esc_attr( $key ),
										esc_attr( self::KEY_ADD_COPY_HEX ),
										esc_attr( self::CLASS_COPY_SAMPLE_COLOR ),
										$this->get_esc_translate( '［配色］で選択しているカラーをコピーします。' ),
										$this->get_esc_translate( '←' )
									);
								}
								// 5列目（配色サンプル・全体、外側、内側）
								if ( $row_number === 1 ) {
									$sample_color_images_comment = '赤の箇所の背景色と文字色を変えて配色を試すことができます。';
									printf(
										'<td colspan="3">' . 
										'<label><input type="radio" name="%s" value="%s">%s</label>' .
										'<span class="tooltip">' .
										'<span class="fa fa-picture-o %s" aria-hidden="false"></span>    <span class="tip-content" style="width: 680px;">' .
										'<img src="%s" alt=""><p>%s</p></span>' .
										'</span>' .
										'<label><input type="radio" name="%s" value="%s">%s</label>' .
										'<span class="tooltip">' .
										'<span class="fa fa-picture-o %s" aria-hidden="false"></span>    <span class="tip-content" style="width: 680px;">' .
										'<img src="%s" alt=""><p>%s</p></span>' .
										'</span>' .
										'<label><input type="radio" name="%s" value="%s">%s</label>' .
										'<span class="tooltip">' .
										'<span class="fa fa-picture-o %s" aria-hidden="false"></span>    <span class="tip-content" style="width: 680px;">' .
										'<img src="%s" alt=""><p>%s</p></span>' .
										'</span>' .
										'<label><input type="radio" name="%s" value="%s" checked>%s</label>' .
										'</td>',
										esc_attr( self::RADIO_NAME_SAMPLE_APPLY ),
										esc_attr( self::RADIO_VALUE_SAMPLE_APPLY_ALL ),
										$this->get_esc_translate( '全体' ),
										esc_attr( self::CLASS_SAMPLE_COLOR ),
										plugins_url( self::FOLDER_NAME_IMAGES . '/' . self::FILE_SAMPLE_COLOR_ALL, __FILE__ ),
										$this->get_esc_translate( $sample_color_images_comment ),
										esc_attr( self::RADIO_NAME_SAMPLE_APPLY ),
										esc_attr( self::RADIO_VALUE_SAMPLE_APPLY_OUTSIDE ),
										$this->get_esc_translate( '外側' ),
										esc_attr( self::CLASS_SAMPLE_COLOR ),
										plugins_url( self::FOLDER_NAME_IMAGES . '/' . self::FILE_SAMPLE_COLOR_OUTSIDE, __FILE__ ),
										$this->get_esc_translate( $sample_color_images_comment ),
										esc_attr( self::RADIO_NAME_SAMPLE_APPLY ),
										esc_attr( self::RADIO_VALUE_SAMPLE_APPLY_INSIDE ),
										$this->get_esc_translate( '内側' ),
										esc_attr( self::CLASS_SAMPLE_COLOR ),
										plugins_url( self::FOLDER_NAME_IMAGES . '/' . self::FILE_SAMPLE_COLOR_INSIDE, __FILE__ ),
										$this->get_esc_translate( $sample_color_images_comment ),
										esc_attr( self::RADIO_NAME_SAMPLE_APPLY ),
										esc_attr( self::RADIO_VALUE_SAMPLE_APPLY_CLEAR ),
										$this->get_esc_translate( 'クリア' )
									);
								}
								// 5列目（配色サンプル・背景）
								if ( $row_number === 2 ) {
									printf(
										'<td><label><input type="radio" name="%s" value="%s" checked>%s</label></td>',
										esc_attr( self::RADIO_NAME_SAMPLE_COLORS ),
										esc_attr( self::RADIO_VALUE_SAMPLE_COLORS_BACKGROUND ),
										$this->get_esc_translate( '背景' )
									);
									printf(
										'<td><input id="%s%s" name="%s%s" title="%s" %s></td>',
										esc_attr( self::KEY_SAMPLE_COLORS ),
										esc_attr( self::KEY_SAMPLE_BACKGROUND ),
										esc_attr( self::KEY_SAMPLE_COLORS ),
										esc_attr( self::KEY_SAMPLE_BACKGROUND ),
										$this->get_esc_translate( self::MESSAGE_CLICK_PALETTE ),
										$sample_color_attr
									);
									printf(
										'<td><input id="%s%s" class="%s" %s value="%s" title="%s"/></td>',
										esc_attr( self::KEY_SAMPLE_HEX ),
										esc_attr( self::KEY_SAMPLE_BACKGROUND ),
										esc_attr( self::CLASS_SAMPLE_HEX ),
										$input_hexarea_size,
										esc_attr( self::COLOR_WHITE ),
										$this->get_esc_translate( self::MESSAGE_INPUT_RRGGBB )
									);
								}
								// 5列目（配色サンプル・フォント）
								if ( $row_number === 3 ) {
									printf(
										'<td><label><input type="radio" name="%s" value="%s">%s</label></td>',
										esc_attr( self::RADIO_NAME_SAMPLE_COLORS ),
										esc_attr( self::RADIO_VALUE_SAMPLE_COLORS_FONT ),
										$this->get_esc_translate( 'フォント' )
									);
									printf(
										'<td><input id="%s%s" name="%s%s" title="%s" %s></td>',
										esc_attr( self::KEY_SAMPLE_COLORS ),
										esc_attr( self::KEY_SAMPLE_FONT ),
										esc_attr( self::KEY_SAMPLE_COLORS ),
										esc_attr( self::KEY_SAMPLE_FONT ),
										$this->get_esc_translate( self::MESSAGE_CLICK_PALETTE ),
										$sample_color_attr
									);
									printf(
										'<td><input id="%s%s" class="%s" %s value="%s" title="%s"/></td>',
										esc_attr( self::KEY_SAMPLE_HEX ),
										esc_attr( self::KEY_SAMPLE_FONT ),
										esc_attr( self::CLASS_SAMPLE_HEX ),
										$input_hexarea_size,
										esc_attr( self::COLOR_WHITE ),
										$this->get_esc_translate( self::MESSAGE_INPUT_RRGGBB )
									);
								}
								// 5列目（配色サンプルヘッダー）
								if ( $row_number === 4 ) {
									printf( '<td colspan="3"><label class="%s">%s</label>',
											esc_attr( self::CLASS_LABEL_MARGIN_RIGHT ),
											$this->get_esc_translate( '［配色］' )
									);
									printf( 
										'<input type="checkbox" id="%s" name="%s" title="%s">',
										esc_attr( self::CHECKBOX_SAMPLE_LOCK ),
										esc_attr( self::CHECKBOX_SAMPLE_LOCK ),
										$this->get_esc_translate( '配色を自動で算出しない場合はチェックしてください。' )
									);
									generate_label_tag( esc_attr( self::CHECKBOX_SAMPLE_LOCK ), $this->get_esc_translate( 'LOCK' ) );
								?>
								</td>
								<?php 
								}
								// 5列目（色相環、彩度、明度 選択チェックボックス）
								if ( $row_number === 5 ) {
									printf(
										'<td colspan="3">' . 
										'<label class="%s"><input type="radio" class="%s" name="%s" value="%s" checked>%s</label>' .
										'<label class="%s"><input type="radio" class="%s" name="%s" value="%s">%s</label>' .
										'<label class="%s"><input type="radio" class="%s" name="%s" value="%s">%s</label>' .
										'<input type="checkbox" id="%s" name="%s" title="%s">' .
										'%s' .
										'</td>',
										esc_attr( self::CLASS_LABEL_MARGIN_RIGHT ),
										esc_attr( self::RADIO_NAME_THREE_ELEMENTS ),
										esc_attr( self::RADIO_NAME_THREE_ELEMENTS ),
										esc_attr( self::RADIO_VALUE_THREE_ELEMENTS_HUE ),
										$this->get_esc_translate( '色相環' ),
										esc_attr( self::CLASS_LABEL_MARGIN_RIGHT ),
										esc_attr( self::RADIO_NAME_THREE_ELEMENTS ),
										esc_attr( self::RADIO_NAME_THREE_ELEMENTS ),
										esc_attr( self::RADIO_VALUE_THREE_ELEMENTS_SAT ),
										$this->get_esc_translate( '彩度' ),
										esc_attr( self::CLASS_LABEL_MARGIN_RIGHT ),
										esc_attr( self::RADIO_NAME_THREE_ELEMENTS ),
										esc_attr( self::RADIO_NAME_THREE_ELEMENTS ),
										esc_attr( self::RADIO_VALUE_THREE_ELEMENTS_VAL ),
										$this->get_esc_translate( '明度' ),
										esc_attr( self::CHECKBOX_THREE_ELEMENTS ),
										esc_attr( self::CHECKBOX_THREE_ELEMENTS ),
										$this->get_esc_translate( '全て表示する場合はチェックしてください。' ),
										get_label_tag( esc_attr( self::CHECKBOX_THREE_ELEMENTS ), $this->get_esc_translate( '全て表示' ) )
									);
								}
								// 6列目（色相環、彩度、明度ヘッダー）
								if ( $row_number === 6 ) {
									printf( '<td class="%s"></td><td class="%s"></td><td class="%s"><label>%s</label></td>',
											esc_attr( self::CLASS_COLOR_VISIBLED_HUE ),
											esc_attr( self::CLASS_COLOR_VISIBLED_HUE ),
											esc_attr( self::CLASS_COLOR_VISIBLED_HUE ),
											$this->get_esc_translate( '色相環位置/n角形' )
									);
									printf( '<td class="%s"></td><td class="%s"></td><td class="%s"><label>%s</label></td>',
											esc_attr( self::CLASS_COLOR_VISIBLED_SAT ),
											esc_attr( self::CLASS_COLOR_VISIBLED_SAT ),
											esc_attr( self::CLASS_COLOR_VISIBLED_SAT ),
											$this->get_esc_translate( '彩度比率' ),
									);
									printf( '<td class="%s"></td><td class="%s"></td><td class="%s"><label>%s</label></td>',
											esc_attr( self::CLASS_COLOR_VISIBLED_VAL ),
											esc_attr( self::CLASS_COLOR_VISIBLED_VAL ),
											esc_attr( self::CLASS_COLOR_VISIBLED_VAL ),
											$this->get_esc_translate( '明度比率' )
									);
								}
								// 7列目（配色サンプル）
								$start_position_colors16 = 7;
								// 色相環
								if ( $start_position_colors16 <= $row_number && $row_number <= ($start_position_colors16 + count($sample_degrees) - 1) ) {
									printf(
										'<td class="%s %s"><input type="radio" class="%s" name="%s" value="%s%s%s" %s>',
										esc_attr( self::CLASS_SAMPLE_COLORS16 ),
										esc_attr( self::CLASS_COLOR_VISIBLED_HUE ),
										esc_attr( self::CLASS_SAMPLE_COLORS16 ),
										esc_attr( self::RADIO_NAME_SAMPLE_COLORS16_HUE ),
										esc_attr( self::RADIO_VALUE_SAMPLE_COLORS16 ),
										esc_attr( self::TYPE_HUE ),
										esc_attr( $sample_color_number ),
										$row_number === $start_position_colors16 ? 'checked' : ''
									);
									printf(
										'<input id="%s%s%s" name="%s%s%s" title="%s" %s></td>',
										esc_attr( self::KEY_SAMPLE_COLORS ),
										esc_attr( self::TYPE_HUE ),
										esc_attr( $sample_color_number ),
										esc_attr( self::KEY_SAMPLE_COLORS ),
										esc_attr( self::TYPE_HUE ),
										esc_attr( $sample_color_number ),
										$this->get_esc_translate( self::MESSAGE_CLICK_PALETTE ),
										$sample_color_attr
									);
									printf(
										'<td class="%s"><input id="%s%s%s" class="%s" %s value="%s" title="%s"/></td>',
										esc_attr( self::CLASS_COLOR_VISIBLED_HUE ),
										esc_attr( self::KEY_SAMPLE_HEX ),
										esc_attr( self::TYPE_HUE ),
										esc_attr( $sample_color_number ),
										esc_attr( self::CLASS_SAMPLE_HEX ),
										$input_hexarea_size,
										esc_attr( self::COLOR_WHITE ),
										$this->get_esc_translate( self::MESSAGE_INPUT_RRGGBB )
									);
									printf(
										'<td class="%s"><p class="%s">%s%s%s%s</p></td>',
										esc_attr( self::CLASS_COLOR_VISIBLED_HUE ),
										esc_attr( self::CLASS_SAMPLE_COLOR_GUIDE ),
										esc_attr( $sample_degrees[ $sample_color_number - 1 ] ),
										$this->get_esc_translate( '度' ),
										esc_attr( $sample_polygons[ $sample_color_number - 1 ] === '' ? '' : '/' ),
										$this->get_esc_translate( $sample_polygons[ $sample_color_number - 1 ] )
									 );
									// 彩度
									if ( $start_position_colors16 <= $row_number && $row_number <= ($start_position_colors16 + count($sample_saturations) - 1) ) {
										printf(
											'<td class="%s %s"><input type="radio" class="%s" name="%s" value="%s%s%s" %s>',
											esc_attr( self::CLASS_SAMPLE_COLORS16 ),
											esc_attr( self::CLASS_COLOR_VISIBLED_SAT ),
											esc_attr( self::CLASS_SAMPLE_COLORS16 ),
											esc_attr( self::RADIO_NAME_SAMPLE_COLORS16_SAT ),
											esc_attr( self::RADIO_VALUE_SAMPLE_COLORS16 ),
											esc_attr( self::TYPE_SAT ),
											esc_attr( $sample_color_number ),
											$row_number === $start_position_colors16 ? 'checked' : ''
										);
										printf(
											'<input id="%s%s%s" name="%s%s%s" title="%s" %s></td>',
											esc_attr( self::KEY_SAMPLE_COLORS ),
											esc_attr( self::TYPE_SAT ),
											esc_attr( $sample_color_number ),
											esc_attr( self::KEY_SAMPLE_COLORS ),
											esc_attr( self::TYPE_SAT ),
											esc_attr( $sample_color_number ),
											$this->get_esc_translate( self::MESSAGE_CLICK_PALETTE ),
											$sample_color_attr
										);
										printf(
											'<td class="%s"><input id="%s%s%s" class="%s" %s value="%s" title="%s"/></td>',
											esc_attr( self::CLASS_COLOR_VISIBLED_SAT ),
											esc_attr( self::KEY_SAMPLE_HEX ),
											esc_attr( self::TYPE_SAT ),
											esc_attr( $sample_color_number ),
											esc_attr( self::CLASS_SAMPLE_HEX ),
											$input_hexarea_size,
											esc_attr( self::COLOR_WHITE ),
											$this->get_esc_translate( self::MESSAGE_INPUT_RRGGBB )
										);
										printf(
											'<td class="%s"><p class="%s">%s</p></td>',
											esc_attr( self::CLASS_COLOR_VISIBLED_SAT ),
											esc_attr( self::CLASS_SAMPLE_COLOR_GUIDE ),
											esc_attr( $sample_saturations[ $sample_color_number - 1 ] )
										 );
									}
									// 明度
									if ( $start_position_colors16 <= $row_number && $row_number <= ($start_position_colors16 + count($sample_values) - 1) ) {
										printf(
											'<td class="%s %s"><input type="radio" class="%s" name="%s" value="%s%s%s" %s>',
											esc_attr( self::CLASS_SAMPLE_COLORS16 ),
											esc_attr( self::CLASS_COLOR_VISIBLED_VAL ),
											esc_attr( self::CLASS_SAMPLE_COLORS16 ),
											esc_attr( self::RADIO_NAME_SAMPLE_COLORS16_VAL ),
											esc_attr( self::RADIO_VALUE_SAMPLE_COLORS16 ),
											esc_attr( self::TYPE_VAL ),
											esc_attr( $sample_color_number ),
											$row_number === $start_position_colors16 ? 'checked' : ''
										);
										printf(
											'<input id="%s%s%s" name="%s%s%s" title="%s" %s></td>',
											esc_attr( self::KEY_SAMPLE_COLORS ),
											esc_attr( self::TYPE_VAL ),
											esc_attr( $sample_color_number ),
											esc_attr( self::KEY_SAMPLE_COLORS ),
											esc_attr( self::TYPE_VAL ),
											esc_attr( $sample_color_number ),
											$this->get_esc_translate( self::MESSAGE_CLICK_PALETTE ),
											$sample_color_attr
										);
										printf(
											'<td class="%s"><input id="%s%s%s" class="%s" %s value="%s" title="%s"/></td>',
											esc_attr( self::CLASS_COLOR_VISIBLED_VAL ),
											esc_attr( self::KEY_SAMPLE_HEX ),
											esc_attr( self::TYPE_VAL ),
											esc_attr( $sample_color_number ),
											esc_attr( self::CLASS_SAMPLE_HEX ),
											$input_hexarea_size,
											esc_attr( self::COLOR_WHITE ),
											$this->get_esc_translate( self::MESSAGE_INPUT_RRGGBB )
										);
										printf(
											'<td class="%s"><p class="%s">%s</p></td>',
											esc_attr( self::CLASS_COLOR_VISIBLED_VAL ),
											esc_attr( self::CLASS_SAMPLE_COLOR_GUIDE ),
											esc_attr( $sample_values[ $sample_color_number - 1 ] )
										 );
									}
									$sample_color_number += 1;
								}
								echo "</tr>\n";
								$row_number += 1;
							}
							?>
						  <tr>
							<td></td>
							<td class="colorbar">
								<span class="tooltip">
								<span class="fa fa-picture-o" aria-hidden="false"></span>    <span id="<?php echo esc_attr( self::ELEMENT_ID_COLOR_PREVIEW_SPAN ); ?>" class="tip-content" style="width: 680px;">
								<img id="<?php echo esc_attr( self::ELEMENT_ID_COLOR_PREVIEW ); ?>" src="" alt="<?php $this->echo_esc_translate( 'カラーを選択してください' ); ?>"></span>
								</span>
								<div>
								<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_SITE_COLOR_CHANGE ); ?>" value="<?php echo wp_create_nonce( self::VERIFY_NONCE_STRING ); ?>" <?php echo $form_attr_change; ?>>
								<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_MODE_SETTING_VISIBLED ); ?>" <?php echo $form_attr_change; ?>>
								<input type="submit" class="button" id="<?php echo esc_attr( self::BUTTON_SITE_COLOR_CHANGE ); ?>" value="<?php $this->echo_esc_translate( 'サイトカラーの変更' ); ?>" <?php echo $form_attr_change; ?> title="<?php $this->echo_esc_translate( 'サイトのカラーを変更します。' ); ?>">
								<?php $this->my_generate_tips_tag( '全ての投稿、固定ページに設定したカラーが適用されます。', get_help_page_tag( 'https://lacaraterra.site/cocoon-scenery-color-plugin/#change' ) ); ?>
								</div>
							</td>
							<td class="colorbar" rowspan="2">
								<input type="checkbox" id="<?php echo esc_attr( self::CHECKBOX_SITE_COLOR_CHANGE ); ?>" name="<?php echo esc_attr( self::CHECKBOX_SITE_COLOR_CHANGE ); ?>" <?php echo $form_attr_create; ?> title="<?php $this->echo_esc_translate( 'カラー作成後にサイトカラーの変更も行います。' ); ?>">
								<?php generate_label_tag( esc_attr( self::CHECKBOX_SITE_COLOR_CHANGE ), $this->get_esc_translate( 'サイトカラーの変更も行う' ) ); ?>
								<?php $this->my_generate_tips_tag( 'カラー作成後にサイトカラーの変更も行います。' ); ?>
								<input type="checkbox" id="<?php echo esc_attr( self::CHECKBOX_COLOR_ONLY_SAVE ); ?>" name="<?php echo esc_attr( self::CHECKBOX_COLOR_ONLY_SAVE ); ?>" <?php echo $form_attr_create; ?> title="<?php $this->echo_esc_translate( '色情報のみ保存します。' ); ?>">
								<?php generate_label_tag( esc_attr( self::CHECKBOX_COLOR_ONLY_SAVE ), $this->get_esc_translate( '色情報のみ保存する' ) ); ?>
								<?php $this->my_generate_tips_tag( 'サイトキーカラー～サイドバー枠線色までの色情報のみをファイルに保存します。' ); ?>
								<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_COLOR_CREATE ); ?>" value="<?php echo wp_create_nonce( self::VERIFY_NONCE_STRING ); ?>" <?php echo $form_attr_create; ?> >
								<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_MODE_SETTING_VISIBLED ); ?>" <?php echo $form_attr_create; ?>>
								<input type="submit" class="button" id="<?php echo esc_attr( self::BUTTON_COLOR_CREATE ); ?>" value="<?php $this->echo_esc_translate( 'カラーの作成' ); ?>" title="<?php $this->echo_esc_translate( '新しいカラーファイルを作成するか、既存のファイルを変更します。' ); ?>" <?php echo $form_attr_create; ?>>
								<?php $this->my_generate_tips_tag( '新しいカラーファイルを作成するか、既存のファイルを変更します。', get_help_page_tag( 'https://lacaraterra.site/cocoon-scenery-color-plugin/#create' ) ); ?>
							</td>
							</tr>
							<tr>
							<td></td>
							<td class="colorbar">
								<input type="checkbox" id="<?php echo esc_attr( self::CHECKBOX_SLUGMODE_USE ); ?>" name="<?php echo esc_attr( self::CHECKBOX_SLUGMODE_USE ); ?>" <?php echo $form_attr_slugmode; ?> title="<?php $this->echo_esc_translate( 'カラーをスラッグモードで使用するか、使用を解除します。' ); ?>">
								<?php generate_label_tag( esc_attr( self::CHECKBOX_SLUGMODE_USE ), $this->get_esc_translate( '使用する' ) ); ?>
								<div>
								<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_MODE_SETTING_VISIBLED ); ?>" <?php echo $form_attr_slugmode; ?>>
								<input type="hidden" name="<?php echo esc_attr( self::CURRENT_COLOR_NAME ); ?>" value="<?php echo esc_attr( $current_color_name ); ?>" <?php echo $form_attr_slugmode; ?>>
								<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_SLUGMODE ); ?>" value="<?php echo wp_create_nonce( self::VERIFY_NONCE_STRING ); ?>" <?php echo $form_attr_slugmode; ?>>
								<input type="submit" class="button" id="<?php echo esc_attr( self::BUTTON_COLOR_SLUGMODE ); ?>" value="<?php $this->echo_esc_translate( 'スラッグモードでの使用/解除' ); ?>" <?php echo $form_attr_slugmode; ?> title="<?php $this->echo_esc_translate( 'カラーをスラッグモードで使用するか、使用を解除します。' ); ?>">
								<?php $this->my_generate_tips_tag( 'カラーをスラッグモードで使用するか、使用を解除します。' ); ?>
								</div>
							</td>
							</tr>
							<tr>
							<td></td>
							<td class="colorbar">
								<input type="checkbox" id="<?php echo esc_attr( self::CHECKBOX_DARKMODE_USE ); ?>" name="<?php echo esc_attr( self::CHECKBOX_DARKMODE_USE ); ?>" <?php echo $form_attr_darkmode; ?> title="<?php $this->echo_esc_translate( 'カラーをダークモードで使用するか、使用を解除します。' ); ?>">
								<?php generate_label_tag( esc_attr( self::CHECKBOX_DARKMODE_USE ), $this->get_esc_translate( '使用する' ) ); ?>
								<div>
								<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_MODE_SETTING_VISIBLED ); ?>" <?php echo $form_attr_darkmode; ?>>
								<input type="hidden" name="<?php echo esc_attr( self::CURRENT_COLOR_NAME ); ?>" value="<?php echo esc_attr( $current_color_name ); ?>" <?php echo $form_attr_darkmode; ?>>
								<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_DARKMODE ); ?>" value="<?php echo wp_create_nonce( self::VERIFY_NONCE_STRING ); ?>" <?php echo $form_attr_darkmode; ?>>
								<input type="submit" class="button" id="<?php echo esc_attr( self::BUTTON_COLOR_DARKMODE ); ?>" value="<?php $this->echo_esc_translate( 'ダークモードでの使用/解除' ); ?>" <?php echo $form_attr_darkmode; ?> title="<?php $this->echo_esc_translate( 'カラーをダークモードで使用するか、使用を解除します。' ); ?>">
								<?php $this->my_generate_tips_tag( 'カラーをダークモードで使用するか、使用を解除します。' ); ?>
								</div>
							</td>
							<td></td>
							</tr>
							<tr>
							<td></td>
							<td class="colorbar">
								<input type="checkbox" id="<?php echo esc_attr( self::CHECKBOX_MAINTENANCE_USE ); ?>" name="<?php echo esc_attr( self::CHECKBOX_MAINTENANCE_USE ); ?>" <?php echo $form_attr_maintenance; ?> title="<?php $this->echo_esc_translate( 'カラーをメンテナンスモードで使用するか、使用を解除します。' ); ?>">
								<?php generate_label_tag( esc_attr( self::CHECKBOX_MAINTENANCE_USE ), $this->get_esc_translate( '使用する' ) ); ?>
								<div>
								<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_MODE_SETTING_VISIBLED ); ?>" <?php echo $form_attr_maintenance; ?>>
								<input type="hidden" name="<?php echo esc_attr( self::CURRENT_COLOR_NAME ); ?>" value="<?php echo esc_attr( $current_color_name ); ?>" <?php echo $form_attr_maintenance; ?>>
								<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_MAINTENANCE ); ?>" value="<?php echo wp_create_nonce( self::VERIFY_NONCE_STRING ); ?>" <?php echo $form_attr_maintenance; ?>>
								<input type="submit" class="button" id="<?php echo esc_attr( self::BUTTON_COLOR_MAINTENANCE ); ?>" value="<?php $this->echo_esc_translate( 'メンテナンスモードでの使用/解除' ); ?>" <?php echo $form_attr_maintenance; ?> title="<?php $this->echo_esc_translate( 'カラーをメンテナンスモードで使用するか、使用を解除します。' ); ?>">
								<?php $this->my_generate_tips_tag( 'カラーをメンテナンスモードで使用するか、使用を解除します。' ); ?>
								</div>
							</td>
							<td></td>
							</tr>
							<tr>
							<td></td>
							<td class="colorbar">
								<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_MODE_SETTING_VISIBLED ); ?>" <?php echo $form_attr_delete; ?>>
								<input type="hidden" name="<?php echo esc_attr( self::CURRENT_COLOR_NAME ); ?>" value="<?php echo esc_attr( $current_color_name ); ?>" <?php echo $form_attr_delete; ?>>
								<input type="hidden" name="<?php echo esc_attr( self::HIDDEN_COLOR_DELETE ); ?>" value="<?php echo wp_create_nonce( self::VERIFY_NONCE_STRING ); ?>" <?php echo $form_attr_delete; ?>>
								<input type="submit" class="button" id="<?php echo esc_attr( self::BUTTON_COLOR_DELETE ); ?>" value="<?php $this->echo_esc_translate( 'カラーの削除' ); ?>" <?php echo $form_attr_delete; ?> title="<?php $this->echo_esc_translate( 'カラー（カラーファイル）を削除します。' ); ?>">
								<?php $this->my_generate_tips_tag( 'カラーを削除します。' ); ?>
							</td>
							<td></td>
							</tr>
						</table>
						</td>
					</tr>
						<?php
					}   // !empty($files)
					?>
					</tbody>
				</table>
				</div>
			</div>
			<!-- バックアップ・リストア -->
			<div id="backup" class="postbox">
			  <h2 class="hndle"><?php $this->echo_esc_translate( 'バックアップ・リストア' ) ?></h2>
			  <div class="inside">
					<p><?php $this->echo_esc_translate( '作成したカラー情報（カラーファイル）のバックアップとリストアを行います。' ) ?><br />
						 <?php $this->echo_esc_translate( '※本プラグインのバージョンアップを行うと作成したカラー情報が失われますので、プラグインのバージョンアップを行う前にはバックアップを取得してください。' ); ?><br />
						 <?php $this->echo_esc_translate( '※本プラグインのバージョンアップを行ったあとは、カラー情報を復元するためにバックアップしたファイルをリストアしてください。' ); ?>
						 <?php echo get_help_page_tag( 'https://lacaraterra.site/cocoon-scenery-color-plugin/#backup' ); ?><br />
					</p>
			    <table class="<?php echo esc_attr( self::CLASS_CSCP_FORM_TABLE_BACKUP ); ?>">
			      <tbody>
			        <!-- バックアップ  -->
			        <tr>
			          <th scope="row"><?php generate_label_tag('', $this->get_esc_translate( 'バックアップ' ) ); ?></th>
			          <td>
			            <a href="<?php echo plugins_url( 'modules/backup-download.php', __FILE__ ); ?>" class="button" title="<?php $this->echo_esc_translate( '作成したカラー情報をバックアップします。' ); ?>"><?php $this->echo_esc_translate( 'バックアップファイルの取得' ) ?></a>
			            <?php
			              $this->my_generate_tips_tag( 'カラー情報（カラーファイル）をバックアップする場合はボタンをクリックしてください。' );
			            ?>
			          </td>
			        </tr>
			        <!-- リストア  -->
			        <tr>
			          <th scope="row"><?php generate_label_tag('', $this->get_esc_translate( 'リストア' ) ); ?></th>
			          <td>
			            <form enctype="multipart/form-data" action="" method="POST">
			                <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo esc_attr( $upload_max_file_size ); ?>" />
			                <?php $this->echo_esc_translate( 'バックアップしたファイルをアップロード：' ) ?>
			                <input name="<?php echo esc_attr( self::INPUT_TYPE_FILE_NAME ); ?>" type="file" /><br>
			                <input type="hidden" name="<?php echo esc_attr( self::HIDDEN_RESTORE ); ?>" value="<?php echo wp_create_nonce( self::VERIFY_NONCE_STRING );?>">
			                <input type="submit" class="button" value="<?php $this->echo_esc_translate( 'カラー情報（カラーファイル）の復元' ) ?>" title="<?php $this->echo_esc_translate( 'バックアップしたファイルを元にファイルを復元します。' ); ?>">
			                <?php
			                $this->my_generate_tips_tag( '［ファイルを選択］ボタンをクリックしてバックアップしたファイルを選択し、［カラー情報（カラーファイル）の復元］ボタンをクリックしてください。' );
			                 ?>
			            </form>
			          </td>
			        </tr>
			      </tbody>
			    </table>
			  </div>
			</div>
			</div><!-- /.metabox-holder -->
		</div><!-- /.wrap admin-settings -->
				<?php
			}
			?>
			<?php
		}

		/**
		 * オプション保存メッセージの出力
		 */
		private function print_options_saved_message() : void {
			$this->print_post_processed_message( $this->get_esc_translate( '設定は保存されました。' ) );
		}

		/**
		 * カラー作成メッセージの出力
		 */
		private function print_color_created_message( $exist_file ) : void {
			$this->print_post_processed_message( 
				$exist_file ? 
				$this->get_esc_translate( 'カラー（カラーファイル）を更新しました。' ) :
				$this->get_esc_translate( 'カラー（カラーファイル）を作成しました。' ) );
		}

		/**
		 * wp-cron実行エラーメッセージの出力（wp-cronプロセス稼働中）
		 * @param  string $another_running_process 稼働中の他wp-cronプロセス（フック）名
		 */
		private function print_cron_running_message( $another_running_process = '' ) : void {
			$message = $this->get_esc_translate( '設定は保存できませんでした。wp-cronプロセスが稼働中です。もう一度行ってください。' );
			if ( '' !== $another_running_process ) {
				$message .= '(' . $another_running_process . ')';
			}
			$this->print_post_processed_message( $message );
		}

		/**
		 * カラー削除メッセージの出力
		 */
		private function print_color_deleted_message() : void {
			$this->print_post_processed_message( $this->get_esc_translate( 'カラー（カラーファイル）を削除しました。' ) );
		}

		/**
		 * バックアップ処理でのエラーメッセージの出力
		 * @param  string $error_message エラーメッセージ
		 */
		private function print_backup_error_message( $error_message ) : void {
			$this->print_post_processed_message( $error_message );
		}

		/**
		 * リストア処理メッセージの出力
		 */
		private function print_restore_message() : void {
			$this->print_post_processed_message( $this->get_esc_translate( 'カラー（カラーファイル）を復元しました。' ) );
		}

		/**
		 * リストア処理でのエラーメッセージの出力
		 */
		private function print_restore_error_message() : void {
			$this->print_post_processed_message( $this->error_message );
		}

		/**
		 * POST処理メッセージの出力
		 * @param  string $message 出力するメッセージ
		 */
		private function print_post_processed_message( $message ) : void {
			printf( '<div class="updated"><p><strong>%s</strong></p></div>', $message );
		}

		/**
		 * JSON形式ファイルの取得
		 * @param  string $file_path JSON形式ファイルのパス
		 * @return array             取得データ
		 */
		private function read_json_file( $file_path ) : array {
			$json_data   = array();
			if ( file_exists( $file_path ) ) {
				$json_string = file_get_contents( $file_path );
				$json_data   = json_decode( $json_string, true );
			}
			return $json_data;
		}

		/**
		 * カレントカラー情報ファイル（JSON形式）の格納
		 * @param  array  $json_data 格納データ
		 */
		private function write_scenery_color_info_file( $json_data ) : void {
			$this->write_json_file( $this->scenery_color_info_json_path, $json_data );
		}

		/**
		 * JSON形式ファイルの格納
		 * @param  string $file_path JSON形式ファイルのパス
		 * @param  array  $json_data 格納データ
		 */
		private function write_json_file( $file_path, $json_data ) : void {
			// 格納データをJSON形式へ変換
			$encode_data = wp_json_encode( $json_data, self::JSON_ENCODE_TO_FILE );
			// JSON形式データのファイルへの書き込み
			file_put_contents( $file_path, $encode_data );
		}

		/**
		 * 配列からのキー項目を指定した値の取得
		 * キー項目が無い場合は空文字列を返します。
		 * @param  string キー名
		 * @param  array  連想配列
		 * @param  mixed  キー名が無い場合のデフォルト値
		 * @return mixed  キーの値
		 */
		private function get_array_key_exists( $key_name, $array_data, $value = '' ) {
			if ( array_key_exists( $key_name, $array_data ) ) {
				$value = $array_data[ $key_name ];
			}
			return $value;
		}

		/**
		 * HTTP POSTデータのサニタイズ
		 * @return mixed サニタイズされたPOSTデータ
		 */
		private function sanitize_http_post_field( $name ) {
			return array_key_exists( $name, $_POST ) ? $this->sanitize_field( $_POST[ $name ] ) : '';
		}

		/**
		 * HTTP POSTデータのサニタイズ
		 * @return bool サニタイズされたPOSTデータのbool値
		 */
		private function sanitize_http_post_field_boolean( $name ) : bool {
			return filter_var( $this->sanitize_http_post_field( $name ), FILTER_VALIDATE_BOOLEAN );
		}

		/**
		 * scriptタグでのJSONデータの出力
		 */
		private function print_script_json_data( $json_data, $script_id ) : void {
			// WordPress 5.7 introduced new function for script tags.
			if ( function_exists( 'wp_get_inline_script_tag' ) ) {
				echo wp_get_inline_script_tag(
					esc_html( wp_json_encode( $json_data, self::JSON_ENCODE_TO_HTML ) ),
					array(
						'type' => 'application/json',
						'id'   => $script_id,
					)
				);
			} else {
				printf( '<script type="application/json" id="%s">%s</script>', esc_attr( $script_id ), esc_html( wp_json_encode( $json_data, self::JSON_ENCODE_TO_HTML ) ) );
			}
		}

		/**
		 * 文字列または配列のサニタイズ
		 * @param  mixed $array_or_string サニタイズ対象のデータ
		 * @return mixed                  サニタイズされたデータ
		 */
		private function sanitize_field( $array_or_string ) {
			if ( is_array( $array_or_string ) ) {
				$result = array();
				foreach ( $array_or_string as $key => $val ) {
					if ( is_array( $val ) ) {
						$key            = sanitize_text_field( $key );
						$result[ $key ] = $this->sanitize_field( $val );
					} else {
						$key            = sanitize_text_field( $key );
						$result[ $key ] = sanitize_text_field( $val );
					}
				}
				return $result;
			} else {
				return sanitize_text_field( $array_or_string );
			}
		}

		/**
		 * スキン制御項目の有無判定
		 * @param  array $default_json_data 判定対象のデータ
		 * @return bool true：スキン制御された項目有り、false：スキン制御された項目無し
		 */
		private function has_skin_option( $default_json_data ) : bool {
			foreach ( $default_json_data as $array_data ) {
				if ( $this->exist_value_in_skin_option( $array_data['cocoon_option'], $array_data['id'] ) ) {
					return true;
				}
			}
			return false;
		}

		/**
		 * スキン制御項目で且つ値の有無判定
		 * @param  bool  $is_cocoon_option Cocoon設定対象判別フラグ
		 * @param  array $key              Cocoonオプションのキー名
		 * @return bool true：スキン制御項目で値有り、false：スキン制御項目では無いかあるいは値無し
		 */
		private function exist_value_in_skin_option( $is_cocoon_option, $key ) : bool {
			return $is_cocoon_option && is_skin_option( $key );
		}

		/**
		 * セレクトボックス OPTIONタグの生成
		 * @param  string $option_id ドロップダウンリスト表示時のキー名
		 * @return string            セレクトボックス OPTIONタグ
		 */
		private function generate_option_tag( $option_id ) : string {
			$data = array();
			$file = plugin_dir_path( __FILE__ ) . self::FOLDER_NAME_DEFAULTS . '/' . $option_id . self::JSON_FILE_EXTENSION;
			if ( ! file_exists( $file ) ) {
				return '';
			}

			$json_string = file_get_contents( $file );
			$json_data   = json_decode( $json_string, true );
			foreach ( $json_data as $array_data ) {
				$data[ $array_data['value'] ] = $this->get_esc_translate( $array_data['text'] );
			}
			ob_start();
			foreach ( $data as $value => $text ) {
				?>
				<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $text ); ?></option>
				<?php
			}
			$output = ob_get_clean();
			return $output;
		}

		/**
		 * カラー情報ファイルで定義されていない項目のtheme_modsデータでの補完
		 * @param  array $in_theme_mods theme_modsデータ
		 * @param  array $json_data     カラー情報ファイル内のキー名
		 */
		private function fill_json_data( $in_theme_mods, &$json_data ) : void {
			foreach ( $in_theme_mods as $key => $value ) {
				if ( ! array_key_exists( $key, $json_data ) ) {
					$json_data[ $key ] = $value;
				}
			}
		}

		/**
		 * テーマオプションの更新
		 * @param  string $path       カラー情報ファイルのパス
		 * @param  bool   $is_wp_cron true：wp_cronからの呼び出し時、false：本プラグインでの通常処理としての呼び出し時
		 */
		private function update_option( $path, $is_wp_cron = false ) : void {
			if ( is_user_administrator() || $is_wp_cron ) {
				if ( $json = wp_filesystem_get_contents( $path ) ) {
					$options = json_decode( $json, true );
					if ( ! is_null( $options ) ) {
						$mods = get_theme_mods();
						foreach ( $options as $name => $value ) {
							if ( array_key_exists( $name, $mods ) ) {
								$mods[ $name ] = $value;
							}
						}
						// テーマオプションの保存
						do_action('cocoon_settings_before_save');
						update_option( 'theme_mods_' . get_stylesheet(), $mods, 'yes' );
						// エディター用のカスタマイズCSS出力
						$this->cocoon_put_theme_css_cache_file();
						do_action('cocoon_settings_after_save');
					}
				}
			}
		}

		/**
		 * エディター用CSSファイルの書き出し
		 */
		private function cocoon_put_theme_css_cache_file() : void {
			if ( function_exists( 'put_theme_css_cache_file' ) ) {
				put_theme_css_cache_file();
			} else {
				ob_start();
				get_template_part('tmp/css-custom');
				$custum_css = ob_get_clean();
				if ($custum_css) {
					$custum_css_file = get_theme_css_cache_file();
					//エディター用CSSファイルの書き出し
					// \wp-content\uploads\cocoon-resources\css-cache\css-custom.css
					wp_filesystem_put_contents($custum_css_file, $custum_css);
				}
			}
		}

		/**
		 * ダークモード用CSSファイルの作成
		 * @param  string $darkmode_color_name ダークモード用として登録したカラー名
		 */
		private function create_darkmode_css_file( $darkmode_color_name ) : void {
			// 現在のCocoonオプション値を元にしたCSSファイルデータの取得
			$css_custom = $this->get_css_custom();

			// ファイル名の構築
			$css_file = $this->plugin_options_dir . self::FILE_CSCP_DARKMODE_CSS;
			// CSSファイルの作成
			// ダークモード用CSS定義で囲む @media (prefers-color-scheme: dark) {}
			file_put_contents( $css_file, '@media (prefers-color-scheme: dark) {' . $css_custom . '}' );
		}

		/**
		 * メンテナンスモード用CSSファイルの作成
		 * @param  string $maintenance_color_name メンテナンスモード用として登録したカラー名
		 */
		private function create_maintenance_css_file( $maintenance_color_name ) : void {
			// 現在のCocoonオプション値を元にしたCSSファイルデータの取得
			$css_custom = $this->get_css_custom();

			// ファイル名の構築
			$css_file = $this->plugin_options_dir . self::FILE_CSCP_MAINTEMODE_CSS;
			// CSSファイルの作成
			file_put_contents( $css_file, $css_custom );
		}

		/**
		 * 現在のCocoonオプション値を元にしたCSSファイルデータの取得
		 * 参考ソース：\cocoon-master\lib\utils.php wp_add_css_custome_to_inline_style()
		 * @return string CSSファイルデータ
		 */
		private function get_css_custom() : string {
	    global $_THEME_OPTIONS;
	    // スキンによっては $_THEME_OPTIONS を更新することがあるため退避
			$theme_options_save = $_THEME_OPTIONS;

			ob_start();//バッファリング
			get_template_part('tmp/css-custom');
			$css_custom = ob_get_clean();
			//CSSの縮小化
			$css_custom = minify_css($css_custom);

			$_THEME_OPTIONS = $theme_options_save;

			return $css_custom;
		}

		/**
		 * 本プラグインに関係するカラー、フォント関連のオプションの取得
		 * @param  array $default_json_data 本プラグインで扱うオプション
		 * @return array                    テーマオプションの現在値
		 */
		private function get_color_in_theme_mods( $default_json_data ) : array {
			$in_mods = array();

			if ( is_user_administrator() ) {
				$mods = get_theme_mods();
				foreach ( $default_json_data as $array_data ) {
					$key           = $array_data['id'];
					$cocoon_option = $array_data['cocoon_option'];
					if ( $cocoon_option ) {
						if ( array_key_exists( $key, $mods ) ) {
							$in_mods[ $key ] = $mods[ $key ];
						}
					}
				}
			}
			return $in_mods;
		}

		/**
		 * theme_modsは最新であるかの判断
		 * theme_modsがカレントのカラーファイルの値と一致している場合に最新であると判断する。
		 * @param  array  $in_theme_mods      theme_modsデータ
		 * @param  string $current_color_name カレントのカラー名
		 * @return bool                       true：最新、false：最新ではない
		 */
		private function is_theme_mods_latest( $in_theme_mods,  $current_color_name ) : bool {
			$current_color = $this->read_json_file( $this->plugin_options_dir . $current_color_name . self::JSON_FILE_EXTENSION );
			foreach ( $in_theme_mods as $name => $value ) {
				if ( array_key_exists( $name, $current_color ) ) {
					if ( $in_theme_mods[ $name ] !== $current_color[ $name ] ) {
						return false;
					}
				}
			}
			return true;
		}

		/**
		 * stripslashes処理
		 * @param  string $value stripslashes処理対象文字列
		 * @return string        stripslashes処理された文字列
		 */
		private function strip_slashes( $value ) : string {
			$stripped = stripslashes( $value );
			return $stripped;
		}

		/**
		 * 翻訳データの取得
		 * @return array 翻訳データ
		 */
		private function get_translation() : array {
			$result_mo_data = array();
			foreach ( $GLOBALS['l10n'][ $this->plugin_name ]->entries as $obj_data ) {
				$result_mo_data[ $obj_data->singular ] = $obj_data->translations[0];
			}
			return $result_mo_data;
		}

		/**
		 * アップロードファイル上限サイズの取得
		 * @param  array  $color_info_json_data カレントカラー情報
		 * @return int    アップロードファイル上限サイズ
		 */
		private function get_upload_max_file_size( $color_info_json_data ) : int {
			// アップロードファイル上限サイズの取得
			return self::SIZE_MB * $this->get_array_key_exists( self::UPLOAD_MAX_FILE_SIZE_MB, $color_info_json_data, self::INPUT_MAX_FILE_SIZE_MB );
		}

		/**
		 * リストア機能でのファイルのアップロード処理
		 * @param  int  $upload_max_file_size アップロードファイル上限サイズ
		 * @return bool true：正常終了、false：エラー
		 */
		private function do_restore_upload( $upload_max_file_size ) : bool {
			// ファイルアップロード情報のチェック
			$uploaded_info = $_FILES[self::INPUT_TYPE_FILE_NAME];
			if ( ! $this->check_uploaded_info( $uploaded_info, $upload_max_file_size ) ) {
				return false;
			}
			// キャッシュパスへ移動
			$upload_file = get_theme_cache_path() . self::FILE_OPTIONS_ZIP;
			if ( move_uploaded_file( $uploaded_info['tmp_name'], $upload_file) ) {
				$file_archive = new CSCP_FileArchive();
				if ( ! $file_archive->do_restore_upload( $upload_file ) ) {
					$this->error_message = $file_archive->get_error_message();
				}
			} else {
					$this->error_message = $this->get_esc_translate( 'アップロードファイルを移動できませんでした。' );
			}
			return '' === $this->error_message;
		}

		/**
		 * ファイルアップロード情報のチェック
		 * @param array $uploaded_info アップロード情報（$_FILES[]）
		 * @param  int  $upload_max_file_size アップロードファイル上限サイズ
		 * @return bool true：正常終了、false：エラー
		 */
		private function check_uploaded_info( $uploaded_info, $upload_max_file_size ) : bool {
			// アップロードの有無確認（アップロードファイルサイズ超過等々）
			if ( isset( $uploaded_info['error'] ) && ( ! is_numeric( $uploaded_info['error'] ) || $uploaded_info['error'] ) ) {
				if ( 2 === $uploaded_info['error'] ) {
					$this->error_message = $this->get_esc_translate( 'アップロードファイルのサイズが上限値を超えています。上限値:' ) . sprintf( '%d MB', $upload_max_file_size / self::SIZE_MB );
				} else {
					$this->error_message = $this->get_esc_translate( 'ファイルをアップロードできませんでした。エラー情報:' ) . $uploaded_info['error'];
				}
				return false;
			}
			// ファイルサイズのチェック
			$upload_file_size = filesize( $uploaded_info['tmp_name'] );
			if ( $upload_max_file_size < $upload_file_size ) {
				$this->error_message = $this->get_esc_translate( 'アップロードファイルのサイズが上限値を超えています。上限値:' ) . sprintf( '%d MB', $upload_max_file_size  / self::SIZE_MB );
				return false;
			}
			// ファイル形式のチェック
			$wp_filetype = wp_check_filetype_and_ext( $uploaded_info['tmp_name'], $uploaded_info['name'] );
			if ( isset( $wp_filetype['ext'] ) && isset( $wp_filetype['type'] ) &&
			     ('zip' !== $wp_filetype['ext'] || 'application/zip' !== $wp_filetype['type']) ) {
				$this->error_message = $this->get_esc_translate( 'アップロードファイルはzip形式ではありません。エラー情報:' ) . $wp_filetype['type'];
			}
			return '' === $this->error_message;
		}

		/**
		 * ファイルの削除
		 * @param  string $delete_file_path 削除するファイルパス
		 */
		private function delete_file( $delete_file_path ) : void {
			if ( file_exists( $delete_file_path ) ) {
				wp_filesystem_delete( $delete_file_path );
			}
		}

	} // class Cocoon_Scenery_Color end

	Cocoon_Scenery_Color::instance();

endif; // class_exists check
