<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Renderer;

use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;

class TwigRenderer implements RendererInterface
{
    protected $twig;

    public function __construct(\Twig_Environment $env)
    {
        $this->twig = $env;
    }

    public function render(AbstractTable $table)
    {
        $tableOptions = $table->getOptions();
        $data = [
            'columns' => $table->getClientSideColumns(),
            'datatable' => $table,
        ];
        if ($tableOptions['has_filter_form'])
        {
            $data['filterForm'] = $table->getFilterForm()->createView();
        }
        return $this->twig->loadTemplate($tableOptions['template'])->render($data);
    }
}
