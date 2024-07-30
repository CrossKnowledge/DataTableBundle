<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Table;

use CrossKnowledge\DataTableBundle\DataTable\ColumnBuilder;
use CrossKnowledge\DataTableBundle\DataTable\Formatter\FormatterInterface;
use CrossKnowledge\DataTableBundle\DataTable\Table\Layout\Bootstrap;
use CrossKnowledge\DataTableBundle\DataTable\Table\Layout\DataTableLayoutInterface;
use CrossKnowledge\DataTableBundle\Table\Element\Column;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Router;
use CrossKnowledge\DataTableBundle\DataTable\Request\PaginateRequest;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

abstract class AbstractTable
{
    const VIEW_CONTEXT = 'view';

    /**
     * @var Router
     */
    protected $router;
    /**
     * @var PaginateRequest
     */
    protected $currentRequest;
    /**
     * @var FormFactory
     */
    protected $formFactory;
    /**
     * @var Form
     */
    protected $filterForm;
    /**
     * @var AuthorizationChecker
     */
    protected $authorizationChecker;

    /**
     * @var Column[]
     */
    protected $columns = [];
    protected $columnsInitialized = [];

    /**
     * @var OptionsResolver
     */
    protected $optionsResolver;

    /**
     * @var array Key value array of options
     */
    protected $options = [];

    /**
     * @var DataTableLayoutInterface
     */
    protected $layout;

    /**
     * @param FormFactory $formFactory
     * @param Router $router
     */
    public function __construct(
        FormFactory $formFactory,
        AuthorizationCheckerInterface $checker,
        Router $router,
        FormatterInterface $formatter,
        DataTableLayoutInterface $layout = null
    ) {
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->formatter = $formatter;
        $this->authorizationChecker = $checker;
        $this->layout = null === $layout ? new Bootstrap() : $layout;
        $this->optionsResolver = new OptionsResolver();
        //$this->initColumnsDefinitions();
        $this->setDefaultOptions($this->optionsResolver);
        $this->configureOptions($this->optionsResolver);
        $this->options = $this->optionsResolver->resolve();
    }

    /**
     *
     * Example implementation
     *
     * public function buildColumns(ColumnBuilder $builder)
     * {
     * $builder->add('Learner.FirstName', new Column('First name title', ['width' => '20%']))
     * ->add('Learner.Name', new Column('Last name'));
     * }
     *
     * @return array key must be the column field name,
     *               value must be an array of options for https://datatables.net/reference/option/columns
     */
    abstract public function buildColumns(ColumnBuilder $builder, $context = self::VIEW_CONTEXT);

    /**
     * Must return a \Traversable a traversable element that must contain for each element an ArrayAccess such as
     *      key(colname) => value(db value)
     *
     * The filter should be used there.
     *
     * Example of the expected return
     * return [
     * 'first_name' => 'John',
     * 'last_name' => 'Doe'
     * ];
     * Example:
     * return new \PropelCollection();
     *
     * @param string $context
     * @return \Traversable
     */
    abstract public function getDataIterator($context = self::VIEW_CONTEXT);

    /**
     * @return int the total number of rows regardless of filters
     */
    abstract public function getUnfilteredCount();

    /**
     * @return int|false if there is no such count
     */
    abstract public function getFilteredCount();

    private final function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'layout' => $this->layout,
            'client_side_filtering' => false,
            'filter_reload_table_on_change' => false,
            'no_init_loading' => false,
            'template' => 'CrossKnowledgeDataTableBundle::default_table.html.twig',
            'data_table_custom_options' => [],
            'has_filter_form' => function () {
                return $this->getFilterForm()->count() > 1;
            },
        ]);
    }

    /**
     * Configure the table options
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    /**
     * @return array
     */
    public function setOptions(array $options)
    {
        $this->options = $this->optionsResolver->resolve(array_merge($this->options, $options));
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Build the filter form
     *
     * @param FormBuilder $builder
     *
     * @return FormBuilder
     */
    public function buildFilterForm(FormBuilder $builder)
    {
        return $builder;
    }

    /**
     * @return string
     */
    public function getAjaxAdditionalParameters()
    {
        return [];
    }

    /**
     * @return string[] should return the content to insert in the rows key(colname) => value(string / html / any)
     */
    public function getOutputRows($context = self::VIEW_CONTEXT)
    {
        $t = [];
        foreach ($this->getDataIterator($context) as $item) {
            $formatted = $this->formatter->formatRow($item, $this, $context);
            $t[] = $formatted;
        }

        return $t;
    }

    /**
     * @see getColumns() same as getColumns but filtered for datatable JS API
     */
    public function getClientSideColumns($context = self::VIEW_CONTEXT)
    {
        $columns = $this->getColumns($context);
        $clientSideCols = [];
        foreach ($columns as $colid => $column) {
            $clientSideCols[$colid] = $column->getClientSideDefinition();
        }

        return $clientSideCols;
    }

    /**
     * @param Request $request
     */
    public function handleRequest(Request $request)
    {
        $this->currentRequest = PaginateRequest::fromHttpRequest($request, $this);
    }

    /**
     * @return PaginateRequest
     */
    public function getCurrentRequest()
    {
        return $this->currentRequest;
    }

    /**
     * @return Form|\Symfony\Component\Form\Form
     */
    public function getFilterForm()
    {
        if (null === $this->filterForm) {
            $this->filterForm = $this->buildFilterForm(
                $this->formFactory->createNamedBuilder($this->getTableId() . '_filter')
                    ->add('dofilter', ButtonType::class)
            )->getForm();
        }

        return $this->filterForm;
    }

    /**
     * Sets the formatter
     *
     * @param FormatterInterface $formatter
     *
     * @return void
     */
    public function setFormatter(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * @return string a table idenfitier that will be used for ajax requests
     */
    public final function getTableId()
    {
        return $this->tableId;
    }

    /**
     * @return \CrossKnowledge\DataTableBundle\Table\Element\Column[]
     */
    public function getColumns($context = self::VIEW_CONTEXT)
    {
        if (!array_key_exists($context, $this->columns)) {
            $this->initColumnsDefinitions($context);
        }

        return $this->columns[$context];
    }

    /**
     * Builds the columns definition
     */
    protected function initColumnsDefinitions($context = self::VIEW_CONTEXT)
    {
        $builder = new ColumnBuilder();

        $this->buildColumns($builder, $context);

        $this->columns[$context] = $builder->getColumns();
        $this->columnsInitialized[$context] = true;
    }

    /**
     * Sets the table identifier
     *
     * @return null
     */
    public final function setTableId($id)
    {
        $this->tableId = $id;
    }
}
