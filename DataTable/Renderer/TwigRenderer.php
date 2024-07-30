<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Renderer;

use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;
use Twig\Environment;

class TwigRenderer implements RendererInterface
{
    /** @var Environment */
    protected $twig;

    /**
     * TwigRenderer constructor
     *
     * @param Environment $env
     */
    public function __construct(Environment $env)
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
        if ($tableOptions['has_filter_form']) {
            $data['filterForm'] = $table->getFilterForm()->createView();
        }

        return $this->twig->load($tableOptions['template'])->render($data);
    }
}
