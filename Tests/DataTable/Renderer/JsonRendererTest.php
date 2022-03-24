<?php

namespace CrossKnowledge\DataTableDundle\Tests\DataTable\Renderer;

use CrossKnowledge\DataTableBundle\DataTable\Renderer\JsonRenderer;
use PHPUnit\Framework\TestCase;

class JsonRendererTest extends TestCase
{

    protected function getTableMock($unfilteredCount, $filteredCount)
    {
        $mock = $this->getMockBuilder('CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('getUnfilteredCount')
            ->will($this->returnValue(
                $unfilteredCount
            ));

        $mock->expects($this->once())
            ->method('getFilteredCount')
            ->will($this->returnValue($filteredCount));

        $mock->expects($this->once())
            ->method('getOutputRows')
            ->will($this->returnValue([
                    ['col1' => 'mytestdata']
                ]));

        return $mock;
    }

    public function testRender()
    {
        $mock = $this->getTableMock(42, 43);

        $renderer = new JsonRenderer();
        $response = $renderer->renderJsonResponse($mock);
        $this->assertEquals(200, $response->getStatusCode());

        $content = json_decode($response->getContent());

        $this->assertEquals(42, $content->recordsTotal);
        $this->assertEquals(43, $content->recordsFiltered);
        $this->assertEquals('mytestdata', $content->data[0][0]);
    }

    public function testFilteredRowsCountIsOptional()
    {
        $mock = $this->getTableMock(43, false);
        $content = json_decode((new JsonRenderer())->render($mock));

        $this->assertEquals($content->recordsFiltered, $content->recordsTotal);
        $this->assertEquals(43, $content->recordsFiltered);
    }
}
