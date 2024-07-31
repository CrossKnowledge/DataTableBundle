<?php

namespace CrossKnowledge\DataTableDundle\Tests\DataTable\Renderer;

use CrossKnowledge\DataTableBundle\DataTable\Renderer\TwigRenderer;
use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;
use CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column\Column;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Form;
use Twig\Environment;

class TwigRendererTest extends TestCase
{
    public function testRenderWithFilters()
    {
        $mock = $this->getTableMock([
            'template' => 'example.html.twig',
            'has_filter_form' => true,
        ]);

        $mock->expects($this->once())
            ->method('getFilterForm')
            ->will(
                $this->returnValue(
                    $this->getMockBuilder(Form::class)
                        ->disableOriginalConstructor()
                        ->getMock()
                )
            );

        $twig = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $templateMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $twig->expects($this->once())->method('loadTemplate')
            ->will($this->returnValue($templateMock));

        $renderer = new TwigRenderer($twig);
        $renderer->render($mock);
    }

    public function testRenderWithoutFilters()
    {
        $mock = $this->getTableMock([
            'template' => 'example.html.twig',
            'has_filter_form' => false,
        ]);

        $mock->expects($this->never())
            ->method('getFilterForm');

        $twig = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $templateMock = $this->getMockBuilder(Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $twig->expects($this->once())->method('loadTemplate')
            ->will($this->returnValue($templateMock));

        $renderer = new TwigRenderer($twig);
        $renderer->render($mock);
    }

    public function getTableMock(array $options)
    {
        $mock = $this->getMockBuilder(AbstractTable::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('getClientSideColumns')
            ->will($this->returnValue(['col1' => new Column('test col1')]));

        $mock->expects($this->once())
            ->method('getOptions')
            ->will($this->returnValue($options));

        return $mock;
    }
}
