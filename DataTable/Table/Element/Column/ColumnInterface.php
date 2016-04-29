<?php


namespace CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column;


use Symfony\Component\OptionsResolver\OptionsResolver;

interface ColumnInterface
{
    /**
     * Sets the unique column identifier  within a given list
     *
     * @param string $identifier
     *
     * @return ColumnInterface mixed
     */
    public function setIdentifier($identifier);
    /**
     * Builds options resolver
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver);
    /**
     * Gets the key/value array that will be serialized into JSON to define the column for the Javascript API
     *
     * @return string[]
     */
    public function getClientSideDefinition();
    /**
     * @return string the content of the cell
     */
    public function formatCell($value, array $rowData, $context);
    /**
     * Sets the column options
     *
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * @return string[] key/value of specified options
     */
    public function getOptions();
}
