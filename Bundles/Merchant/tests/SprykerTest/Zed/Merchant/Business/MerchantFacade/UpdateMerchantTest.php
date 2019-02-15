<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Business\MerchantFacade;

use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use SprykerTest\Zed\Merchant\Business\AbstractMerchantFacadeTest;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Merchant
 * @group Business
 * @group MerchantFacade
 * @group UpdateMerchantTest
 * Add your own group annotations below this line
 */
class UpdateMerchantTest extends AbstractMerchantFacadeTest
{
    protected const MERCHANT_STATUS_WAITING_FOR_APPROVAL = 'waiting-for-approval'; // todo: has to be removed
    protected const MERCHANT_STATUS_APPROVED = 'approved';
    protected const MERCHANT_STATUS_ACTIVE = 'active';
    protected const MERCHANT_STATUS_INACTIVE = 'inactive';

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
        $merchantAddressTransfer = $this->tester->haveMerchantAddress(['fkMerchant' => $expectedIdMerchant]);
        $merchantTransfer
            ->setMerchantKey('second-key')
            ->setName('Second Company')
            ->setAddress($merchantAddressTransfer);

        $merchantResponseTransfer = $this->tester->getFacade()->updateMerchant($merchantTransfer);
        $updatedMerchant = $merchantResponseTransfer->getMerchant();

        $this->assertSame($expectedIdMerchant, $updatedMerchant->getIdMerchant());
        $this->assertEquals('second-key', $updatedMerchant->getMerchantKey());
        $this->assertEquals('Second Company', $updatedMerchant->getName());
    }

    /**
     * @return void
     */
    public function testUpdateMerchantWithoutMerchantKey(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();

        $expectedIdMerchant = $merchantTransfer->getIdMerchant();
        $merchantTransfer->setMerchantKey(null);

        $merchantResponseTransfer = $this->tester->getFacade()->updateMerchant($merchantTransfer);
        $updatedMerchant = $merchantResponseTransfer->getMerchant();

        $this->assertSame($expectedIdMerchant, $updatedMerchant->getIdMerchant());
        $this->assertNotEmpty($updatedMerchant->getMerchantKey());
    }

    /**
     * @dataProvider getCorrectStatusTransitions
     *
     * @param string[] $presetStatuses
     * @param string $correctlyChangedStatus
     *
     * @return void
     */
    public function testUpdateMerchantWithCorrectStatusWorks(array $presetStatuses, string $correctlyChangedStatus): void
    {
        $merchantTransfer = $this->tester->haveMerchant();

        foreach ($presetStatuses as $presetStatus) {
            $merchantTransfer->setStatus($presetStatus);
            $merchantResponseTransfer = $this->tester->getFacade()->updateMerchant($merchantTransfer);
            $merchantTransfer = $merchantResponseTransfer->getMerchant();
        }

        $expectedIdMerchant = $merchantTransfer->getIdMerchant();
        $merchantTransfer
            ->setStatus($correctlyChangedStatus);

        $merchantResponseTransfer = $this->tester->getFacade()->updateMerchant($merchantTransfer);
        $updatedMerchant = $merchantResponseTransfer->getMerchant();

        $this->assertSame($expectedIdMerchant, $updatedMerchant->getIdMerchant());
        $this->assertSame($correctlyChangedStatus, $updatedMerchant->getStatus());
    }

    /**
     * @dataProvider getWrongStatusTransitions
     *
     * @param string[] $presetStatuses
     * @param string $wronglyChangedStatus
     *
     * @return void
     */
    public function testUpdateMerchantWithIncorrectStatusReturnsIsSuccessFalse(array $presetStatuses, string $wronglyChangedStatus): void
    {
        $merchantTransfer = $this->tester->haveMerchant();

        foreach ($presetStatuses as $presetStatus) {
            $merchantTransfer->setStatus($presetStatus);
            $this->tester->getFacade()->updateMerchant($merchantTransfer);
        }

        $merchantTransfer
            ->setStatus($wronglyChangedStatus);

        $merchantResponseTransfer = $this->tester->getFacade()->updateMerchant($merchantTransfer);

        $this->assertFalse($merchantResponseTransfer->getIsSuccess());
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

        $this->tester->getFacade()->updateMerchant($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateMerchantWithWrongIdReturnsIsSuccessFalse(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantTransfer
            ->setIdMerchant($merchantTransfer->getIdMerchant() + 1);

        $merchantResponseTransfer = $this->tester->getFacade()->updateMerchant($merchantTransfer);

        $this->assertFalse($merchantResponseTransfer->getIsSuccess());
    }

    /**
     * @return array
     */
    public function getCorrectStatusTransitions(): array
    {
        return [
            [[], static::MERCHANT_STATUS_APPROVED],
            [[static::MERCHANT_STATUS_APPROVED], static::MERCHANT_STATUS_ACTIVE],
            [[static::MERCHANT_STATUS_APPROVED], static::MERCHANT_STATUS_INACTIVE],
            [[static::MERCHANT_STATUS_APPROVED, static::MERCHANT_STATUS_ACTIVE], static::MERCHANT_STATUS_INACTIVE],
            [[static::MERCHANT_STATUS_APPROVED, static::MERCHANT_STATUS_INACTIVE], static::MERCHANT_STATUS_ACTIVE],
        ];
    }

    /**
     * @return array
     */
    public function getWrongStatusTransitions(): array
    {
        return [
            [[], static::MERCHANT_STATUS_ACTIVE],
            [[], static::MERCHANT_STATUS_INACTIVE],
            [[static::MERCHANT_STATUS_APPROVED], static::MERCHANT_STATUS_WAITING_FOR_APPROVAL],
            [[static::MERCHANT_STATUS_APPROVED, static::MERCHANT_STATUS_ACTIVE], static::MERCHANT_STATUS_WAITING_FOR_APPROVAL],
            [[static::MERCHANT_STATUS_APPROVED, static::MERCHANT_STATUS_INACTIVE], static::MERCHANT_STATUS_WAITING_FOR_APPROVAL],
        ];
    }
}
