<?php
namespace CrossKnowledge\DataTableDundle\Tests\DataTable\Column;


use CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column\Column;
use PHPUnit\Framework\TestCase;

class ColumnTest extends TestCase
{
    public function testUndefinedOptionThrowsException()
    {
        $this->setExpectedException('Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException');
        $column = new Column("test", ['not_defined' => true]);
    }

    public function testFormatCellUsesCallback()
    {
        $column = new Column("test");
        $column->setFormatValueCallback(function($val, $row){
            return $val.'ok';
        });

        $this->assertEquals('testok', $column->formatCell('test', [], 'view'));
        $column->setFormatValueCallback(null);

        $this->assertEquals('test', $column->formatCell('test', [], 'view'), 'callback should have been resetted');
    }

    public function testBasicGetterSetter()
    {
        $column = (new Column("test", ['auto_escape' => false]))
                   ->setFormatValueCallback(function() {
                        return 'test ok';
                    })
                   ->setIdentifier('test');

        $this->assertEquals("test", $column->getOptions()['title']);
        $this->assertEquals("test ok", call_user_func($column->getFormatValueCallback()));
        $this->assertFalse($column->getOptions()['auto_escape'], 'Autoescape has been disabled');

        $column->setOptions(['defaultContent' => 'test']);
        $this->assertArrayHasKey('defaultContent', $column->getOptions());

        $this->assertTrue($column->getOptions()['auto_escape'], 'Default autoescape value must be resetted  by setOptions');
    }
}
