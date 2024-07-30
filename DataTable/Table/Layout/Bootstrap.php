<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Table\Layout;

class Bootstrap implements DataTableLayoutInterface
{
    public function getName()
    {
        return 'boostrap-datatable-layout';
    }

    public function getDomDefinition()
    {
        return "<'row'<'col-sm-3 dom-position-filter-after'>>" .
            "<'row'<'col-sm-12'tr>>" .
            "<'row'<'col-sm-4'i><'col-sm-4'p><'col-sm-2'l>>";
    }
}
