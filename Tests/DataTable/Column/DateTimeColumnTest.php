<?php

namespace CrossKnowledge\DataTableDundle\Tests\DataTable\Column;

use CrossKnowledge\DataTableBundle\DataTable\Table\Element\Column\DateTimeColumn;
use PHPUnit\Framework\TestCase;

class DateTimeColumnTest extends TestCase
{
    /**
     * @dataProvider dateFormatProvider
     */
    public function testOptions($input, $expected, $options)
    {
        $column = new DateTimeColumn("test", $options);

        $this->assertEquals($expected, $column->formatCell(
            $input, [], 'view'
        ));
    }

    public function dateFormatProvider()
    {
        return [
            ['1987-01-11 12:00:00', '11/01/87', [
                    'input_format' => 'Y-m-d H:i:s',
                    'output_format' => 'd/m/y',
                ]
            ],
            ['01-11-1987 12:00:00', '1987-01-11 12:00:00', [
                    'input_format' => 'm-d-Y H:i:s',
                    'output_format' => 'Y-m-d H:i:s',
                ]
            ],
            [new \DateTime('1987-01-11 12:00:00'), '1987-01-11 12:00:00', [
                    'input_format' => 'object',
                    'output_format' => 'Y-m-d H:i:s',
                ]
            ],
            [null, '', [
                    'input_format' => 'object',
                    'output_format' => 'Y-m-d H:i:s',
                ]
            ],
        ];
    }
}
