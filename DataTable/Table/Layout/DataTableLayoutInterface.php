<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Table\Layout;

interface DataTableLayoutInterface
{
    /**
     * @return string layout name
     */
    public function getName(): string;

    /**
     * @return string conform to https://datatables.net/reference/option/dom (empty = default layout)
     */
    public function getDomDefinition(): string;
}
