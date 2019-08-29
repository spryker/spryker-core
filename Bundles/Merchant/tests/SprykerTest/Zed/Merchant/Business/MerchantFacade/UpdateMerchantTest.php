<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Business\MerchantFacade;

use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Merchant\MerchantConfig;
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

        $merchantResponseTransfer = $this->tester->getFacade()->updateMerchant($merchantTransfer);
        $updatedMerchant = $merchantResponseTransfer->getMerchant();

        $this->assertSame($expectedIdMerchant, $updatedMerchant->getIdMerchant());
        $this->assertEquals('second-key', $updatedMerchant->getMerchantKey());
        $this->assertEquals('Second Company', $updatedMerchant->getName());
        $this->assertNotEmpty($updatedMerchant->getAddressCollection()->getAddresses()->offsetGet(0));
    }

    /**
     * @dataProvider getCorrectStatusTransitions
     *
     * @param string[] $presetStatuses
     *
     * @return void
     */
    public function testUpdateMerchantWithCorrectStatusWorks(array $presetStatuses): void
    {
        $merchantTransfer = $this->tester->haveMerchant();
        $expectedStatus = end($presetStatuses);

        $merchantResponseTransfer = $this->updateMerchantWithStatuses($merchantTransfer, $presetStatuses);

        $this->assertTrue($merchantResponseTransfer->getIsSuccess());
        $this->assertSame($expectedStatus, $merchantResponseTransfer->getMerchant()->getStatus());
    }

    /**
     * @dataProvider getWrongStatusTransitions
     *
     * @param string[] $presetStatuses
     *
     * @return void
     */
    public function testUpdateMerchantWithIncorrectStatusReturnsIsSuccessFalse(array $presetStatuses): void
    {
        $merchantTransfer = $this->tester->haveMerchant();

        $merchantResponseTransfer = $this->updateMerchantWithStatuses($merchantTransfer, $presetStatuses);

        $this->assertFalse($merchantResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateMerchantWithoutDataThrowsException(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantTransfer
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
            [[MerchantConfig::STATUS_APPROVED]],
            [[MerchantConfig::STATUS_APPROVED, MerchantConfig::STATUS_ACTIVE]],
            [[MerchantConfig::STATUS_APPROVED, MerchantConfig::STATUS_INACTIVE]],
            [[MerchantConfig::STATUS_APPROVED, MerchantConfig::STATUS_ACTIVE, MerchantConfig::STATUS_INACTIVE]],
            [[MerchantConfig::STATUS_APPROVED, MerchantConfig::STATUS_INACTIVE, MerchantConfig::STATUS_ACTIVE]],
        ];
    }

    /**
     * @return array
     */
    public function getWrongStatusTransitions(): array
    {
        return [
            [[MerchantConfig::STATUS_ACTIVE]],
            [[MerchantConfig::STATUS_INACTIVE]],
            [[MerchantConfig::STATUS_APPROVED, MerchantConfig::STATUS_WAITING_FOR_APPROVAL]],
            [[MerchantConfig::STATUS_APPROVED, MerchantConfig::STATUS_ACTIVE, MerchantConfig::STATUS_WAITING_FOR_APPROVAL]],
            [[MerchantConfig::STATUS_APPROVED, MerchantConfig::STATUS_INACTIVE, MerchantConfig::STATUS_WAITING_FOR_APPROVAL]],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param array $presetStatuses
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    protected function updateMerchantWithStatuses(MerchantTransfer $merchantTransfer, array $presetStatuses): MerchantResponseTransfer
    {
        $merchantResponseTransfer = (new MerchantResponseTransfer())->setIsSuccess(false);

        foreach ($presetStatuses as $presetStatus) {
            $merchantTransfer->setStatus($presetStatus);
            $merchantResponseTransfer = $this->tester->getFacade()->updateMerchant($merchantTransfer);
            $merchantTransfer = $merchantResponseTransfer->getMerchant();

            if ($merchantResponseTransfer->getIsSuccess() === false) {
                return $merchantResponseTransfer;
            }
        }

        return $merchantResponseTransfer;
    }
}
