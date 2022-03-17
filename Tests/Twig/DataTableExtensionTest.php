<?php

namespace CrossKnowledge\DataTableDundle\Tests\Controller;

use CrossKnowledge\DataTableBundle\Twig\DataTableExtension;
use CrossKnowledge\DataTableDundle\Tests\UsesContainerTrait;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use PHPUnit\Framework\TestCase;

require_once __DIR__.'/../UsesContainerTrait.php';

class DataTableExtensionTest extends TestCase
{
    use UsesContainerTrait;

    protected function getFunctionByName(\Twig_Extension $extension, $name)
    {
        foreach ($extension->getFunctions() as $function) {
            if ($function->getName()==$name) {
                return $function;
            }
        }
    }

    public function testRenderTableCallsTwigRenderer()
    {
        $tableMock = $this->getDataTableMock();
        $rendererMock = $this->getMockBuilder('CrossKnowledge\DataTableBundle\DataTable\Renderer\TwigRenderer')
            ->disableOriginalConstructor()
            ->getMock();

        $twigExtension = new DataTableExtension($rendererMock);
        $function = $this->getFunctionByName($twigExtension, 'render_table');
        $this->assertNotNull($function, 'Function "render_table" is not registered in twig_extension');

        $rendererMock->expects($this->once())->method('render');
        call_user_func_array($function->getCallable(), [$tableMock]);
    }
}