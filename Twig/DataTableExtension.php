<?php

namespace CrossKnowledge\DataTableBundle\Twig;

use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;
use Symfony\Component\DependencyInjection\Container;

class DataTableExtension extends \Twig_Extension
{
    /**
     * @var Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('render_table', array($this, 'renderTable'), array(
                'is_safe' => array('html'),
            )),
        );
    }

    public function getName()
    {
        return 'crossKnowledge.datatable.twig_extension';
    }

    public function renderTable(AbstractTable $table)
    {
        return $this->container->get('crossknowledge_datatable.twig_renderer')->render($table);
    }
}
