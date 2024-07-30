<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Table;

use CrossKnowledge\DataTableBundle\DataTable\ColumnBuilder;
use CrossKnowledge\DataTableBundle\DataTable\Formatter\FormatterInterface;
use CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column\Column;
use CrossKnowledge\DataTableBundle\DataTable\Table\Layout\Bootstrap;
use CrossKnowledge\DataTableBundle\DataTable\Table\Layout\DataTableLayoutInterface;
use Symfony\Component\Form\Form;
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

    protected Router $router;
    protected PaginateRequest $currentRequest;
    protected FormFactory $formFactory;
    protected Form $filterForm;
    protected AuthorizationChecker $authorizationChecker;
    protected array $columns = [];
    protected array $columnsInitialized = [];
    protected OptionsResolver $optionsResolver;
    protected array $options = [];
    protected DataTableLayoutInterface $layout;
    private string $tableId;
    private FormatterInterface $formatter;

    /**
     * @param FormFactory $formFactory
     * @param AuthorizationCheckerInterface $checker
     * @param Router $router
     * @param FormatterInterface $formatter
     * @param DataTableLayoutInterface|null $layout
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
        $this->initColumnsDefinitions();
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

    public function setOptions(array $options)
    {
        $this->options = $this->optionsResolver->resolve(array_merge($this->options, $options));
    }

    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Build the filter form
     * @param FormBuilder $builder
     * @return FormBuilder
     */
    public function buildFilterForm(FormBuilder $builder)
    {
        return $builder;
    }

    public function getAjaxAdditionalParameters()
    {
        return [];
    }

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

    public function getCurrentRequest()
    {
        return $this->currentRequest;
    }

    /**
     * @return Form
     */
    public function getFilterForm()
    {
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
    public final function getTableId(): string
    {
        return $this->tableId;
    }

    /**
     * @param string $context
     * @return Column[]
     */
    public function getColumns(string $context = self::VIEW_CONTEXT)
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
     * @param $id
     * @return void
     */
    public final function setTableId($id)
    {
        $this->tableId = $id;
    }
}
