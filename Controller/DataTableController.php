<?php


namespace CrossKnowledge\DataTableBundle\Controller;

use CrossKnowledge\DataTableBundle\DataTable\Renderer\JsonRenderer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DataTableController extends Controller
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