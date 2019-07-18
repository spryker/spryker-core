<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\Communication\Controller;

use Codeception\Test\Unit;
use Exception;
use SprykerTest\Zed\Kernel\Communication\Controller\Fixtures\MockController;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group Communication
 * @group Controller
 * @group AbstractControllerTest
 * Add your own group annotations below this line
 */
class AbstractControllerTest extends Unit
{
    /**
     * @deprecated Please use phpunit's additional internal methods methods instead of the deprecated `assertInternalType()`.
     */
    public const EXPECTED_INTERNAL_TYPE = 'int';

    /**
     * @dataProvider getTestData()
     *
     * @param mixed $input
     * @param int $expected
     * @param bool $isValid
     *
     * @return void
     */
    public function testCastInt($input, $expected, $isValid)
    {
        $controller = new MockController();

        if (!$isValid) {
            $this->expectException(Exception::class);
        }

        $result = $controller->indexAction($input);

        $this->assertSame($expected, $result);
        $this->assertIsInt($result);
    }

    /**
     * @return array
     */
    public function getTestData()
    {
        return [
            ['1', 1, true],
            [1, 1, true],
            [1.5, 1, true],
            [true, 1, false],
            [false, 0, false],
            ['string', 0, false],
            [[], 0, false],
        ];
    }
}
