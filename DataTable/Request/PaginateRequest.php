<?php
namespace CrossKnowledge\DataTableBundle\DataTable\Request;

use \CrossKnowledge\DataTableBundle\DataTable\Table\AbstractTable;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provider a readable format with auto-completiont to represent the POST/GET datas send  by datatable ajax calls.
 */
class PaginateRequest
{
    /**
     * @var int current offset in paging
     */
    public $offset = 0;
    /**
     * @var int current per page limit
     */
    public $limit = 10;
    /**
     * @var Form filter form
     */
    public $customFilter = null;
    /**
     * @var array datatable search parameters
     */
    public $search = [];
    /**
     * @var array datatable order by parameters (something like
    order[0][column]:0
    order[0][dir]:asc
     */
    public $orderBy = null;
    /**
     * @var array datatable columns parameters
     */
    public $columns = [];

    public function __construct($offset, $limit, $search, $customFilter, $columns, $order)
    {
        $this->offset = $offset;
        $this->limit = $limit;
        $this->search = $search;
        $this->customFilter = $customFilter;
        $this->columns = $columns;
        $this->orderBy = $order;
    }

    /**
     * @return true if a specific sort order is set
     */
    public function isOrdered()
    {
        return count($this->orderBy)>0;
    }

    public static function fromHttpRequest(Request $request, AbstractTable $table)
    {
        $table->getFilterForm()->handleRequest($request);

        $numericOrder = $request->get('order', []);
        $colnameOrder = [];

        //Limit: support single column ordering
        if (!empty($numericOrder[0]) && isset($numericOrder[0]['column'])) {
            $colIndex = 0;
            $sortColIndex = $numericOrder[0]['column'];
            foreach ($table->getColumns() as $colid=>$column) {
                if ($sortColIndex == $colIndex) {
                    $colnameOrder[$colid] = $numericOrder[0]['dir'];
                }

                $colIndex++;
            }
        }


        return new static(
            $request->get('start', 0),
            $request->get('length', 10),
            $request->get('search', []),
            $table->getFilterForm(),
            $request->get('columns', []),
            $colnameOrder
        );
    }
}
