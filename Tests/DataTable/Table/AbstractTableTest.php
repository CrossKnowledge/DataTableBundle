<?php
namespace CrossKnowledge\DataTableDundle\Tests\DataTable\Table;


use CrossKnowledge\DataTableBundle\DataTable\Formatter\DefaultFormatter;
use CrossKnowledge\DataTableBundle\DataTable\Request\PaginateRequest;
use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;
use CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column\Column;
use CrossKnowledge\DataTableBundle\DataTable\Table\Layout\Bootstrap;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class AbstractTableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getTableMock($cols, $rows)
    {
        $formFactory = $this->getMockBuilder(FormFactory::class)
                             ->disableOriginalConstructor()
                             ->getMock();

        $form = $this->getMockBuilder('Symfony\Component\Form\Form')
                     ->disableOriginalConstructor()
                     ->getMock();

        $authChecker = $this->getMockBuilder(AuthorizationChecker::class)
                            ->disableOriginalConstructor()
                            ->getMock();

        $router = $this->getMockBuilder(Router::class)
                    ->disableOriginalConstructor()
                    ->getMock();

        $formatter = new DefaultFormatter();
        $layout    = new Bootstrap();

        $mock = $this->getMockBuilder(AbstractTable::class)
                     ->setConstructorArgs([
                         $formFactory, $authChecker,
                         $router, $formatter, $layout
                     ])
                     ->setMethods(['getColumns', 'getFilterForm'])
                     ->getMockForAbstractClass();

        $mock->expects($this->any())
            ->method('getDataIterator')
            ->will($this->returnValue($rows));

        $mock->expects($this->any())
             ->method('getColumns')
             ->will($this->returnValue($cols));

        $mock->expects($this->any())
            ->method('getFilterForm')
            ->will($this->returnValue($form));

        $mock->setTableId('testtableId');

        return $mock;
    }
    /**
     * @dataProvider buildViewProvider
     */
    public function testBuildView($cols, $rows, $expectedRowCount)
    {
        $table = $this->getTableMock($cols, $rows);
        $table->setOptions(['has_filter_form' => false]);

        $filterForm = $table->getFilterForm();
        $filterForm->expects($this->never())
                   ->method('createView');

        $viewData = $table->buildView();
        $this->assertEquals($viewData['datatable'], $table);
        $this->assertCount($expectedRowCount, $viewData['data']);
        $this->assertCount(count($cols), $viewData['columns']);
        $this->assertArrayHasKey('filteredRowsCount', $viewData);

        $table = $this->getTableMock($cols, $rows);
        $table->setOptions(['has_filter_form' => true]);

        $filterForm = $table->getFilterForm();
        $filterForm->expects($this->once())
                   ->method('createView');
        $table->buildView();
    }
    /**
     * @dataProvider buildViewProvider
     */
    public function testHasFilterFormOption($cols, $rows, $expectedRowCount)
    {
        $table = $this->getTableMock($cols, $rows);
        $table->setOptions(['has_filter_form' => false]);
        $filterForm = $table->getFilterForm();
        $filterForm->expects($this->never())
                   ->method('createView');

        $table->buildView();

        $table = $this->getTableMock($cols, $rows);
        $table->setOptions(['has_filter_form' => true]);

        $filterForm = $table->getFilterForm();
        $filterForm->expects($this->once())
                   ->method('createView');
        $table->buildView();
    }
    /**
     * @dataProvider buildViewProvider
     */
    public function testGetOutputRows($cols, $rows, $expectedRowCount)
    {
        $table = $this->getTableMock($cols, $rows);
        $this->assertCount($expectedRowCount, $table->getOutputRows());
    }
    /**
     * @dataProvider buildViewProvider
     */
    public function testHandleRequest($cols, $rows, $expectedRowCount)
    {
        $table = $this->getTableMock($cols, $rows);
        $this->assertNull($table->getCurrentRequest());
        $table->handleRequest(new Request());
        $this->assertInstanceOf(PaginateRequest::class, $table->getCurrentRequest());
    }

    public function buildViewProvider()
    {
        return [
          [
              [
                  'firstCol' => new Column('title 1st col'),
                  'secondCol' => new Column('title 2nd col')
              ],
              [
                  ['firstCol' => 'row1column1value', 'secondCol' => 'row2column2value'],
                  ['firstCol' => 'row2column1value', 'secondCol' => 'row2column2value']
              ],
              2
          ]
        ];
    }
}