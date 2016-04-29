<?php


namespace CrossKnowledge\DataTableBundle\DataTable\Formatter;

use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;

interface FormatterInterface
{
    public function formatRow($row, AbstractTable $table, $context);
}
