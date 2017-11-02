<?php


namespace CrossKnowledge\DataTableDundle\Tests\DataTable\Renderer;


use CrossKnowledge\DataTableBundle\DataTable\Renderer\TwigRenderer;
use CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column\Column;

class TwigRendererTest extends \PHPUnit_Framework_TestCase
{
    public function testRender()
    {
        $mock = $this->getTableMock([
            'template' => 'example.html.twig'
        ]);

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
            ->will($this->returnValue(array_merge(['has_filter_form'=>false],$options)));

        return $mock;
    }
}
