<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Renderer;

use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\JsonResponse;

class JsonRenderer implements RendererInterface
{
    public function render(AbstractTable $table)
    {
        $viewVars = $table->buildView();
        $jsonVars = [];
        $jsonVars['recordsTotal'] = $viewVars['unfilteredRowsCount'];
        if ($viewVars['filteredRowsCount']!==false) {
            $jsonVars['recordsFiltered'] = $viewVars['filteredRowsCount'];
        } else {
            $jsonVars['recordsFiltered'] = $viewVars['unfilteredRowsCount'];
        }
        $jsonVars['data'] = array_map(
            function ($item) {
                $t = [];
                foreach ($item as $k => $v) {
                    $t[] = $v;
                }

                return $t;
            },
            $viewVars['data']
        );

        return json_encode($jsonVars);
    }

    public function renderJsonResponse(AbstractTable $table)
    {
        $response = new JsonResponse();
        $response->setContent($this->render($table));

        return $response;
    }
}
