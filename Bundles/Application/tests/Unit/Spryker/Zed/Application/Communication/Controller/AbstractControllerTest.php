<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Application\Communication\Controller;

use Unit\Spryker\Zed\Application\Communication\Controller\Fixtures\MockController;

/**
 * @group Spryker
 * @group Zed
 * @group Application
 * @group Communication
 */
class AbstractControllerTest extends \PHPUnit_Framework_TestCase
{

    const EXPECTED_INTERNAL_TYPE = 'int';

    /**
     * @dataProvider getTestData()
     *
     * @param mixed $input
     * @param int $expected
     * 
     * @return void
     */
    public function testCastInt($input, $expected)
    {
        $controller = new MockController();

        $result = $controller->indexAction($input);

        $this->assertSame($expected, $result);
        $this->assertInternalType(self::EXPECTED_INTERNAL_TYPE, $result);
    }

    /**
     * @return array
     */
    public function getTestData()
    {
        return [
            [1, 1],
            [1.5, 1],
            [true, 1],
            [false, 0],
            ['string', 0],
            [[], 0],
        ];
    }

}
