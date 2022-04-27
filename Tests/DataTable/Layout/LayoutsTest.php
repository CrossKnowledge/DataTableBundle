<?php
namespace CrossKnowledge\DataTableDundle\Tests\DataTable\Layout;

use CrossKnowledge\DataTableBundle\DataTable\Table\Layout\Bootstrap;
use CrossKnowledge\DataTableBundle\DataTable\Table\Layout\CustomLayout;
use CrossKnowledge\DataTableBundle\DataTable\Table\Layout\DataTableLayoutInterface;

class ColumnBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider layoutsProvider
     */
    public function testLayouts($layoutKey, DataTableLayoutInterface $layoutObject)
    {
        $this->assertEquals($layoutKey, $layoutObject->getName());
        $this->assertNotEmpty($layoutObject->getDomDefinition());
    }

    public function layoutsProvider()
    {
        return [
            ['boostrap-datatable-layout', new Bootstrap()],
            ['k3', new CustomLayout('k3', 'custom3')],
        ];
    }
}