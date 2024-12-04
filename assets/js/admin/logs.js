jQuery(document).on('change', 'select#logs-files-list-select', function() {
    jQuery('#log_display').html("<p>Loading...</p>");

    jQuery.ajax({
        url: logs_viewer_js_data.ajax_url,
        // url: window.url,
        type: 'GET',
        dataType: 'JSON',
        data: {
            action: 'load_log',
            data: jQuery('#logs-files-list-select').val(),
        },
        success: (result) => {
            jQuery('#log_display').html("");

            result.data.forEach((logLine) => {
                let html = "<p>" + logLine.date + " - " + logLine.logger + " " + logLine.level + " : " + logLine.message;

                if (logLine['context'].length !== 0) {
                    html += "<details><summary>[ View Context ]</summary><div style='white-space: pre'>"
                        + JSON.stringify(logLine.context, null, 2)
                        + "</div></details>";
                }

                html += "</p>";
                jQuery('#log_display').append(html);
            })
        },
    });
});