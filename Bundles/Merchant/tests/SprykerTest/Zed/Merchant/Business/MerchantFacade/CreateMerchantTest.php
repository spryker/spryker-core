<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Business\MerchantFacade;

use Codeception\Test\Unit;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Merchant
 * @group Business
 * @group MerchantFacade
 * @group CreateMerchantTest
 * Add your own group annotations below this line
 */
class CreateMerchantTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Merchant\MerchantBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateMerchantStatus(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->createMerchantTransfer();

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->createMerchant($merchantTransfer);

        // Assert
        $this->assertEquals($this->tester->createMerchantConfig()->getDefaultMerchantStatus(), $merchantResponseTransfer->getMerchant()->getStatus());
        $this->assertNotNull($merchantResponseTransfer->getMerchant()->getIdMerchant());
        $this->assertNotNull($merchantResponseTransfer->getMerchant()->getMerchantKey());
    }

    /**
     * @return void
     */
    public function testCreateMerchantWithEmptyMerchantKeyStatus(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->createMerchantTransfer();
        $merchantTransfer->setMerchantKey(null);

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->createMerchant($merchantTransfer);

        // Assert
        $this->assertNotNull($merchantResponseTransfer->getMerchant()->getIdMerchant());
        $this->assertNotNull($merchantResponseTransfer->getMerchant()->getMerchantKey());
    }

    /**
     * @return void
     */
    public function testCreateMerchantWithEmptyRequiredFieldsThrowsException(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->createMerchantTransfer();
        $merchantWithEmptyNameTransfer = clone $merchantTransfer;
        $merchantWithEmptyNameTransfer->setName(null);

        $merchantWithEmptyEmailTransfer = clone $merchantTransfer;
        $merchantWithEmptyEmailTransfer->setEmail(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->createMerchant($merchantWithEmptyNameTransfer);
        $this->tester->getFacade()->createMerchant($merchantWithEmptyEmailTransfer);
    }
}
