<?php


namespace CrossKnowledge\DataTableBundle;

use CrossKnowledge\DataTableBundle\DependencyInjection\Compiler\DatatablePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CrossKnowledgeDataTableBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DatatablePass());
    }
}
