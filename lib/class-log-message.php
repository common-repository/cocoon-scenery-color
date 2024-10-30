<?php
/**
 * Cocoon Scenery Color Plugin My Log message class
 *
 * @version 1.0.0
 * @author nshuuo36 <vento@lacaraterra.site>
 * @copyright Copyright (c) 2021 lacaraterra
 * @license https://opensource.org/licenses/gpl-2.0.php GPLv2
 * @link https://lacaraterra.site
 */

if( ! class_exists( 'CSCP_MyLogMessage' ) ) :

/**
 * My Log Message class.
 */
class CSCP_MyLogMessage {

  // DateIntervalを使用して時間差を算出するための変数
  private DateTime $startDateObj, $endDateObj;
  // 処理時間計測時のメッセージ
  private string $message;

  /**
   * 指定したメッセージをログへ出力します。
   * メッセージは、sprintf形式で指定します。
   * @return string 出力したメッセージ
   */
  public static function logMessage($arg1, ...$args) : string {
    $tempMessage = self::makeLogMessage($arg1, ...$args);
    self::my_error_log( $tempMessage );
    return $tempMessage;
  }

  /**
   * 指定した情報メッセージをログへ出力します。
   * メッセージは、sprintf形式で指定します。
   * メッセージの先頭に【INFO】文字列を出力します。
   * @return string 出力したメッセージ
   */
  public static function infoLogMessage($arg1, ...$args) : string {
    $tempMessage = self::makeLogMessage($arg1, ...$args);
    self::my_error_log( '[INFO]' . $tempMessage );
    return $tempMessage;
  }

  /**
   * 指定した警告メッセージをログへ出力します。
   * メッセージは、sprintf形式で指定します。
   * メッセージの先頭に【WARN】文字列を出力します。
   * @return string 出力したメッセージ
   */
  public static function warnLogMessage($arg1, ...$args) : string {
    $tempMessage = self::makeLogMessage($arg1, ...$args);
    self::my_error_log( '[WARN]' . $tempMessage );
    return $tempMessage;
  }

  /**
   * 指定したエラーメッセージをログへ出力します。
   * メッセージは、sprintf形式で指定します。
   * メッセージの先頭に【ERROR】文字列を出力します。
   * @return string 出力したメッセージ
   */
  public static function errorLogMessage($arg1, ...$args) : string {
    $tempMessage = self::makeLogMessage($arg1, ...$args);
    self::my_error_log( '[ERROR]' . $tempMessage );
    return $tempMessage;
  }

  /**
   * バックトレースを出力します。
   * @param integer $limit スタックフレーム数（呼び出し階層数）。0 は全てのスタックフレームを出力します。
   * @return void
   */
  public static function printBacktrace( int $limit = 0 ) : void {
    // バックトレースの取得
    ob_start();
    debug_print_backtrace( $limit );
    $trace = ob_get_clean();
    // 改行でバックトレース情報を分割
    $rows = explode(PHP_EOL, str_replace( array("\r\n", "\r", "\n"), PHP_EOL, $trace ) );
    // バックトレース情報の最後が空行であれば削除
    if ( empty( $rows[count( $rows ) - 1] ) ) {
        array_pop( $rows );
    }
    // バックトレースに本メソッドの情報以外が含まれる場合は先頭の本メソッドの情報を削除
    if ( 1 < count($rows) ) {
      array_shift( $rows );
    }
    // 配列情報を文字列＋改行へ変換
    $trace = trim( implode( PHP_EOL, $rows ) );
    self::my_error_log( sprintf( '<----- Backtrace(limit:%d) start ----->', $limit ) . PHP_EOL . $trace );
    self::my_error_log( '<----- Backtrace end ----->' );
  }

  /**
   * バックトレースを出力します。
   * @param integer $limit スタックフレーム数（呼び出し階層数）。0 は全てのスタックフレームを出力します。
   * @return void
   */
  public static function printBacktrace2( int $limit = 0 ) : void {
    // バックトレースの出力
    self::logMessage( '<----- Backtrace(%d) start ----->', $limit );
    ob_start();
    debug_print_backtrace( $limit );
    $trace = ob_get_clean();
    self::my_error_log( $trace );
    self::my_error_log( '<----- Backtrace end ----->' );
  }

  /**
   * 処理時間の計測を開始します。
   * @param string $message 開始メッセージ
   * @return void
   */
  public function startLog( string $message) : void {
    $this->message = $message;
    self::logMessage( '<----- ' . $this->message . ' start ----->' );
    $this->startDateObj = new DateTime();
  }

  /**
   * 処理時間の計測を終了します。
   * startLogメソッドで指定したメッセージと処理時間を出力します。
   * @return void
   */
  public function stopLog() : void {
    // DateIntervalを使用して時間差を算出
    $this->endDateObj = new DateTime();
    $diffObj = $this->startDateObj->diff($this->endDateObj);
    self::logMessage( '<----- ' . $this->message . ' end ' . $diffObj->format( '処理時間 %I:%S.%F' ) . ' ----->' );
  }

  /**
   * 指定したメッセージをsprintf関数を呼び出して文字列として組み立てます。
   * @return string 出力したメッセージ
   */
  private static function makeLogMessage($arg1, ...$args) : string {
    $get_args = func_get_args();
    $tempMessage = call_user_func_array( 'sprintf', $get_args );
    return $tempMessage;
  }

  /**
   * 出力条件によってerror_log()を呼び出します。
   * @param string $message 出力メッセージ
   * @return void
   */
  private static function my_error_log( $message ) : void {
//  self::my_error_log_force( $message );
//  return;

    $debug_file = plugin_dir_path( __FILE__ ) . 'debug.json';
    if ( file_exists( $debug_file ) ) {
			$json_string     = file_get_contents( $debug_file );
			$debug_json_data = json_decode( $json_string, true );
			if ( isset($debug_json_data['my_log_output']) && '1' === $debug_json_data['my_log_output'] ) {
				$log_file = dirname( plugin_dir_path( __FILE__ ), 1 ) . '/log/cscp-log.txt';
				$max_log_file_size = 1024 * 1024 * 3;
				if ( isset($debug_json_data['max_my_log_file_size']) ) {
					$max_log_file_size = $debug_json_data['max_my_log_file_size'];
				}
				if ( file_exists( $log_file ) ) {
					$log_file_size = filesize( $log_file );
					// ログファイルサイズ超過時は削除
					if ( $max_log_file_size < $log_file_size ) {
						unlink( $log_file );
					}
				}
	      $message_type = 3;
	      error_log( date('[Y-m-d H:i:s D T] ') . $message . PHP_EOL, $message_type, $log_file );
			} else {
				error_log( $message );
			}
    }
  }

  /**
   * error_log()を呼び出します。
   * @param string $message 出力メッセージ
   * @return void
   */
  private static function my_error_log_force( $message ) : void {
		error_log( $message );
  }

}

endif; // class_exists check
