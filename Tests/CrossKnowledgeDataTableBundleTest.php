<?php


namespace CrossKnowledge\DataTableDundle\Tests;


use CrossKnowledge\DataTableBundle\CrossKnowledgeDataTableBundle;
use CrossKnowledge\DataTableBundle\DependencyInjection\Compiler\DatatablePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CrossKnowledgeDataTableBundleTest extends \PHPUnit_Framework_TestCase
{
    public function testPassIsRegistered()
    {
        $container = new ContainerBuilder();
        $bundle = new CrossKnowledgeDataTableBundle();
        $bundle->build($container);

        $passes = array_values(
            array_filter(
                $container->getCompilerPassConfig()->getPasses(),
                function ($pass) {
                    return $pass instanceof DatatablePass;
                }
            )
        );

        $this->assertInstanceOf(DatatablePass::class, $passes[0]);
    }
}
