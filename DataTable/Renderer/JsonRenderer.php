<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Renderer;

use CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;
use Symfony\Component\HttpFoundation\JsonResponse;

class JsonRenderer implements RendererInterface
{
    public function render(AbstractTable $table)
    {
        $jsonVars = [];
        $jsonVars['recordsTotal'] = $table->getUnfilteredCount();
        $filterCount = $table->getFilteredCount();
        $jsonVars['recordsFiltered'] = $filterCount!==false ? $filterCount : $jsonVars['recordsTotal'];
        $jsonVars['data'] = array_map(
            function ($item) {
                $t = [];
                foreach ($item as $k => $v) {
                    $t[] = $v;
                }

                return $t;
            },
            $table->getOutputRows()
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
