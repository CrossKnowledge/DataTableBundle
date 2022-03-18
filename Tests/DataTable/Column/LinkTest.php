<?php
namespace CrossKnowledge\DataTableDundle\Tests\DataTable\Column;

use CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column\Link;
use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{
    /**
     * @dataProvider linkTestProvider
     */
    public function testOptions($expectedOut, $options)
    {
        $column = new Link("test", array_merge(
            $options, ['LinkTextField' => 'label_field']));

        //Link should work with no value anyway
        $this->assertEquals($expectedOut, $column->formatCell(
            '', [
                'id_field' => '12',
                'label_field' => 'image.png'
            ], 'view'
        ));
    }

    public function linkTestProvider()
    {
        return [
            ['<a href="/12" alt="image.png">image.png</a>', [
                'UrlCallback' => function($value, $row) {
                        return '/'.$row['id_field'];
                    }
                ]
            ],
            ['<a href="image.png" alt="image.png">image.png</a>', [
                    'UrlField' => 'label_field'
                ]
            ],
            ['<a href="12" alt="image.png">image.png</a>', [
                    'UrlField' => 'id_field'
                ]
            ],
        ];
    }
}
