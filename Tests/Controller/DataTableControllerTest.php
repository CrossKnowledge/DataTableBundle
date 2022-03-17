<?php
namespace CrossKnowledge\DataTableDundle\Tests\Controller;

require_once __DIR__.'/../UsesContainerTrait.php';

use CrossKnowledge\DataTableBundle\Controller\DataTableController;
use CrossKnowledge\DataTableDundle\Tests\UsesContainerTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

class DataTableControllerTest extends TestCase
{
    use UsesContainerTrait;

    public function testJsonAction()
    {
        $tableMock = $this->getDataTableMock();
        $registryMock = $this->getMockBuilder('CrossKnowledge\DataTableBundle\DataTable\DataTableRegistry')
                             ->disableOriginalConstructor()
                             ->getMock();

        $registryMock->expects($this->once())
                     ->method('retrieveByTableId')
                     ->will($this->returnValue($tableMock));

        $rendererMock = $this->getMockBuilder('CrossKnowledge\DataTableBundle\DataTable\Renderer\JsonRenderer')
                             ->getMock();

        $rendererMock->expects($this->once())
                         ->method('renderJsonResponse')
                         ->with($tableMock);

        $request = new Request([], [], ['tableid' => 'testtable_id']);


        $controller = new DataTableController();
        $container  = new ContainerBuilder();
        $container->set('crossknowledge_datatable.registry', $registryMock);
        $container->set('crossknowledge_datatable.json_renderer', $rendererMock);
        $this->compileContainer($container);

        $controller->setContainer($container);
        $controller->jsonAction($request);
    }
}
