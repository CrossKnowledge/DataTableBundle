<?php


namespace CrossKnowledge\DataTableBundle\DataTable;

use CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column\ColumnInterface;

/**
 * Class ColumnBuilder
 *
 * @package CrossKnowledge\DataTableBundle\Element\Column
 */
class ColumnBuilder
{
    protected array $columns;

    /**
     * @param string $colidentifier
     * @param ColumnInterface $definition
     */
    public function add(string $colidentifier, ColumnInterface $definition)
    {
        $definition->setIdentifier($colidentifier);
        $this->columns[$colidentifier] = $definition;

        return $this;
    }

    public function getColumns()
    {
        return $this->columns;
    }
}
