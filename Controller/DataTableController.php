<?php

namespace CrossKnowledge\DataTableBundle\Controller;

use CrossKnowledge\DataTableBundle\DataTable\DataTableRegistry;
use CrossKnowledge\DataTableBundle\DataTable\Renderer\JsonRenderer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DataTableController
{
    /** @var DataTableRegistry */
    private $registry;
    /** @var JsonRenderer */
    private $jsonRenderer;

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

    public function setRegistry(DataTableRegistry $registry): void
    {
        $this->registry = $registry;
    }

    public function setJsonRenderer(JsonRenderer $jsonRenderer): void
    {
        $this->jsonRenderer = $jsonRenderer;
    }
}
