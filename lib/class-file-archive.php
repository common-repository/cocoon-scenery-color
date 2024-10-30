<?php
/**
 * Cocoon Scenery Color Plugin File Archive class
 *
 * @version 1.0.0
 * @author nshuuo36 <vento@lacaraterra.site>
 * @copyright Copyright (c) 2021 lacaraterra
 * @license https://opensource.org/licenses/gpl-2.0.php GPLv2
 * @link https://lacaraterra.site
 */

if( ! class_exists( 'CSCP_FileArchive' ) ) :

	require_once __DIR__ . '/class-log-message.php';

	/**
	 * File Archive class.
	 * JSONファイル（カラーファイル）のバックアップ・リストア機能で使用します。
	 * バックアップ処理でのファイル形式は、ZipArchiveクラスを利用してzip圧縮形式とします。
	 * リストア処理では、WordPressが提供しているunzip_file関数を使用してzip圧縮形式のファイルを解凍処理します。
	 */
	class CSCP_FileArchive {

		const FILE_OPTIONS_ZIP             = 'options.zip';
		const FOLDER_NAME_OPTIONS          = 'options';
		const FOLDER_NAME_DOWNLOAD         = 'download';
		const FOLDER_NAME_UPLOAD           = 'upload';
		const JSON_FILE_EXTENSION          = 'json';
		const CSS_FILE_EXTENSION           = 'css';
		// カレントカラー
		const FILE_SCENERY_COLOR_INFO_JSON = 'scenery-color-info' . '.' . self::JSON_FILE_EXTENSION;


		// private
		private $plugin_name;
		private $plugin_dir;
		private $plugin_options_dir;
		private $plugin_download_dir;
		private $plugin_upload_dir;
		private $scenery_color_info_json_path;
		private $zip_file_path;
		private $unzip_working_dir = '';
		private $error_message = '';

		/**
		 * デフォルトコンストラクタ
		 */
		public function __construct() {
			$this->plugin_name                  = dirname( plugin_basename( __FILE__ ), 2 );
			$this->plugin_dir                   = dirname( plugin_dir_path( __FILE__ ), 1 );
			$this->plugin_options_dir           = $this->plugin_dir . '/' . self::FOLDER_NAME_OPTIONS . '/';
			$this->plugin_download_dir          = $this->plugin_dir . '/' . self::FOLDER_NAME_DOWNLOAD . '/';
			$this->plugin_upload_dir            = $this->plugin_dir . '/' . self::FOLDER_NAME_UPLOAD . '/';
			$this->scenery_color_info_json_path = $this->plugin_dir . '/' . self::FILE_SCENERY_COLOR_INFO_JSON;
		}

		/**
		 * エラーメッセージを取得します。
		 * @return string エラーメッセージ
		 */
		public function get_error_message() : string {
			return $this->error_message;
		}

		/**
		 * バックアップ機能でのファイルのダウンロード処理を行います。
		 */
		public function do_backup_download() : void {
			if ( $this->create_backup_file() ) {
				$this->download_zip_file();
				unlink( $this->zip_file_path );
			}
		}

		/**
		 * バックアップファイルを作成します。
		 * @return bool   true：正常終了、false：エラー
		 */
		public function create_backup_file() : bool {
			$this->zip_file_path = $this->plugin_download_dir  . self::FILE_OPTIONS_ZIP;
			return $this->make_zip_file();
		}

		/**
		 * リストア機能でのファイルのアップロード処理を行います。
		 * @param  string $upload_zip_file   アップロードファイル
		 * @return bool   true：正常終了、false：エラー
		 */
		public function do_restore_upload( $upload_zip_file ) : bool {
			$this->zip_file_path = $upload_zip_file;
			// zipファイルの解凍
			$result = $this->unpack_upload_file();
			if ( $result ) {
				$result = $this->restore_zip_file();
			}
			unlink( $this->zip_file_path );
			return $result;
		}

		/**
		 * バックアップ機能でのファイルのダウンロード処理を行います。
		 * @return bool true：正常終了、false：エラー
		 */
		private function make_zip_file() : bool {
			if ( ! class_exists( 'ZipArchive' ) ) {
				$this->error_message = $this->get_esc_translate( "ZipArchiveクラスが無効です。" );
				return false;
			}
			$zip = new ZipArchive();
			$result = $zip->open( $this->zip_file_path, ZipArchive::CREATE | ZipArchive::OVERWRITE );
			if ( true === $result ) {
				// カレントカラー情報格納ファイルの圧縮
				$this->json_css_file_to_zip( $zip, $this->scenery_color_info_json_path, basename($this->scenery_color_info_json_path) );
				// optionsフォルダの圧縮
				$this->folder_to_zip( $zip, $this->plugin_options_dir, basename($this->plugin_options_dir) . '/' );
				$zip->close();
			} else {
				$this->error_message = $this->get_esc_translate( "ZipArchiveクラスのopenメソッドでエラーが発生しました。エラーコード:" )  . $result;
			}
			return true === $result;
		}

		/**
		 * zipファイルをHTMLへ出力してダウンロードします。
		 */
		private function download_zip_file() : void {
			header('Content-Type: application/zip; name="' . self::FILE_OPTIONS_ZIP . '"');
			header('Content-Disposition: attachment; filename="' . self::FILE_OPTIONS_ZIP . '"');
			header('Content-Length:' . filesize( $this->zip_file_path ));
			header('Pragma: no-cache');
			header('Cache-Control: no-cache');
			readfile( $this->zip_file_path );
		}

		/**
		 * 指定された1ファイルを圧縮します。
		 * ファイルの拡張子が'json'または'css'であるファイルが対象です。
		 * @param object $zip         ZipArchiveオブジェクト
		 * @param string $source_path 圧縮対象ファイルのパス
		 * @param string $parent_path 圧縮時に格納する相対パスフォルダ
		 */
		private function json_css_file_to_zip( $zip, $source_path, $parent_path = '' ) : void {
			if ( file_exists( $source_path ) && 
					 ( self::JSON_FILE_EXTENSION === pathinfo( $source_path, PATHINFO_EXTENSION ) ||
					   self::CSS_FILE_EXTENSION  === pathinfo( $source_path, PATHINFO_EXTENSION ) ) ) {
				$zip->addFile( $source_path, mb_convert_encoding( $parent_path, 'CP932', 'UTF-8' ) );
			}
		}

		/**
		 * 指定されたフォルダを再帰的に圧縮します。
		 * @param object $zip         ZipArchiveオブジェクト
		 * @param string $source_path 圧縮対象ファイルのパス
		 * @param string $parent_path 圧縮時に格納する相対パスフォルダ
		 */
		private function folder_to_zip( $zip, $source_path, $parent_path = '' ) : void {
			if ( ! file_exists( $source_path ) ) {
				return;
			}

			$handle = opendir( $source_path );
			while ( false !== ( $entry = readdir( $handle ) ) ) {
				if ( '.' !== $entry && '..' !== $entry ) {
					$local_path = $parent_path . $entry;
					$full_path = $source_path . $entry;
					if ( is_file( $full_path ) ) {
						// $zip->addFile( $full_path, $local_path);
						$this->json_css_file_to_zip( $zip, $full_path, $local_path );
					// サブフォルダは圧縮対象としない } else if (is_dir( $full_path )) {
					// サブフォルダは圧縮対象としない 	$zip->addEmptyDir( $local_path );
					// サブフォルダは圧縮対象としない 	$this->folder_to_zip( $zip, $full_path . '/', $local_path . '/' );
					}
				}
			}
			closedir( $handle );
		}

		/**
		 * zipファイルを解凍します。
		 * @return bool   true：正常終了、false：エラー
		 */
		private function unpack_upload_file() {
			if ( ! class_exists( 'ZipArchive' ) ) {
				$this->error_message = $this->get_esc_translate( "ZipArchiveクラスが無効です。" );
				return false;
			}
			$this->unzip_working_dir = $this->plugin_upload_dir . basename( $this->zip_file_path, '.zip' ) . '/';
			$zip = new ZipArchive();
			$result = $zip->open( $this->zip_file_path );
			if ( true === $result ) {
				for ( $index = 0; $index < $zip->numFiles; $index++ ) {
					// getNameIndex( $index )でファイル名を取得すると、不明な文字コードで変換されたファイル名を取得してしまうため、
					// getNameIndex()の第2引数で ZipArchive::FL_ENC_RAW（何も手を加えない文字列を取得します）を指定します。
					// $file_name = $zip->getNameIndex( $index );
					$file_name = $zip->getNameIndex( $index, ZipArchive::FL_ENC_RAW );
					// SJISをUTF-8へ変換
					$file_name = mb_convert_encoding( $file_name, 'UTF-8', 'CP932' );
					// ZipArchive内で保持しているエントリの name もUTF-8へ変換した名前で置き換えます。
					$zip->renameIndex( $index, $file_name );
					// これで extractTo関数 は正常に行われます。
					$result = $zip->extractTo( $this->unzip_working_dir, $file_name );
					if ( ! $result ) {
						$this->error_message = $this->get_esc_translate( "ZipArchiveクラスのextractToメソッドでエラーが発生しました。" );
					}
				}
				$zip->close();
      } else {
				$this->error_message = $this->get_esc_translate( "ZipArchiveクラスのopenメソッドでエラーが発生しました。エラーコード:" )  . $result;
      }
			return '' === $this->error_message;
		}

		/**
		 * 解凍されたファイルを所定のフォルダへ復元します。
		 * @return bool   true：正常終了、false：エラー
		 */
		private function restore_zip_file() : bool {
			global $wp_filesystem;
			if ( empty( $wp_filesystem ) ) {
				WP_Filesystem();
			}

			// 解凍後のフォルダ構成をチェック
			if ( ! file_exists( $this->unzip_working_dir . self::FOLDER_NAME_OPTIONS ) ) {
				$this->error_message = $this->get_esc_translate( "アップロードファイルのフォルダ構成が正しくありません。" );
				return false;
			}

			// カレントカラー情報が格納されているファイルの復元
			$from_file = $this->unzip_working_dir . self::FILE_SCENERY_COLOR_INFO_JSON;
			if ( file_exists( $from_file ) && $this->check_json_type( $from_file ) ) {
				$overwrite = true;
				if ( ! $wp_filesystem->copy( $from_file, $this->scenery_color_info_json_path, $overwrite, FS_CHMOD_FILE ) ) {
					$this->error_message = $this->get_esc_translate( "ファイルコピーでエラーが発生しました。エラーメッセージ:" ) . self::FILE_SCENERY_COLOR_INFO_JSON;
					return false;
				}
			}

			// optionsフォルダの復元
			$from_dir  = $this->unzip_working_dir . self::FOLDER_NAME_OPTIONS . '/';
			$to_dir    = $this->plugin_options_dir;

			// 復元対象のチェック
			$skip_list = array();
			$include_hidden = false;
			$recursive = false;
			$unpack_files = $wp_filesystem->dirlist( $from_dir, $include_hidden, $recursive );
			$unpack_files = $this->flatten_dirlist( $unpack_files );
			if ( ! empty( $unpack_files ) ) {
 				foreach ( $unpack_files as $file_name => $file_details ) {
 					$file_path = $from_dir . $file_name;
					if ( $wp_filesystem->is_dir( $file_path )) {
						if ( self::FOLDER_NAME_OPTIONS !== $file_name ) {
							// optionsフォルダ以外はスキップ対象
							array_push( $skip_list, $file_name );
						}
					} else if ( $wp_filesystem->is_file( $file_path ) &&
							! ( ( self::JSON_FILE_EXTENSION === pathinfo( $file_path, PATHINFO_EXTENSION ) && 
										$this->check_json_type( $file_path ) ) ||
							 		( self::CSS_FILE_EXTENSION  === pathinfo( $file_path, PATHINFO_EXTENSION ) && 
										$this->check_css_type( $file_path ) ) ) ) {
						// 拡張子が'((json'且つJSON形式) または ('css'且つCSS形式))以外はスキップ対象
						array_push( $skip_list, $file_name );
					}
				}
			}

			// 解凍元フォルダからの復元（ファイルコピー）
			$result = copy_dir( $from_dir, $to_dir, $skip_list );
			if ( is_wp_error( $result ) ) {
				$this->error_message = $this->get_esc_translate( "ファイルコピーでエラーが発生しました。エラーメッセージ:" ) . $result->get_error_message();
			}
			
			// 解凍元フォルダの削除
			$wp_filesystem->delete( $this->unzip_working_dir, true );

			return ! is_wp_error( $result );
		}

		/**
		 * 指定ファイルがJSON形式かをチェックします。
		 * @param array $file_path ファイルパス
		 * @return true：JSON形式、false：以外
		 */
		private function check_json_type( $file_path ) : bool {
			$finfo     = finfo_open( FILEINFO_MIME_TYPE );
			$real_mime = finfo_file( $finfo, $file_path );
			finfo_close( $finfo );
			return 'application/json' === $real_mime;
		}

		/**
		 * 指定ファイルがCSS形式かをチェックします。
		 * @param array $file_path ファイルパス
		 * @return true：CSS形式、false：以外
		 */
		private function check_css_type( $file_path ) : bool {
			$finfo     = finfo_open( FILEINFO_MIME_TYPE );
			$real_mime = finfo_file( $finfo, $file_path );
			finfo_close( $finfo );
			return 'text/plain' === $real_mime;
		}

		/**
		 * dirlistの再帰モードで取得したファイル名をフラットなパスへ変換します。
		 * @param array  $nested_files dirlistの再帰モードで取得したファイル情報
		 * @return array ファイル名をフラットなパスへ変換したファイル情報
		 */
		private function flatten_dirlist( $nested_files, $path = '' ) {
			$files = array();
			foreach ( $nested_files as $name => $details ) {
				$files[ $path . $name ] = $details;
				// Append children recursively.
				if ( ! empty( $details['files'] ) ) {
					$children = $this->flatten_dirlist( $details['files'], $path . $name . '/' );
					// Merge keeping possible numeric keys, which array_merge() will reindex from 0..n.
					$files = $files + $children;
				}
			}
			return $files;
		}

		private function get_esc_translate( $text ) : string {
			return esc_html__( $text, $this->plugin_name );
		}

	} // class CSCP_FileArchive end

endif; // class_exists check
