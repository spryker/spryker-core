<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedRequest\Business\Model;

use Codeception\TestCase\Test;
use Spryker\Zed\ZedRequest\Business\Exception\ActionPathHasForbiddenSymbolsException;
use Spryker\Zed\ZedRequest\Business\Model\Repeater;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ZedRequest
 * @group Business
 * @group Model
 * @group RepeaterTest
 * Add your own group annotations below this line
 *
 * @property \SprykerTest\Zed\ZedRequest\BusinessTester $tester
 */
class RepeaterTest extends Test
{
    /**
     * @return void
     */
    public function testSetRepeatedDataWritesDataToFiles(): void
    {
        $requestMock = $this->tester->getTransferRequest();
        $httpRequest = $this->tester->getHttpRequest();

        $repeater = new Repeater();
        $repeater->setRepeatData($requestMock, $httpRequest);

        $this->assertFileExists($this->tester->getDefaultFileName());
        $this->assertFileExists($this->tester->getFileNameWithBundleControllerAction());
    }

    /**
     * @return void
     */
    public function testSetRepeatedDataWritesWrongDataToFiles(): void
    {
        // Arrange
        $requestMock = $this->tester->getTransferRequest();
        $httpRequest = $this->tester->getHttpRequestWithForbiddenSymbolsInMvcPartsNames();

        $repeater = new Repeater();

        // Assert
        $this->expectException(ActionPathHasForbiddenSymbolsException::class);

        // Act
        $repeater->setRepeatData($requestMock, $httpRequest);
    }

    /**
     * @return void
     */
    public function testGetRepeatedDataReturnsArray(): void
    {
        $repeater = new Repeater();

        $this->assertIsArray($repeater->getRepeatData());
        $this->assertIsArray($repeater->getRepeatData($this->tester->getBundleControllerAction()));
    }
}
