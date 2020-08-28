<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Business\MerchantFacade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Merchant\MerchantConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Merchant
 * @group Business
 * @group MerchantFacade
 * @group UpdateMerchantTest
 * Add your own group annotations below this line
 */
class UpdateMerchantTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Merchant\MerchantBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdateMerchant(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant([
            MerchantTransfer::MERCHANT_KEY => 'one-key',
            MerchantTransfer::NAME => 'One Company',
        ]);

        $expectedIdMerchant = $merchantTransfer->getIdMerchant();
        $merchantTransfer
            ->setMerchantKey('second-key')
            ->setName('Second Company');

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->updateMerchant($merchantTransfer);
        $updatedMerchant = $merchantResponseTransfer->getMerchant();

        // Assert
        $this->assertSame($expectedIdMerchant, $updatedMerchant->getIdMerchant());
        $this->assertSame('second-key', $updatedMerchant->getMerchantKey());
        $this->assertSame('Second Company', $updatedMerchant->getName());
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
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $expectedStatus = end($presetStatuses);

        // Act
        $merchantResponseTransfer = $this->updateMerchantWithStatuses($merchantTransfer, $presetStatuses);

        // Assert
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
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        // Act
        $merchantResponseTransfer = $this->updateMerchantWithStatuses($merchantTransfer, $presetStatuses);

        // Assert
        $this->assertFalse($merchantResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateMerchantWithEmptyRequiredFieldsThrowsException(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->createMerchantTransfer();
        $merchantWithEmptyNameTransfer = clone $merchantTransfer;
        $merchantWithEmptyNameTransfer->setName(null);

        $merchantWithEmptyEmailTransfer = clone $merchantTransfer;
        $merchantWithEmptyEmailTransfer->setEmail(null);
        $merchantTransfer->setIdMerchant(null);

        // Assert
        $this->expectException(RequiredTransferPropertyException::class);

        // Act
        $this->tester->getFacade()->updateMerchant($merchantWithEmptyNameTransfer);
        $this->tester->getFacade()->updateMerchant($merchantWithEmptyEmailTransfer);
        $this->tester->getFacade()->updateMerchant($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateMerchantWithWrongIdReturnsIsSuccessFalse(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantTransfer
            ->setIdMerchant($merchantTransfer->getIdMerchant() + 1);

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->updateMerchant($merchantTransfer);

        // Assert
        $this->assertFalse($merchantResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUpdateMerchantStores(): void
    {
        // Arrange
        $storeTransfer1 = $this->tester->haveStore([StoreTransfer::NAME => 'DE']);
        $storeTransfer2 = $this->tester->haveStore([StoreTransfer::NAME => 'AT']);
        $storeRelationTransfer = (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_STORES => [$storeTransfer1->getIdStore()],
        ])->build();
        $merchantTransfer = $this->tester->haveMerchant([ MerchantTransfer::STORE_RELATION => $storeRelationTransfer->toArray()]);
        $storesIds = [
            $storeTransfer1->getIdStore(),
            $storeTransfer2->getIdStore(),
        ];
        $merchantTransfer->getStoreRelation()->setIdStores($storesIds);

        // Act
        $merchantResponseTransfer = $this->tester->getFacade()->updateMerchant($merchantTransfer);
        $merchantTransfer = $merchantResponseTransfer->getMerchant();

        // Assert
        $this->assertCount(count($storesIds), $merchantTransfer->getStoreRelation()->getStores());
        foreach ($merchantTransfer->getStoreRelation()->getIdStores() as $idStore) {
            $this->assertTrue(in_array($idStore, $storesIds));
        }
    }

    /**
     * @return array
     */
    public function getCorrectStatusTransitions(): array
    {
        return [
            [[MerchantConfig::STATUS_APPROVED]],
            [[MerchantConfig::STATUS_APPROVED, MerchantConfig::STATUS_DENIED]],
            [[MerchantConfig::STATUS_APPROVED, MerchantConfig::STATUS_DENIED, MerchantConfig::STATUS_APPROVED]],
        ];
    }

    /**
     * @return array
     */
    public function getWrongStatusTransitions(): array
    {
        return [
            [[MerchantConfig::STATUS_APPROVED, MerchantConfig::STATUS_WAITING_FOR_APPROVAL]],
            [[MerchantConfig::STATUS_DENIED, MerchantConfig::STATUS_WAITING_FOR_APPROVAL]],
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
