<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Money\Converter;

use PHPUnit_Framework_TestCase;
use Spryker\Shared\Money\Converter\IntegerToDecimalConverter;
use Spryker\Shared\Money\Exception\InvalidConverterArgumentException;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Money
 * @group Converter
 * @group IntegerToDecimalConverterTest
 */
class IntegerToDecimalConverterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider convertValues
     *
     * @param int $input
     * @param float $expected
     *
     * @return void
     */
    public function testConvertShouldReturnString($input, $expected)
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
    public function testConvertShouldThrowExceptionIfValueNotInt()
    {
        $this->expectException(InvalidConverterArgumentException::class);

        $integerToDecimalConverter = new IntegerToDecimalConverter();
        $integerToDecimalConverter->convert(0.01);
    }

}
