<?php


namespace CrossKnowledge\DataTableDundle\Tests;


use CrossKnowledge\DataTableBundle\CrossKnowledgeDataTableBundle;
use CrossKnowledge\DataTableBundle\DependencyInjection\Compiler\DatatablePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use PHPUnit\Framework\TestCase;

class CrossKnowledgeDataTableBundleTest extends TestCase
{
    public function testPassIsRegistered()
    {
        $container = new ContainerBuilder();
        $bundle = new CrossKnowledgeDataTableBundle();
        $bundle->build($container);

        $this->assertInstanceOf(DatatablePass::class, $container->getCompilerPassConfig()->getBeforeOptimizationPasses()[0]);
    }
}
