<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column;

use Closure;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Column implements ColumnInterface
{
    /**
     * Column field info that might be used client side
     *
     * https://datatables.net/reference/option/columns
     *
     * @var array
     */
    public static array $clientSideColumnOptions = [
        'cellType',
        'className',
        'contentPadding',
        'createdCell',
        'data',
        'defaultContent',
        'name',
        'orderable',
        'orderData',
        'orderDataType',
        'render',
        'searchable',
        'title',
        'type',
        'visible',
        'width',
    ];

    /**
     * @var array key/value of options
     */
    protected array $options;

    /**
     * @var OptionsResolver
     */
    protected OptionsResolver $optionsResolver;

    /**
     * @var ?Closure callback that will be used to format this cell values
     */
    protected $formatValueCallback;

    public function __construct($title = '', $options = [])
    {
        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
        $this->setOptions(array_merge($options, ['title' => $title]));
    }

    /**
     * Column unique identifier
     * @param string $identifier
     * @return Column
     */
    public function setIdentifier($identifier): Column
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined('title');
        $resolver->setDefault('auto_escape', true);
        $resolver->setDefined(static::$clientSideColumnOptions);
    }

    /**
     * @param array $options one within static::$clientSideColumnOptions
     */
    public function setOptions(array $options): Column
    {
        $this->options = $this->optionsResolver->resolve($options);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param string $title
     * @return Column
     */
    public function setTitle($title)
    {
        $this->options['title'] = $title;

        return $this;
    }

    /**
     * Format a cell content for this column
     * @param $value
     * @param array $rowData
     * @param $context
     * @return mixed
     */
    public function formatCell($value, array $rowData, $context): string
    {
        if (is_callable($this->formatValueCallback)) {
            return call_user_func_array($this->formatValueCallback, [$value, $rowData, $context]);
        } else {
            return $value;
        }
    }

    /**
     * @return Closure
     */
    public function getFormatValueCallback(): Closure
    {
        return $this->formatValueCallback;
    }

    /**
     * @param Closure|null $callback
     * @return Column
     */
    public function setFormatValueCallback(\Closure $callback = null): Column
    {
        $this->formatValueCallback = $callback;

        return $this;
    }

    /**
     * @return array key/value filtered for client side API
     */
    public function getClientSideDefinition(): array
    {
        $infos = [];

        array_walk($this->options, function ($optval, $optname) use (&$infos) {
            if (in_array($optname, static::$clientSideColumnOptions)) {
                $infos[$optname] = $optval;
            }
        });

        return $infos;
    }
}
