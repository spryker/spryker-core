<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Business\MerchantFacade;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Merchant
 * @group Business
 * @group MerchantFacade
 * @group FindMerchantDataTest
 * Add your own group annotations below this line
 */
class FindMerchantDataTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Merchant\MerchantBusinessTester
     */
    protected $tester;

    protected const MERCHANT_EMAIL = 'merchant@test.test';

    /**
     * @return void
     */
    public function testFindMerchantByIdWillFindExistingMerchant(): void
    {
        $expectedMerchant = $this->tester->haveMerchantWithAddressCollection();

        $actualMerchant = $this->tester->getFacade()->findMerchantByIdMerchant($expectedMerchant->getIdMerchant());

        $this->assertEquals($expectedMerchant, $actualMerchant);
    }

    /**
     * @return void
     */
    public function testFindMerchantByIdWillNotFindMerchant(): void
    {
        $merchantTransfer = $this->tester->haveMerchantWithAddressCollection();

        $actualMerchant = $this->tester->getFacade()->findMerchantByIdMerchant($merchantTransfer->getIdMerchant() + 1);

        $this->assertNull($actualMerchant);
    }

    /**
     * @return void
     */
    public function testFindMerchantByEmailWillFindExistingMerchant(): void
    {
        $expectedMerchant = $this->tester->haveMerchantWithAddressCollection();

        $actualMerchant = $this->tester->getFacade()->findMerchantByEmail($expectedMerchant->getEmail());

        $this->assertEquals($expectedMerchant, $actualMerchant);
    }

    /**
     * @return void
     */
    public function testFindMerchantByEmailWillNotFindMerchant(): void
    {
        $actualMerchant = $this->tester->getFacade()->findMerchantByEmail(static::MERCHANT_EMAIL);

        $this->assertNull($actualMerchant);
    }

    /**
     * @return void
     */
    public function testFindMerchantAddressByIdMerchantAddressWillFindMerchantAddress(): void
    {
        $expectedMerchantAddressCollectionTransfer = $this->tester->haveMerchantAddressCollection();

        foreach ($expectedMerchantAddressCollectionTransfer->getAddresses() as $expectedMerchantAddressTransfer) {
            $actualMerchantAddressTransfer = $this->tester->getFacade()->findMerchantAddressByIdMerchantAddress(
                $expectedMerchantAddressTransfer->getIdMerchantAddress()
            );

            $this->assertNotEmpty($actualMerchantAddressTransfer->getIdMerchantAddress());
        }
    }

    /**
     * @return void
     */
    public function testFindMerchantAddressByIdMerchantAddressWillNotFindMissingMerchantAddress(): void
    {
        $merchantAddressCollectionTransfer = $this->tester->haveMerchantAddressCollection();

        foreach ($merchantAddressCollectionTransfer->getAddresses() as $merchantAddressTransfer) {
            $actualMerchantAddressTransfer = $this->tester->getFacade()->findMerchantAddressByIdMerchantAddress(
                $merchantAddressTransfer->getIdMerchantAddress() + 1
            );

            $this->assertNull($actualMerchantAddressTransfer);
        }
    }
}
