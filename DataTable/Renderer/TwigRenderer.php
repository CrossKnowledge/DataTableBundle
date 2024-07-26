<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Renderer;

use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class TwigRenderer implements RendererInterface
{
    protected Environment $env;

    /**
     * TwigRenderer constructor
     *
     * @param Environment $env
     */
    public function __construct(Environment $env)
    {
        $this->env = $env;
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function render(AbstractTable $table): string
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
        return $this->env->load($tableOptions['template'])->render($data);
    }
}
