<?php
namespace CrossKnowledge\DataTableDundle\Tests\DependencyInjection;

use CrossKnowledge\DataTableBundle\DependencyInjection\Compiler\DatatablePass;
use CrossKnowledge\DataTableBundle\DependencyInjection\CrossKnowledgeDataTableExtension;
use CrossKnowledge\DataTableDundle\Tests\UsesContainerTrait;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use PHPUnit\Framework\TestCase;
require_once __DIR__.'/../UsesContainerTrait.php';

class DependencyInjectionTest extends TestCase
{
    use UsesContainerTrait;

    public function testRegistryHydratedByCompilerPass()
    {
        $mock = $this->getMockBuilder('CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable')
                    ->disableOriginalConstructor()
                    ->getMock();

        $definition = new Definition(get_class($mock));
        $definition->addTag('crossknowledge.datatable', ['table_id' => 'testtagged_service_table']);

        $container = new ContainerBuilder();
        $container->set('testtagged_service', $mock);
        $container->setDefinition('testtagged_service', $definition);
        $container = $this->compileContainer($container);

        $registry = $container->get('crossknowledge_datatable.registry');

        $this->assertInstanceOf(
            'CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable',
            $registry->retrieveByTableId('testtagged_service_table')
        );
    }
}