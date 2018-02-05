<?php

namespace SprykerTest\Service\UtilSanitize;

use Codeception\Test\Unit;
use Spryker\Service\UtilSanitize\Model\ArrayFilter;
use stdClass;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Service
 * @group UtilSanitize
 * @group ArrayFilterTest
 * Add your own group annotations below this line
 */
class ArrayFilterTest extends Unit
{
    /**
     * @return void
     */
    public function testArrayFilterRecursive()
    {
        $arrayFilter = $this->createArrayFilterModel();

        $array = [
            'emptyArray' => [],
            'false' => false,
            'true' => true,
            'zero' => 0,
            'stringZero' => '0',
            'emptyString' => '',
            'someObject' => new stdClass(),
            'nested' => [
                'foo' => [
                    'bar' => [
                        'emptyString' => '',
                        'null' => null,
                        'string' => 'String',
                    ],
                ],
            ],
        ];
        $result = $arrayFilter->arrayFilterRecursive($array);

        $expected = [
            'true' => $array['true'],
            'someObject' => $array['someObject'],
            'nested' => [
                'foo' => [
                    'bar' => [
                        'string' => 'String',
                    ],
                ],
            ],
        ];
        $this->assertSame($expected, $result);
    }

    /**
     * @return \Spryker\Service\UtilSanitize\Model\ArrayFilter
     */
    protected function createArrayFilterModel()
    {
        return new ArrayFilter();
    }
}
