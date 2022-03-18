<?php

namespace CrossKnowledge\DataTableDundle\Tests;

use CrossKnowledge\DataTableBundle\CrossKnowledgeDataTableBundle;
use CrossKnowledge\DataTableBundle\DependencyInjection\Compiler\DatatablePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use PHPUnit\Framework\TestCase;

class CrossKnowledgeDataTableBundleTest extends TestCase
{
    /**
     * Test if the DataTable compiler pass is successfully registered.
     */
    public function testPassIsRegistered()
    {
        $container = new ContainerBuilder();
        (new CrossKnowledgeDataTableBundle())->build($container);
        $compilerPasses = $container->getCompilerPassConfig()->getBeforeOptimizationPasses();

        $this->assertNotEmpty(array_filter($compilerPasses, function($compilerPass) {
            return $compilerPass instanceof DatatablePass;
        }));
    }
}
