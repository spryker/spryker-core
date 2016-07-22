<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Money\Converter;

use Spryker\Shared\Money\Converter\CentToDecimalConverter;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Money
 * @group Converter
 * @group IntegerToFloatConverter
 */
class IntegerToFloatConverterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider convertValues
     *
     * @param int $input
     * @param float $expected
     *
     * @return void
     */
    public function testConvertShouldReturnInteger($input, $expected)
    {
        $centToDecimalConverter = new CentToDecimalConverter();

        $this->assertSame($expected, $centToDecimalConverter->convert($input));
    }

    /**
     * @return array
     */
    public function convertValues()
    {
        return [
            [1000, 10.00],
            [100, 1.00],
            [10, 0.10],
            [1, 0.01],
        ];
    }

}
