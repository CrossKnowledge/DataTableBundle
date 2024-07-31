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
     *
     * @return ColumnBuilder
     */
    public function add($colidentifier, ColumnInterface $definition): self
    {
        $definition->setIdentifier($colidentifier);
        $this->columns[$colidentifier] = $definition;

        return $this;
    }

    /**
     * @return ColumnInterface[]
     */
    public function getColumns(): array
    {
        return $this->columns;
    }
}
