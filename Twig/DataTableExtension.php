<?php

namespace CrossKnowledge\DataTableBundle\Twig;

use CrossKnowledge\DataTableBundle\DataTable\Renderer\TwigRenderer;
use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class DataTableExtension extends AbstractExtension
{
    protected TwigRenderer $renderer;

    public function __construct(TwigRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('render_table', [$this, 'renderTable'], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function getName()
    {
        return 'crossKnowledge.datatable.twig_extension';
    }

    public function renderTable(AbstractTable $table)
    {
        return $this->renderer->render($table);
    }
}
