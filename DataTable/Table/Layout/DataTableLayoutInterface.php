<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Table\Layout;

interface DataTableLayoutInterface
{
    public function getName(): string;

    public function getDomDefinition(): string;
}
