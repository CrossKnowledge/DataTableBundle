<?php

namespace CrossKnowledge\DataTableBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DatatablePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $registry = $container->getDefinition('crossknowledge_datatable.registry');

        $tFound = [];

        foreach ($container->findTaggedServiceIds('crossknowledge.datatable') as $id => $attributesInfos) {
            foreach ($attributesInfos as $attributeInfos) {
                $tFound[$attributeInfos['table_id']] = new Reference($id);
                $container->getDefinition($id)->addMethodCall('setTableId', [$attributeInfos['table_id']]);
            }
        }

        $registry->setArguments([$tFound]);
    }
}
