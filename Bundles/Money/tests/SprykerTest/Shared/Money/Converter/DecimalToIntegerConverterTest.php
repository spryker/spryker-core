<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Money\Converter;

use Codeception\Test\Unit;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverter;
use Spryker\Shared\Money\Exception\InvalidConverterArgumentException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Money
 * @group Converter
 * @group DecimalToIntegerConverterTest
 * Add your own group annotations below this line
 */
class DecimalToIntegerConverterTest extends Unit
{
    /**
     * @dataProvider convertValues
     *
     * @param float $input
     * @param int $expected
     *
     * @return void
     */
    public function testConvertValidInput($input, $expected)
    {
        $decimalToIntegerConverter = new DecimalToIntegerConverter();

        $this->assertSame($expected, $decimalToIntegerConverter->convert($input));
    }

    /**
     * @return array
     */
    public function convertValues()
    {
        return [
            [10.01, 1001],
            [10.10, 1010],
            [10.00, 1000],
            [1.00, 100],
            [0.10, 10],
            [0.01, 1],
        ];
    }

    /**
     * @return void
     */
    public function testConvertInvalidInput()
    {
        $this->expectException(InvalidConverterArgumentException::class);

        $integerToDecimalConverter = new DecimalToIntegerConverter();
        $integerToDecimalConverter->convert(100);
    }
}
