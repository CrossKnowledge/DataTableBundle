(function ($) {

    var datatables = require('./_ckdatatable');

    $(document).on('ready', function() {
        datatables.registerEvents();
    });

})(jQuery);

