<?php

namespace CrossKnowledge\DataTableBundle\Controller;

use CrossKnowledge\DataTableBundle\DataTable\DataTableRegistry;
use CrossKnowledge\DataTableBundle\DataTable\Renderer\JsonRenderer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DataTableController
{
    /** @var DataTableRegistry */
    private $registry;
    /** @var JsonRenderer */
    private JsonRenderer $jsonRenderer;

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function jsonAction(Request $request): JsonResponse
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

    public function setContainer(ContainerBuilder $container)
    {
        $this->setRegistry($container->get('crossknowledge_datatable.registry'));
        $this->setJsonRenderer($container->get('crossknowledge_datatable.json_renderer'));
    }
}
