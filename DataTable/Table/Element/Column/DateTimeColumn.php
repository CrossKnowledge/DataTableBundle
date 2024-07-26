<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeColumn extends Column
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('input_format', 'Y-m-d H:i:s');
        $resolver->setRequired('output_format');
    }

    public function formatCell($value, array $row, $context): string
    {
        if (empty($value)) {
            return '';
        }
        $inputFormat = $this->getOptions()['input_format'];
        if ($inputFormat == 'object') {
            $dateTime = $value;
        } else {
            $dateTime = \DateTime::createFromFormat($inputFormat, $value);
        }

        return $dateTime->format($this->getOptions()['output_format']);
    }
}
