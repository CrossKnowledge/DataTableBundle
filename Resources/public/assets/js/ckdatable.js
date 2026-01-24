// Ensure jQuery is available and set up noConflict for Prototype.js
if (typeof window.jQuery !== 'undefined') {
    window.jQueryForDataTable = window.jQuery.noConflict();
}

jQuery(function($) {
    var $configDatatables = $('.ck-datatable');
    if ($configDatatables.length === 0) {
        console.error('DataTable initialization failed: No configuration div found');
        return;
    }

    $configDatatables.each(function(idx, configDiv) {
        var $wrapper = $(configDiv);
        var $table = $wrapper.find('table');
        if ($table.length && $wrapper.length) {
            try {
                var ajaxUrl = $wrapper.attr('data-cktable-ajax-url');
                var columns = JSON.parse($wrapper.attr('data-cktable-columns') || '{}');
                var customOptions = JSON.parse($wrapper.attr('data-cktable-custom-options') || '{}');
                
                // Convert columns object to array
                var columnArray = [];
                for (var key in columns) {
                    var column = columns[key];
                    columnArray.push(column);
                }

                var options = {
                    ajax: {
                        url: ajaxUrl,
                        dataSrc: 'data',
                        type: 'POST'
                    },
                    serverSide: true,
                    columns: columnArray,
                    processing: true,
                    searching: false,
                    paging: true,
                    // DataTables DOM layout: r=processing display, t=table, i=information summary, l=length changing input
                    dom: 'rt<"bottom-row"<"bottom-left"i><"bottom-right"l>>',
                    pageLength: 10,
                    lengthMenu: [10, 25, 50, 100],
                    language: {
                        lengthMenu: "Show _MENU_ entries"
                    },
                    ordering: true
                };
                
                // Apply custom options if any
                if (customOptions && Object.keys(customOptions).length > 0) {
                    options = $.extend(true, {}, options, customOptions);
                }

                // Initialize DataTable
                var dataTable = new DataTable($table[0], options);
            } catch (error) {
                console.error('DataTable initialization failed:', error);
            }
        }
    });
});