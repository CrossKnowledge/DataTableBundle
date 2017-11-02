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
        return $template->render(
            $table->buildView(false)
        );
    }
}
