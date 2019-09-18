<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Money\Converter;

use Codeception\Test\Unit;
use Spryker\Shared\Money\Converter\IntegerToDecimalConverter;
use Spryker\Shared\Money\Exception\InvalidConverterArgumentException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Money
 * @group Converter
 * @group IntegerToDecimalConverterTest
 * Add your own group annotations below this line
 */
class IntegerToDecimalConverterTest extends Unit
{
    /**
     * @dataProvider convertValues
     *
     * @param int $input
     * @param float $expected
     *
     * @return void
     */
    public function testConvertValidInput($input, $expected)
    {
        $integerToDecimalConverter = new IntegerToDecimalConverter();

        $this->assertSame($expected, $integerToDecimalConverter->convert($input));
    }

    /**
     * @return array
     */
    public function convertValues()
    {
        return [
            [1100, 11.00],
            [1010, 10.10],
            [1001, 10.01],
            [1000, 10.00],
            [100, 1.00],
            [10, 0.10],
            [1, 0.01],
            [1799, 17.99],
        ];
    }

    /**
     * @return void
     */
    public function testConvertInvalidInput()
    {
        $this->expectException(InvalidConverterArgumentException::class);

        $integerToDecimalConverter = new IntegerToDecimalConverter();
        $integerToDecimalConverter->convert(0.01);
    }
}
