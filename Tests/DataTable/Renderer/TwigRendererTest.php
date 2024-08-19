<?php

namespace CrossKnowledge\DataTableDundle\Tests\DataTable\Renderer;

use CrossKnowledge\DataTableBundle\DataTable\Renderer\TwigRenderer;
use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;
use CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column\Column;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Form;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class TwigRendererTest extends TestCase
{
    public function testRenderWithFilters()
    {
        $mock = $this->getTableMock([
            'template' => 'example.html.twig',
            'has_filter_form' => true,
        ]);

        $filterForm = $this->getMockBuilder(Form::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mock->expects($this->once())
            ->method('getFilterForm')
            ->willReturn($filterForm);

        $templates = [
            'example.html.twig' => '{{ table.options.template }} with filter form',
        ];

        $twig = new Environment(new ArrayLoader($templates));

        $renderer = new TwigRenderer($twig);
        $result = $renderer->render($mock);

        $this->assertEquals(' with filter form', $result);
    }

    public function testRenderWithoutFilters()
    {
        $mock = $this->getTableMock([
            'template' => 'example.html.twig',
            'has_filter_form' => false,
        ]);

        $mock->expects($this->never())
            ->method('getFilterForm');

        $templates = [
            'example.html.twig' => '{{ table.options.template }} without filter form',
        ];

        $twig = new Environment(new ArrayLoader($templates));

        $renderer = new TwigRenderer($twig);
        $result = $renderer->render($mock);

        $this->assertEquals(' without filter form', $result);
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
