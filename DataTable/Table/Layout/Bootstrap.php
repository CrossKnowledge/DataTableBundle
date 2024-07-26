<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Table\Layout;

class Bootstrap implements DataTableLayoutInterface
{
    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return 'boostrap-datatable-layout';
    }

    /**
     * @inheritdoc
     */
    public function getDomDefinition(): string
    {
        return "<'row'<'col-sm-3 dom-position-filter-after'>>" .
            "<'row'<'col-sm-12'tr>>" .
            "<'row'<'col-sm-4'i><'col-sm-4'p><'col-sm-2'l>>";
    }
}
