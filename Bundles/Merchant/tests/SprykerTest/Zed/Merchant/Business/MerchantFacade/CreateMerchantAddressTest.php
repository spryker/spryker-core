<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Business\MerchantFacade;

use Generated\Shared\Transfer\MerchantAddressTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Zed\Merchant\Business\AbstractMerchantFacadeTest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Merchant
 * @group Business
 * @group MerchantFacade
 * @group CreateMerchantAddressTest
 * Add your own group annotations below this line
 */
class CreateMerchantAddressTest extends AbstractMerchantFacadeTest
{
    /**
     * @return void
     */
    public function testCreateMerchantAddress(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantAddressTransfer = $this->tester->createMerchantAddressTransfer()
            ->setFkMerchant($merchantTransfer->getIdMerchant());

        $this->tester->getFacade()->createMerchantAddress($merchantAddressTransfer);

        $this->assertNotNull($merchantAddressTransfer->getIdMerchantAddress());
    }

    /**
     * @return void //todo: check and remove
     */
    public function testCreateMerchantAddressWithEmptyKey(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantAddressTransfer = $this->tester->createMerchantAddressTransfer()
            ->setFkMerchant($merchantTransfer->getIdMerchant())
            ->setKey(null);

        $this->tester->getFacade()->createMerchantAddress($merchantAddressTransfer);

        $this->assertNotNull($merchantAddressTransfer->getIdMerchantAddress());
    }

    /**
     * @return void
     */
    public function testCreateMerchantAddressWithExistingKeyThrowsException(): void
    {
        $this->expectException(PropelException::class);

        $merchantTransfer = $this->tester->haveMerchant();
        $merchantAddressTransfer = $this->tester->haveMerchantAddress(['key' => 'Merchant-address-1']);
        $merchantAddressTransfer2 = $this->tester->createMerchantAddressTransfer()
            ->setFkMerchant($merchantTransfer->getIdMerchant())
            ->setKey($merchantAddressTransfer->getKey());

        $this->tester->getFacade()->createMerchantAddress($merchantAddressTransfer);
        $this->tester->getFacade()->createMerchantAddress($merchantAddressTransfer2);
    }

    /**
     * @return void
     */
    public function testCreateMerchantAddressWithoutRequiredDataThrowsException(): void
    {
        $this->expectException(RequiredTransferPropertyException::class);

        $merchantAddressTransfer = (new MerchantAddressTransfer());

        $this->tester->getFacade()->createMerchantAddress($merchantAddressTransfer);
    }
}
