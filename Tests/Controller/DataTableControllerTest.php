<?php

namespace CrossKnowledge\DataTableDundle\Tests\Controller;

require_once __DIR__ . '/../UsesContainerTrait.php';

use CrossKnowledge\DataTableBundle\Controller\DataTableController;
use CrossKnowledge\DataTableBundle\DataTable\DataTableRegistry;
use CrossKnowledge\DataTableBundle\DataTable\Renderer\JsonRenderer;
use CrossKnowledge\DataTableDundle\Tests\UsesContainerTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

class DataTableControllerTest extends TestCase
{
    use UsesContainerTrait;

    /**
     * Test jsonAction from DataTableController.
     */
    public function testJsonAction()
    {
        $tableMock = $this->getDataTableMock();
        $registryMock = $this->getMockBuilder(DataTableRegistry::class)
            ->disableOriginalConstructor()
            ->getMock();

        $registryMock->expects($this->once())
            ->method('retrieveByTableId')
            ->will($this->returnValue($tableMock));

        $rendererMock = $this->getMockBuilder(JsonRenderer::class)
            ->getMock();

        $rendererMock->expects($this->once())
            ->method('renderJsonResponse')
            ->with($tableMock);

        $request = new Request([], [], ['tableid' => 'testtable_id']);

        $controller = new DataTableController($registryMock, $rendererMock);

        $controller->jsonAction($request);
    }
}
