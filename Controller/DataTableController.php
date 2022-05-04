<?php

namespace CrossKnowledge\DataTableBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DataTableController extends AbstractController
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function jsonAction(Request $request)
    {
        $registry = $this->get('crossknowledge_datatable.registry');
        $dataTable = $registry->retrieveByTableId($request->get('tableid'));
        $dataTable->handleRequest($request);

        return $this->get('crossknowledge_datatable.json_renderer')->renderJsonResponse($dataTable);
    }
}
