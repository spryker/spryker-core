<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Business;

use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Merchant\Business\MerchantFacade;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Merchant
 * @group Business
 * @group Facade
 * @group MerchantFacadeTest
 * Add your own group annotations below this line
 */
class MerchantFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Merchant\MerchantBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCreateMerchant(): void
    {
        $merchantTransfer = (new MerchantTransfer())
            ->setMerchantKey('spryker-test-1')
            ->setName('Spryker Merchant');

        (new MerchantFacade())->createMerchant($merchantTransfer);

        $this->assertNotNull($merchantTransfer->getIdMerchant());
    }

    /**
     * @return void
     */
    public function testCreateMerchantWithEmptyKeyGeneratesKey(): void
    {
        $merchantTransfer = (new MerchantTransfer())
            ->setName('Spryker Merchant');

        (new MerchantFacade())->createMerchant($merchantTransfer);

        $this->assertNotNull($merchantTransfer->getMerchantKey());
    }

    /**
     * @return void
     */
    public function testCreateMerchantWithEmptyNameThrowsException(): void
    {
        $merchantTransfer = (new MerchantTransfer())
            ->setMerchantKey('spryker-test-1');

        $this->expectException(RequiredTransferPropertyException::class);

        (new MerchantFacade())->createMerchant($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testCreateMerchantWithNotUniqueName(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();
        $newMerchantTransfer = (new MerchantTransfer())
            ->setMerchantKey($merchantTransfer->getMerchantKey() . '-1')
            ->setName($merchantTransfer->getName());

        (new MerchantFacade())->createMerchant($newMerchantTransfer);
        $this->assertNotNull($newMerchantTransfer->getIdMerchant());
    }

    /**
     * @return void
     */
    public function testCreateMerchantWithNotUniqueKeyThrowsException(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();

        $newMerchantTransfer = (new MerchantTransfer())
            ->setMerchantKey($merchantTransfer->getMerchantKey())
            ->setName($merchantTransfer->getName());

        $this->expectException(Exception::class);

        (new MerchantFacade())->createMerchant($newMerchantTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateMerchant(): void
    {
        $merchantTransfer = $this->tester->haveMerchant([
            'one-key',
            'One Company',
        ]);

        $expectedIdMerchant = $merchantTransfer->getIdMerchant();
        $merchantTransfer
            ->setMerchantKey('second-key')
            ->setName('Second Company');

        $updatedMerchant = (new MerchantFacade())->updateMerchant($merchantTransfer);

        $this->assertSame($expectedIdMerchant, $updatedMerchant->getIdMerchant());
        $this->assertEquals('second-key', $updatedMerchant->getMerchantKey());
        $this->assertEquals('Second Company', $updatedMerchant->getName());
    }

    /**
     * @return void
     */
    public function testUpdateMerchantWithoutDataThrowsException(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantTransfer
            ->setMerchantKey(null)
            ->setName(null);

        $this->expectException(RequiredTransferPropertyException::class);

        (new MerchantFacade())->updateMerchant($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateMerchantWithWrongIdThrowsException(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantTransfer
            ->setIdMerchant($merchantTransfer->getIdMerchant() + 1);

        $this->expectException(Exception::class);

        (new MerchantFacade())->updateMerchant($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testGetMerchantById(): void
    {
        $expectedMerchant = $this->tester->haveMerchant();

        $merchantTransfer = (new MerchantTransfer())
            ->setIdMerchant($expectedMerchant->getIdMerchant());

        $actualMerchant = (new MerchantFacade())->getMerchantById($merchantTransfer);

        $this->assertEquals($expectedMerchant, $actualMerchant);
    }

    /**
     * @return void
     */
    public function testDeleteMerchant(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();

        (new MerchantFacade())->deleteMerchant($merchantTransfer);

        $this->tester->assertMerchantNotExists($merchantTransfer->getIdMerchant());
    }

    /**
     * @return void
     */
    public function testDeleteMerchantWithoutThrowsException(): void
    {
        $merchantTransfer = new MerchantTransfer();

        $this->expectException(RequiredTransferPropertyException::class);

        (new MerchantFacade())->deleteMerchant($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testGetMerchantsReturnNotEmptyCollection(): void
    {
        $this->tester->truncateMerchantRelations();

        $this->tester->haveMerchant();
        $this->tester->haveMerchant();

        $merchantTransferCollection = (new MerchantFacade())->getMerchants();
        $this->assertCount(2, $merchantTransferCollection->getMerchants());
    }
}
