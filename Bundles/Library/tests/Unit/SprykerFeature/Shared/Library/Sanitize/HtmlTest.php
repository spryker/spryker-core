<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Shared\Library\Sanitize;

use SprykerFeature\Zed\Library\Sanitize\Html;

/**
 * @group Sanitize
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
            [new \stdClass(), '(object)stdClass'],
        ];
    }

    /**
     * @dataProvider dataProvider
     *
     * @param $string
     * @param $expected
     *
     * @return void
     */
    public function testEscape($string, $expected)
    {
        $this->assertSame($expected, Html::escape($string));
    }

}
