<?php


namespace CrossKnowledge\DataTableDundle\Tests\DataTable\Renderer;


use CrossKnowledge\DataTableBundle\DataTable\Renderer\TwigRenderer;
use CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column\Column;
use PHPUnit\Framework\TestCase;

class TwigRendererTest extends TestCase
{
    public function testRenderWithFilters()
    {
        $mock = $this->getTableMock([
            'template' => 'example.html.twig',
            'has_filter_form' => true
        ]);

        $mock->expects($this->once())
            ->method('getFilterForm')
            ->will($this->returnValue($this->getMockBuilder('Symfony\Component\Form\Form')
                ->disableOriginalConstructor()
                ->getMock()));

        $twig = $this->getMockBuilder('\Twig_Environment')
                    ->disableOriginalConstructor()
                    ->getMock();

        $templateMock = $this->getMockBuilder(\Twig_TemplateInterface::class)
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
            'has_filter_form' => false
        ]);

        $mock->expects($this->never())
            ->method('getFilterForm');

        $twig = $this->getMockBuilder('\Twig_Environment')
                    ->disableOriginalConstructor()
                    ->getMock();

        $templateMock = $this->getMockBuilder(\Twig_TemplateInterface::class)
                             ->disableOriginalConstructor()
                             ->getMock();

        $twig->expects($this->once())->method('loadTemplate')
             ->will($this->returnValue($templateMock));

        $renderer = new TwigRenderer($twig);
        $renderer->render($mock);
    }

    public function getTableMock(array $options)
    {
        $mock = $this->getMockBuilder('CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable')
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
