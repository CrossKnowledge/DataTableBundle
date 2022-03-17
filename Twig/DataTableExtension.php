<?php

namespace CrossKnowledge\DataTableBundle\Twig;

use CrossKnowledge\DataTableBundle\DataTable\Renderer\TwigRenderer;
use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;

class DataTableExtension extends \Twig_Extension
{
    /**
     * @var TwigRenderer
     */
    protected $renderer;

    public function __construct(TwigRenderer $renderer)
    {
        $this->renderer = $renderer;
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
        return 'crossknowledge.datatable.twig_extension';
    }

    public function renderTable(AbstractTable $table)
    {
        return $this->renderer->render($table);
    }
}
