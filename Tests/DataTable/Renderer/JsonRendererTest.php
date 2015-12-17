<?php

namespace CrossKnowledge\DataTableDundle\Tests\DataTable\Renderer;

use CrossKnowledge\DataTableBundle\DataTable\Renderer\JsonRenderer;

class JsonRendererTest extends \PHPUnit_Framework_TestCase
{

    protected function getTableMock($unfilteredCount, $filteredCount)
    {
        $mock = $this->getMockBuilder('CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable')
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('buildView')
            ->will($this->returnValue([
                'data' => [
                    ['col1' => 'mytestdata']
                ],
                'unfilteredRowsCount' => $unfilteredCount,
                'filteredRowsCount' => $filteredCount
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