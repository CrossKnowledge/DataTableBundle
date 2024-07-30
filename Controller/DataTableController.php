<?php

namespace CrossKnowledge\DataTableBundle\Controller;

use CrossKnowledge\DataTableBundle\DataTable\DataTableRegistry;
use CrossKnowledge\DataTableBundle\DataTable\Renderer\JsonRenderer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DataTableController
{
    private DataTableRegistry $registry;
    private JsonRenderer $jsonRenderer;

    public function __construct(DataTableRegistry $registry, JsonRenderer $jsonRenderer)
    {
        $this->registry = $registry;
        $this->jsonRenderer = $jsonRenderer;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function jsonAction(Request $request)
    {
        $dataTable = $this->registry->retrieveByTableId($request->get('tableid'));
        $dataTable->handleRequest($request);

        return $this->jsonRenderer->renderJsonResponse($dataTable);
    }
}
