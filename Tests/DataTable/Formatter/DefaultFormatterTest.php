<?php

namespace CrossKnowledge\DataTableDundle\Tests\DataTable\Formatter;


use CrossKnowledge\DataTableBundle\DataTable\Formatter\DefaultFormatter;
use CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column\Column;

class DefaultFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testFormatRowAutoEscapeOption()
    {
        $tableMock = $this->getMockBuilder('CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable')
            ->disableOriginalConstructor()
            ->getMock();

        $tableMock->expects($this->once())
            ->method('getColumns')
            ->will($this->returnValue([
                'escapedcolumn' => new Column("escaped", ['auto_escape' => true]),
                'unescapedcolumn' => new Column("not escaped", ['auto_escape' => false])
            ]));

        $formatter = new DefaultFormatter();

        $row = [
          'escapedcolumn' => '<testval',
          'unescapedcolumn' => '<testval',
        ];

        $expected = [
            'escapedcolumn' => '&lt;testval',
            'unescapedcolumn' => '<testval',
        ];

        $this->assertEquals($formatter->formatRow($row, $tableMock), $expected);
    }
}