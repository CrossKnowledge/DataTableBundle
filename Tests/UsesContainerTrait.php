<?php

namespace CrossKnowledge\DataTableDundle\Tests;

use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;
use CrossKnowledge\DataTableBundle\DependencyInjection\Compiler\DatatablePass;
use CrossKnowledge\DataTableBundle\DependencyInjection\CrossKnowledgeDataTableExtension;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

trait UsesContainerTrait
{
    protected function compileContainer(ContainerBuilder $container, $loadExt=true)
    {
        if ($loadExt) {
            $extension = new CrossKnowledgeDataTableExtension();
            $container->registerExtension($extension);
            $container->addCompilerPass(new DatatablePass());
            $extension->load([], $container);
        }

        $container->getCompilerPassConfig()->setRemovingPasses([]);
        $container->getCompilerPassConfig()->setOptimizationPasses([]);
        $container->compile();

        return $container;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    public function getDataTableMock()
    {
        return $this->getMockBuilder('CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable')
                    ->disableOriginalConstructor()
                    ->getMock();
    }
}