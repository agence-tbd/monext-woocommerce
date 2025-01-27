<?php

declare( strict_types=1 );
defined( 'ABSPATH' ) || exit;
/**
 * Variables used in this file.
 * @var array $args Array of meta data.
 */
?>
<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo esc_html__('Payline Logs Viewer', 'payline'); ?></h1>
    <hr class="wp-header-end">
    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-1">
            <div id="post-body-content">
                <div class="meta-box-sortables">
                    <div class="postbox">

                        <h2>
                            <span><?php echo esc_html__('Please select a log file', 'payline'); ?></span>
                        </h2>
                        <div class="inside">
                            <form id="log-form">
                                <label for="logs-files-list-select"><?php echo esc_html__('Log file', 'payline'); ?>:</label>
                                <select id="logs-files-list-select" name="logs-files-list-select">
                                    <option value="">-- <?php echo esc_html__('Please select a log file', 'payline'); ?> --</option>
									<?php foreach ($args["logsFilesArray"] as $logFile): ?>
                                        <option value="<?php echo esc_attr($logFile); ?>">
											<?php echo esc_html($logFile); ?>
                                        </option>
									<?php endforeach; ?>
                                </select>
                            </form>
                        </div>

                        <h2>
                            <span><?php echo esc_html__('File content', 'payline'); ?></span>
                        </h2>
                        <div class="inside" id="log-content">
                            <div id="log_container" class="card log_container">
                                <div id="log_display" class="card-body log_display">
                                    <p><?php echo esc_html__('Please select a log file', 'payline'); ?></p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
