<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Renderer;

use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;
use Twig\Environment;

class TwigRenderer implements RendererInterface
{
    /** @var Environment */
    protected Environment $twigEnvironment;

    /**
     * TwigRenderer constructor
     *
     * @param Environment $twigEnvironment
     */
    public function __construct(Environment $twigEnvironment)
    {
        $this->twigEnvironment = $twigEnvironment;
    }

    public function render(AbstractTable $table): string
    {
        $tableOptions = $table->getOptions();
        $data = [
            'columns' => $table->getClientSideColumns(),
            'datatable' => $table,
        ];
        if ($tableOptions['has_filter_form']) {
            $data['filterForm'] = $table->getFilterForm()->createView();
        }

        return $this->twigEnvironment->load($tableOptions['template'])->render($data);
    }
}
