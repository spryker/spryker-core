<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Library\Generator;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Library\Generator\StringGenerator;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Library
 * @group Generator
 * @group StringGeneratorTest
 */
class StringGeneratorTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testCreateInstance()
    {
        $generator = new StringGenerator();
        $this->assertInstanceOf(StringGenerator::class, $generator);
    }

    /**
     * @return void
     */
    public function testSetLengthMustReturnFluentInterface()
    {
        $generator = new StringGenerator();
        $this->assertInstanceOf(StringGenerator::class, $generator->setLength(10));
    }

    /**
     * @return void
     */
    public function testGenerateRandomStringWithoutModifyLength()
    {
        $generator = new StringGenerator();

        $string = $generator->generateRandomString();

        $this->assertInternalType('string', $string);
    }

    /**
     * @dataProvider randomStringProvider
     *
     * @param int $length
     *
     * @return void
     */
    public function testGenerateRandomString($length)
    {
        $generator = new StringGenerator();
        $generator->setLength($length);

        $string = $generator->generateRandomString();

        $this->assertSame($length, strlen($string));
    }

    /**
     * @return array
     */
    public function randomStringProvider()
    {
        return [
            [10],
            [9],
            [8],
            [7],
            [6],
            [5],
        ];
    }

}
