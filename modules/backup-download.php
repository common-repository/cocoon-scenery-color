<?php // カラー情報（カラーファイル）のバックアップファイルをダウンロードします。
/**
 * Cocoon Scenery Color Plugin
 *
 * @version 1.0.0
 * @author nshuuo36 <vento@lacaraterra.site>
 * @copyright Copyright (c) 2021 lacaraterra
 * @license https://opensource.org/licenses/gpl-2.0.php GPLv2
 * @link https://lacaraterra.site
 */

require_once '../../../../wp-load.php';
require_once get_template_directory() . '/lib/_defins.php';

if ( is_user_administrator() ) {
	$file_archive = new CSCP_FileArchive();
	$file_archive->do_backup_download();
}
exit;

?>
