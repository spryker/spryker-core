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
 *
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
     * @deprecated Use PHPUnit's additional internal methods methods instead of the deprecated `assertInternalType()`.
     *
     * @var string
     */
    public const EXPECTED_INTERNAL_TYPE = 'int';

    /**
     * @dataProvider getTestData()
     *
     * @param mixed $input
     * @param int $expected
     *
     * @return void
     */
    public function testCastInt($input, int $expected): void
    {
        $controller = new MockController();

        $result = $controller->indexAction($input);

        $this->assertSame($expected, $result);
        $this->assertIsInt($result);
    }

    /**
     * @dataProvider getInvalidTestData()
     *
     * @param mixed $input
     *
     * @return void
     */
    public function testCastIntThrowsExceptionForInvalidData($input): void
    {
        //Arrange
        $controller = new MockController();

        //Assert
        $this->expectException(Exception::class);

        //Act
        $controller->indexAction($input);
    }

    /**
     * @return array
     */
    public function getTestData(): array
    {
        return [
            ['1', 1],
            [1, 1],
            [1.5, 1],
        ];
    }

    /**
     * @return array
     */
    public function getInvalidTestData(): array
    {
        return [
            [true],
            ['string'],
            [[]],
        ];
    }
}
