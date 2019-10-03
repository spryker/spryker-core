<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfile\Business\MerchantProfileFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProfile
 * @group Business
 * @group MerchantProfileFacade
 * @group GetMerchantProfileDataTest
 * Add your own group annotations below this line
 */
class GetMerchantProfileDataTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProfile\MerchantProfileBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindOneMerchantProfile(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $expectedMerchantProfileTransfer = $this->tester->haveMerchantProfile($merchantTransfer);

        // Act
        $merchantProfileCriteriaFilterTransfer = new MerchantProfileCriteriaFilterTransfer();
        $merchantProfileCriteriaFilterTransfer->setIdMerchant($expectedMerchantProfileTransfer->getFkMerchant());
        $merchantProfileTransfer = $this->tester->getFacade()->findOne($merchantProfileCriteriaFilterTransfer);

        // Assert
        $this->assertNotNull($merchantProfileTransfer);
        $this->assertEquals($merchantProfileTransfer->getIdMerchantProfile(), $expectedMerchantProfileTransfer->getIdMerchantProfile());
    }
}
