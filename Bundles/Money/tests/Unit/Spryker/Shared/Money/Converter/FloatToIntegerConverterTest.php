<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Money\Converter;

use Spryker\Shared\Money\Converter\DecimalToCentConverter;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Money
 * @group Converter
 * @group FloatToIntegerConverter
 */
class FloatToIntegerConverterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider convertValues
     *
     * @param float $input
     * @param int $expected
     *
     * @return void
     */
    public function testConvertShouldReturnInteger($input, $expected)
    {
        $decimalToCentConverter = new DecimalToCentConverter();

        $this->assertSame($expected, $decimalToCentConverter->convert($input));
    }

    /**
     * @return array
     */
    public function convertValues()
    {
        return [
            [10.00, 1000],
            [1.00, 100],
            [0.10, 10],
            [0.01, 1],
        ];
    }

}
