<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Business\MerchantFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Merchant
 * @group Business
 * @group MerchantFacade
 * @group FindMerchantByIdMerchantTest
 * Add your own group annotations below this line
 */
class FindMerchantByIdMerchantTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Merchant\MerchantBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindMerchantByIdWillFindExistingMerchant(): void
    {
        // Arrange
        $expectedMerchant = $this->tester->haveMerchant();

        $merchantCriteriaFilterTransfer = new MerchantCriteriaFilterTransfer();
        $merchantCriteriaFilterTransfer->setIdMerchant($expectedMerchant->getIdMerchant());

        // Act
        $actualMerchant = $this->tester->getFacade()->findOne($merchantCriteriaFilterTransfer);

        // Assert
        $this->assertEquals($expectedMerchant, $actualMerchant);
    }

    /**
     * @return void
     */
    public function testFindMerchantByIdWillNotFindMerchant(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        $merchantCriteriaFilterTransfer = new MerchantCriteriaFilterTransfer();
        $merchantCriteriaFilterTransfer->setIdMerchant($merchantTransfer->getIdMerchant() + 1);

        // Act
        $actualMerchant = $this->tester->getFacade()->findOne($merchantCriteriaFilterTransfer);

        // Assert
        $this->assertNull($actualMerchant);
    }
}
