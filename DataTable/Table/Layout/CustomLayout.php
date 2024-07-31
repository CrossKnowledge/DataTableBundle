<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Table\Layout;

class CustomLayout implements DataTableLayoutInterface
{
    protected $name, $dom;

    public function __construct($name, $dom)
    {
        $this->name = $name;
        $this->dom = $dom;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function getDomDefinition(): string
    {
        return $this->dom;
    }
}
