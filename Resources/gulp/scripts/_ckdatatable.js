
(function ($) {
    /**
     * Bridge between Datatable JS Api & Crossknowledge's symfony bundles
     *
     * One instance is one table and everything related to it :
     * - Datatable JS lib instance
     * - filter wrapper jq element
     * - wrapper jq element
     * - event listeners
     */
    class CkDataTable {
        /**
         * @param jquery $element the main element that contains "how to setup" infos as data attributes
         */
        constructor($element) {
            this.setElement($element);
            if (!this.filterableContainer.hasClass('no-init-loading')){
                this.initTable();
            }
        }
        initTable(){
            //Prevent an already existing dataTable to be register
            if (this.element.data('cktable-initialized') == undefined) {
                this.initUsingContainer();
                this.element.data('cktable-initialized', true);
            }
        }
        reloadTable(){
            if (!this.table){
                this.initTable();
            }
            else{
                this.table.ajax.reload(() => {
                    this.element.trigger('finishRefresh');
                });
            }
            return false;
        }

        /**
         * Set the main wrapper that contains infos as data attributes
         */
        setElement($element) {
            this.element = $element;
            this.filterableContainer = this.element.find('.ck-datatable-filter-container');
            this.initFilterable();
            this.initPerPage();
        }

        initPerPage() {
            var $select = this.element.find('.datatable-length-container select');
            if ($select.length > 0 && $select.val()!="") {
                this.perPage = $select.val();
            } else {
                this.perPage = 10;
            }
        }

        /**
         * DOM events init and set filter positionning
         */
        initFilterable() {

            this.filterableContainer.data('dom-positionning-complete', false);
            this.filterableContainer.find('button').on('click', () => {
                return this.reloadTable();
        });

            if (this.filterableContainer.hasClass('filter-onchange')) {
                this.filterableContainer.find('select').on('change', () => {
                    return this.reloadTable();
            });
                this.timer = false;
                this.filterableContainer.find('input').on('keyup', () => {
                    if (this.timer != false) {
                    clearTimeout(this.timer);
                    this.timer = false;
                }

                this.timer = setTimeout(() => {
                    return this.reloadTable();
            }, 400);
            });
            }

            this.filterableContainer.find('input').on('keypress', (e) => {
                if(e.keyCode==13) {
                return this.reloadTable();
            }
        });
        }
        /**
         * Format this.data for datatable js api constructor
         */
        getFormattedData() {
            return $(this.data).map(function(idx, item) {
                let t = new Array();

                for (let i in item) {
                    t.push(item[i]);
                }
                return [t];
            });
        }
        /**
         * Format this.columns for datatable js api constructor
         */
        formatColumnOption() {
            let t = [];

            for (let i in this.columns) {
                var def = $.extend({}, this.columns[i]);
                //def.data = i;
                t.push(def);
            }

            return t;
        }
        /**
         * @return string the filter values
         */
        getFilteredDataValues() {
            let t = {};
            this.filterableContainer.find('input,select').each(function() {
                t[$(this).attr('name')] = $(this).val();
            });

            return t;
        }

        tableDrawCallback() {
            //Reposition filter
            if (this.filterableContainer.data('dom-positionning-complete')===false) {
                this.filterableContainer.insertAfter(this.element.find('.dom-position-filter-after'));
                this.filterableContainer.data('dom-positionning-complete', true);
            }

            let paginate = this.element.find('.dataTables_paginate');
            if (paginate.length > 0) {
                //Empty paging divs if only one page
                let pagingSize = paginate.find('.paginate_button:not(.next,.previous)').length;
                if (pagingSize === 1) {
                    paginate[0].style.display = 'none';
                } else {
                    paginate[0].style.display = 'block';
                }
            }
        }
        /**
         * Init the datatable instance using the dom element's container data-attributes
         */
        initUsingContainer() {

            this.url = this.element.data('cktable-ajax-url');

            this.columns = this.element.data('cktable-columns');
            let colList = this.formatColumnOption();


            let options = {
                ajax: {
                    url: this.url,
                    dataSrc: 'data',
                    type: 'POST',
                    data: (d) => {
                        return $.extend(d, this.getFilteredDataValues());
                    },
                },
                drawCallback: (settings) => {
                    this.tableDrawCallback();
                },
                //deferLoading: this.element.data('total-row-count'),
                serverSide: true,
                //data: data,
                bFilter: this.element.data('cktable-clientside-filtering'),
                columns: colList
            };

            if (this.element.data('cktable-custom-dom')) {
                options.dom = jQuery.parseJSON(this.element.data('cktable-custom-dom'));
            }

            var customOptions = this.element.data('cktable-custom-options');
            if (customOptions!=undefined) {
                options = $.extend({}, options, customOptions);
            }
            this.table = this.element.find('table').DataTable(options);
        }
    }

    function registerEvents() {
        $('.ck-datatable').each(function () {
            var tb = new CkDataTable($(this));
        });
    }

    module.exports = {
        registerEvents,
        CkDataTable
    }

})(jQuery);
