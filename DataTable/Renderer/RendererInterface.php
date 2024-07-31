<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Renderer;

use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;

interface RendererInterface
{
    /**
     * @param DataTable $table
     * @return string html rendered
     */
    public function render(AbstractTable $table): string;
}
