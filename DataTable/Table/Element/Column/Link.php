<?php

namespace CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Link extends Column
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setRequired('LinkTextField');
        $resolver->setDefault('AltTextField', function (Options $options) {
            return $options['LinkTextField'];
        });
        $resolver->setDefault('UrlField', function (Options $options) {
            return $options['LinkTextField'];
        });
        $resolver->setDefault('UrlCallback', function($value, $row) {
            return $row[$this->options['UrlField']];
        });
        $resolver->setAllowedTypes('UrlCallback', ['\Closure']);
    }
    /**
     * Build a link
     */
    public function formatCell($value, array $rowData, $context)
    {
        $value = parent::formatCell($value, $rowData, $context);
        $url = call_user_func_array($this->options['UrlCallback'], [$value, $rowData]);
        return '<a href="'.$url.'" alt="'.htmlentities($rowData[$this->options['LinkTextField']], ENT_COMPAT | ENT_HTML401, 'UTF-8').'">'.htmlentities($rowData[$this->options['LinkTextField']], ENT_COMPAT | ENT_HTML401, 'UTF-8').'</a>';
    }
}
