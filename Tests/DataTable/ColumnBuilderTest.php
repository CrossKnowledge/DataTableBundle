<?php

namespace CrossKnowledge\DataTableDundle\Tests\DataTable;

use CrossKnowledge\DataTableBundle\DataTable\ColumnBuilder;
use CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column\Column;
use PHPUnit\Framework\TestCase;

class ColumnBuilderTest extends TestCase
{
    public function testBuilder()
    {
        $builder = new ColumnBuilder();

        for ($i=0;$i<3;$i++) {
            $builder->add("testcolumn".$i, new Column("test column ".$i));
        }

        $this->assertCount(3, $builder->getColumns());

        $i = 0;
        foreach ($builder->getColumns() as $colid=>$column) {
            $this->assertEquals('testcolumn'.$i, $colid);
            $i++;
        }
    }
}