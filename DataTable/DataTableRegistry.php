<?php

namespace CrossKnowledge\DataTableBundle\DataTable;

use BadMethodCallException;
use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;

class DataTableRegistry
{
    /**
     * @var Table\AbstractTable[]
     */
    protected array $tables;

    /**
     * @param AbstractTable[] $tableList
     */
    public function __construct(array $tableList)
    {
        $this->tables = $tableList;
    }

    public function retrieveByTableId($tableId): AbstractTable
    {
        if (!array_key_exists($tableId, $this->tables)) {
            throw new BadMethodCallException('Table id with ' . $tableId . ' is not registered');
        }

        return $this->tables[$tableId];
    }
}
