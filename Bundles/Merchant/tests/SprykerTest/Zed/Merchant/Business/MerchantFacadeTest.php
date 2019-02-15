<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Merchant\Business;

use Codeception\Test\Unit;
use Exception;
use Generated\Shared\Transfer\MerchantAddressTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\Merchant\Business\Exception\MerchantNotFoundException;
use Spryker\Zed\Merchant\Business\MerchantFacadeInterface;

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
    protected const MERCHANT_EMAIL = 'merchant@test.test';
    protected const ID_MERCHANT_ADDRESS = 1243;
    protected const MERCHANT_STATUS_WAITING_FOR_APPROVAL = 'waiting-for-approval'; // todo: has to be removed
    protected const MERCHANT_STATUS_APPROVED = 'approved';
    protected const MERCHANT_STATUS_ACTIVE = 'active';
    protected const MERCHANT_STATUS_INACTIVE = 'inactive';

    /**
     * @var \SprykerTest\Zed\Merchant\MerchantBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * @return void
     */
    public function testCreateMerchant(): void
    {
        $merchantTransfer = $this->tester->createMerchantTransferWithAddressTransfer();

        $merchantResponseTransfer = $this->getFacade()->createMerchant($merchantTransfer);

        $this->assertNotNull($merchantResponseTransfer->getMerchant()->getIdMerchant());
    }

    /**
     * @return void
     */
    public function testCreateMerchantWithEmptyKeyGeneratesKey(): void
    {
        $merchantTransfer = $this->tester->createMerchantTransferWithAddressTransfer()
            ->setMerchantKey(null);

        $merchantResponseTransfer = $this->getFacade()->createMerchant($merchantTransfer);

        $this->assertNotNull($merchantResponseTransfer->getMerchant()->getMerchantKey());
    }

    /**
     * @return void
     */
    public function testCreateMerchantCreatesMerchantWithCorrectStatus(): void
    {
        $merchantTransfer = $this->tester->createMerchantTransferWithAddressTransfer();

        $merchantResponseTransfer = $this->getFacade()->createMerchant($merchantTransfer);

        $this->assertEquals($this->tester->createMerchantConfig()->getDefaultMerchantStatus(), $merchantResponseTransfer->getMerchant()->getStatus());
    }

    /**
     * @return void
     */
    public function testCreateMerchantWithEmptyNameThrowsException(): void
    {
        $merchantTransfer = $this->tester->createMerchantTransferWithAddressTransfer()
            ->setName(null);

        $this->expectException(RequiredTransferPropertyException::class);

        $this->getFacade()->createMerchant($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testCreateMerchantWithNotUniqueName(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();
        $newMerchantTransfer = $this->tester->createMerchantTransferWithAddressTransfer()
            ->setName($merchantTransfer->getName());

        $merchantResponseTransfer = $this->getFacade()->createMerchant($newMerchantTransfer);
        $this->assertNotNull($merchantResponseTransfer->getMerchant()->getIdMerchant());
    }

    /**
     * @return void
     */
    public function testCreateMerchantWithNotUniqueKeyThrowsException(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();
        $newMerchantTransfer = $this->tester->createMerchantTransferWithAddressTransfer()
            ->setMerchantKey($merchantTransfer->getMerchantKey());

        $this->expectException(Exception::class);

        $this->getFacade()->createMerchant($newMerchantTransfer);
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
        $merchantAddressTransfer = $this->tester->haveMerchantAddress(['fkMerchant' => $expectedIdMerchant]);
        $merchantTransfer
            ->setMerchantKey('second-key')
            ->setName('Second Company')
            ->setAddress($merchantAddressTransfer);

        $merchantResponseTransfer = $this->getFacade()->updateMerchant($merchantTransfer);
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

        $merchantResponseTransfer = $this->getFacade()->updateMerchant($merchantTransfer);
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
            $merchantResponseTransfer = $this->getFacade()->updateMerchant($merchantTransfer);
            $merchantTransfer = $merchantResponseTransfer->getMerchant();
        }

        $expectedIdMerchant = $merchantTransfer->getIdMerchant();
        $merchantTransfer
            ->setStatus($correctlyChangedStatus);

        $merchantResponseTransfer = $this->getFacade()->updateMerchant($merchantTransfer);
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
            $this->getFacade()->updateMerchant($merchantTransfer);
        }

        $merchantTransfer
            ->setStatus($wronglyChangedStatus);

        $merchantResponseTransfer = $this->getFacade()->updateMerchant($merchantTransfer);

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

        $this->getFacade()->updateMerchant($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateMerchantWithWrongIdReturnsIsSuccessFalse(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantTransfer
            ->setIdMerchant($merchantTransfer->getIdMerchant() + 1);

        $merchantResponseTransfer = $this->getFacade()->updateMerchant($merchantTransfer);

        $this->assertFalse($merchantResponseTransfer->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testGetMerchantById(): void
    {
        $expectedMerchant = $this->tester->haveMerchant();

        $merchantTransfer = (new MerchantTransfer())
            ->setIdMerchant($expectedMerchant->getIdMerchant());

        $actualMerchant = $this->getFacade()->getMerchantById($merchantTransfer);

        $this->assertEquals($expectedMerchant, $actualMerchant);
    }

    /**
     * @return void
     */
    public function testGetMerchantByIdWillThrowMerchantNotFoundException(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();

        $merchantTransfer = (new MerchantTransfer())
            ->setIdMerchant($merchantTransfer->getIdMerchant() + 1);

        $this->expectException(MerchantNotFoundException::class);

        $this->getFacade()->getMerchantById($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testFindMerchantByIdWillFindExistingMerchant(): void
    {
        $expectedMerchant = $this->tester->haveMerchant();
        $actualMerchant = $this->getFacade()->findMerchantByIdMerchant($expectedMerchant->getIdMerchant());

        $this->assertEquals($expectedMerchant, $actualMerchant);
    }

    /**
     * @return void
     */
    public function testFindMerchantByIdWillNotFindMerchant(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();

        $actualMerchant = $this->getFacade()->findMerchantByIdMerchant($merchantTransfer->getIdMerchant() + 1);

        $this->assertNull($actualMerchant);
    }

    /**
     * @return void
     */
    public function testFindMerchantByEmailWillFindExistingMerchant(): void
    {
        $expectedMerchant = $this->tester->haveMerchant();

        $actualMerchant = $this->getFacade()->findMerchantByEmail($expectedMerchant->getEmail());

        $this->assertEquals($expectedMerchant, $actualMerchant);
    }

    /**
     * @return void
     */
    public function testFindMerchantByEmailWillNotFindMerchant(): void
    {
        $actualMerchant = $this->getFacade()->findMerchantByEmail(static::MERCHANT_EMAIL);

        $this->assertNull($actualMerchant);
    }

    /**
     * @return void
     */
    public function testDeleteMerchant(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();

        $this->getFacade()->deleteMerchant($merchantTransfer);

        $this->tester->assertMerchantNotExists($merchantTransfer->getIdMerchant());
    }

    /**
     * @return void
     */
    public function testDeleteMerchantWithoutIdMerchantThrowsException(): void
    {
        $merchantTransfer = new MerchantTransfer();

        $this->expectException(RequiredTransferPropertyException::class);

        $this->getFacade()->deleteMerchant($merchantTransfer);
    }

    /**
     * @return void
     */
    public function testGetMerchantsReturnNotEmptyCollection(): void
    {
        $this->tester->truncateMerchantRelations();

        $this->tester->haveMerchant();
        $this->tester->haveMerchant();

        $merchantCollectionTransfer = $this->getFacade()->getMerchantCollection();
        $this->assertCount(2, $merchantCollectionTransfer->getMerchants());
    }

    /**
     * @return void
     */
    public function testCreateMerchantAddress(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantAddressTransfer = $this->tester->createMerchantAddressTransfer()
            ->setFkMerchant($merchantTransfer->getIdMerchant());

        $this->getFacade()->createMerchantAddress($merchantAddressTransfer);

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

        $this->getFacade()->createMerchantAddress($merchantAddressTransfer);

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

        $this->getFacade()->createMerchantAddress($merchantAddressTransfer);
        $this->getFacade()->createMerchantAddress($merchantAddressTransfer2);
    }

    /**
     * @return void
     */
    public function testCreateMerchantAddressWithoutRequiredDataThrowsException(): void
    {
        $this->expectException(RequiredTransferPropertyException::class);

        $merchantAddressTransfer = (new MerchantAddressTransfer());

        $this->getFacade()->createMerchantAddress($merchantAddressTransfer);
    }

    /**
     * @return void
     */
    public function testfindMerchantAddressByIdMerchantAddressWillFindMerchantAddress(): void
    {
        $expectedMerchantAddressTransfer = $this->tester->haveMerchantAddress();

        $actualMerchantAddressTransfer = $this->getFacade()->findMerchantAddressByIdMerchantAddress(
            $expectedMerchantAddressTransfer->getIdMerchantAddress()
        );

        $this->assertNotEmpty($actualMerchantAddressTransfer->getIdMerchantAddress());
    }

    /**
     * @return void
     */
    public function testfindMerchantAddressByIdMerchantAddressWillNotFindMissingMerchantAddress(): void
    {
        $actualMerchantAddressTransfer = $this->getFacade()->findMerchantAddressByIdMerchantAddress(
            static::ID_MERCHANT_ADDRESS
        );

        $this->assertNull($actualMerchantAddressTransfer);
    }

    /**
     * @return void
     */
    public function testGetNextStatusesWillReturnArray(): void
    {
        $nextStatuses = $this->getFacade()->getNextStatuses($this->tester->createMerchantConfig()->getDefaultMerchantStatus());

        $this->assertTrue(is_array($nextStatuses));
        $this->assertNotEmpty($nextStatuses);
    }

    /**
     * @return void
     */
    public function testGetNextStatusesWillReturnEmptyArrayOnNotFoundCurrentStatus(): void
    {
        $nextStatuses = $this->getFacade()->getNextStatuses('random-status');

        $this->assertTrue(is_array($nextStatuses));
        $this->assertEmpty($nextStatuses);
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

    /**
     * @return \Spryker\Zed\Merchant\Business\MerchantFacadeInterface
     */
    protected function getFacade(): MerchantFacadeInterface
    {
        return $this->tester->getFacade();
    }
}
