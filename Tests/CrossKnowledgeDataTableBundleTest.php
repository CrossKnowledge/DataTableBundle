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

        $this->assertInstanceOf(DatatablePass::class, $container->getCompilerPassConfig()->getBeforeOptimizationPasses()[0]);
    }
}