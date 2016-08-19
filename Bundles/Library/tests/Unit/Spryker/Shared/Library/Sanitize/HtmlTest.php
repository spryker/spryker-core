<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Library\Sanitize;

use Spryker\Zed\Library\Sanitize\Html;
use stdClass;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Library
 * @group Sanitize
 * @group HtmlTest
 */
class HtmlTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            ['my<nonhtml>?&x', 'my&lt;nonhtml&gt;?&amp;x'],
            [
                ['<foo>', '&nbsp;'],
                ['&lt;foo&gt;', '&amp;nbsp;'],
            ],
            [false, false],
            [new stdClass(), '(object)stdClass'],
        ];
    }

    /**
     * @dataProvider dataProvider
     *
     * @param string $string
     * @param string $expected
     *
     * @return void
     */
    public function testEscape($string, $expected)
    {
        $this->assertSame($expected, Html::escape($string));
    }

}
