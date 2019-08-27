<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Business\MerchantFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Zed\Merchant\Business\Exception\MerchantNotFoundException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Merchant
 * @group Business
 * @group MerchantFacade
 * @group GetMerchantDataTest
 * Add your own group annotations below this line
 */
class GetMerchantDataTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Merchant\MerchantBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetMerchantById(): void
    {
        $expectedMerchant = $this->tester->haveMerchantWithAddressCollection();

        $merchantTransfer = (new MerchantTransfer())
            ->setIdMerchant($expectedMerchant->getIdMerchant());

        $actualMerchant = $this->tester->getFacade()->getMerchantById($merchantTransfer);

        $this->assertEquals($expectedMerchant, $actualMerchant);
    }

    /**
     * @return void
     */
    public function testGetMerchantByIdWillThrowMerchantNotFoundException(): void
    {
        $merchantTransfer = $this->tester->haveMerchantWithAddressCollection();

        $merchantTransfer = (new MerchantTransfer())
            ->setIdMerchant($merchantTransfer->getIdMerchant() + 1);

        $this->expectException(MerchantNotFoundException::class);

        $this->tester->getFacade()->getMerchantById($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testGetMerchantsReturnNotEmptyCollection(): void
    {
        $this->tester->truncateMerchantRelations();

        $this->tester->haveMerchantWithAddressCollection();
        $this->tester->haveMerchantWithAddressCollection();

        $merchantCollectionTransfer = $this->tester->getFacade()->getMerchantCollection();
        $this->assertCount(2, $merchantCollectionTransfer->getMerchants());
    }

    /**
     * @return void
     */
    public function testGetApplicableMerchantStatusesWillReturnArray(): void
    {
        $applicableMerchantStatuses = $this->tester->getFacade()->getApplicableMerchantStatuses($this->tester->createMerchantConfig()->getDefaultMerchantStatus());

        $this->assertTrue(is_array($applicableMerchantStatuses));
        $this->assertNotEmpty($applicableMerchantStatuses);
    }

    /**
     * @return void
     */
    public function testGetApplicableMerchantStatusesWillReturnEmptyArrayOnNotFoundCurrentStatus(): void
    {
        $applicableMerchantStatuses = $this->tester->getFacade()->getApplicableMerchantStatuses('random-status');

        $this->assertTrue(is_array($applicableMerchantStatuses));
        $this->assertEmpty($applicableMerchantStatuses);
    }
}
