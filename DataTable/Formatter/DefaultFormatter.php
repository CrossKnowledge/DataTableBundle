<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Formatter;

use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;

class DefaultFormatter implements FormatterInterface
{
    /**
     * Contains common output behavior here based on columns definitions.
     *
     * @param $row
     * @param AbstractTable $table
     * @param $context
     * @return array
     */
    public function formatRow($row, AbstractTable $table, $context)
    {
        $cols = $table->getColumns($context);
        $newRow = [];

        foreach ($cols as $colIdentifier => $column) {
            $rawValue = array_key_exists($colIdentifier, $row) ? $row[$colIdentifier] : "";

            if ($column->getOptions()['auto_escape']) {
                $value = htmlentities($rawValue, ENT_COMPAT | ENT_HTML401, 'UTF-8');
            } else {
                $value = $rawValue;
            }

            $colVal = $column->formatCell($value, $row, $context);//Column definition
            $newRow[$colIdentifier] = $colVal;
        }

        return $newRow;
    }
}
