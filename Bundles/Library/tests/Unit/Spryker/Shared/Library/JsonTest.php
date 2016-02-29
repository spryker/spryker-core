<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Library;
use Spryker\Shared\Library\Json;

/**
 * @group Library
 * @group Json
 */
class JsonTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            [[], '[]'],
            [['foo' => 'bar'], '{"foo":"bar"}'],
            [['foo' => ['bar' => ['baz']]], '{"foo":{"bar":["baz"]}}'],
        ];
    }

    /**
     * @param mixed $given
     * @param string $expected
     *
     * @dataProvider dataProvider
     *
     * @return void
     */
    public function testEncode($given, $expected)
    {
        $this->assertEquals($expected, Json::encode($given));
    }


    /**
     * @param mixed $expected
     * @param string $given
     *
     * @dataProvider dataProvider
     *
     * @return void
     */
    public function testDecode($expected, $given)
    {
        $this->assertEquals($expected, Json::decode($given, true));
    }

}
