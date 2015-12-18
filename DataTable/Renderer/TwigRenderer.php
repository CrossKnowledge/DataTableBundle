<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Renderer;

use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;
use Symfony\Bundle\TwigBundle\TwigEngine;

class TwigRenderer implements RendererInterface
{
    protected $twig;

    public function __construct(TwigEngine $engine)
    {
        $this->twig = $engine;
    }

    public function render(AbstractTable $table)
    {
        return $this->twig->render(
            $table->getOptions()['template'],
            $table->buildView()
        );
    }
}
