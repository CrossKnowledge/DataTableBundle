<?php
namespace CrossKnowledge\DataTableDundle\Tests\DataTable\Request;

use CrossKnowledge\DataTableBundle\DataTable\Request\PaginateRequest;
use CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column\Column;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormRegistry;
use Symfony\Component\HttpFoundation\Request;

class PaginateRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateFromHttpRequest()
    {
        $tableMock = $this->getTableMock();
        $request = new Request([], [
           'order' => [['column' => '1', 'dir' => 'desc']],
           'start'  => 242,
           'length' => 342
        ]);

        $paginateRequest = PaginateRequest::fromHttpRequest($request, $tableMock);
        $this->assertEquals(342, $paginateRequest->limit);
        $this->assertEquals(242, $paginateRequest->offset);
        $this->assertEquals(['secondcolumn' => 'desc'], $paginateRequest->orderBy);
        $this->assertEquals(true, $paginateRequest->isOrdered());
    }

    protected function getTableMock()
    {
        $formMock = $this->getMockBuilder('Symfony\Component\Form\Form')
            ->disableOriginalConstructor()
            ->getMock();

        $tableMock = $this->getMockBuilder('CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable')
            ->disableOriginalConstructor()
            ->getMock();

        $tableMock->expects($this->any())
            ->method('getColumns')
            ->will(
                $this->returnValue(
                    [
                        'firstcolumn' => new Column("firstcolumn"),
                        'secondcolumn' => new Column("secondcolumn"),
                    ]
                )
            );


        $tableMock->expects($this->any())
            ->method('getFilterForm')
            ->will($this->returnValue($formMock));

        return $tableMock;
    }
}