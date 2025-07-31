<?php

use Dubture\Monolog\Reader\LogReader;
class PaylineLogsViewer {

	public static function showLogs()
	{
		$template_data = array(
			'logsFilesArray' => self::getPaylineLogsFilesList(),
		);

//		wc_get_template( 'admin/logs-viewer.php', $template_data );
		load_template( WP_PLUGIN_DIR.'/payline/templates/admin/logs-viewer.php', true, $template_data );
	}

	protected static function getPaylineLogsFilesList()
	{
		$logsFiles = [];
		$directoryPath = WP_CONTENT_DIR.'/uploads/wc-logs/payline';
		if (is_dir($directoryPath)) {
			$files = scandir($directoryPath);
			$files = array_diff($files, array('.', '..')); // Exclure les entrées spéciales
			foreach ($files as $file) {
				$logsFiles[] = $file;
			}
		}
		return $logsFiles;
	}

	public static function doAjaxGetLogs()
	{
		$logFileContent = self::getLogsLines($_GET['data']);
		wp_send_json_success($logFileContent);
	}

	protected static function getLogsLines($logFilename)
	{
		$logFileContent = [];
		if ($logFilename) {
			$logFile =  WP_CONTENT_DIR.'/uploads/wc-logs/payline/'. $logFilename;
			$reader = new LogReader($logFile, 0);

			foreach ($reader as $log) {
				if (!empty($log) && !empty($log['date'])) {

					$logFileContent[] = [
						'date' => $log['date']->format('d-m-Y'),
						'logger' => $log['logger'],
						'level' => $log['level'],
						'message' => $log['message'],
						'context' => $log['context'],
					];
				}
			}
		}
		return $logFileContent;
	}
}