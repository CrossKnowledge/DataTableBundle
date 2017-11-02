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
        $template = $this->twig->loadTemplate($table->getOptions()['template']);
        $data = [
            'columns' => $table->getClientSideColumns(),
            'datatable' => $table,
        ];
        if ($table->getOptions()['has_filter_form'])
        {
            $data['filterForm'] = $table->getFilterForm()->createView();
        }
        return $template->render($data);
    }
}
