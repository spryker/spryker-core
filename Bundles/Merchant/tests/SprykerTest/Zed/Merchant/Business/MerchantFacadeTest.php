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
use Spryker\Zed\Merchant\Business\Exception\MerchantStatusTransitionNotAllowedException;
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
    protected const ID_MERCHANT = 123;
    protected const MERCHANT_EMAIL = 'merchant@test.test';
    protected const ID_MERCHANT_ADDRESS = 1243;
    protected const MERCHANT_STATUS_WAITING_FOR_APPROVAL = 'waiting-for-approval';

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
            ->setName('Spryker Merchant')
            ->setContactPersonFirstName('Spryker')
            ->setContactPersonLastName('Merchant')
            ->setContactPersonPhone('1234567890')
            ->setContactPersonTitle('Dr')
            ->setRegistrationNumber('1234-56789-12')
            ->setEmail('spryker.merchant@localhost.com')
            ->setAddress($this->tester->createMerchantAddressTransfer());

        (new MerchantFacade())->createMerchant($merchantTransfer);

        $this->assertNotNull($merchantTransfer->getIdMerchant());
    }

    /**
     * @return void
     */
    public function testCreateMerchantWithEmptyKeyGeneratesKey(): void
    {
        $merchantTransfer = (new MerchantTransfer())
            ->setName('Spryker Merchant')
            ->setContactPersonFirstName('Spryker')
            ->setContactPersonLastName('Merchant')
            ->setContactPersonPhone('1234567890')
            ->setContactPersonTitle('Dr')
            ->setRegistrationNumber('1234-56789-12')
            ->setEmail('spryker.merchant@localhost.com')
            ->setAddress($this->tester->createMerchantAddressTransfer());

        (new MerchantFacade())->createMerchant($merchantTransfer);

        $this->assertNotNull($merchantTransfer->getMerchantKey());
    }

    /**
     * @return void
     */
    public function testCreateMerchantCreatesMerchantWithCorrectStatus(): void
    {
        $merchantTransfer = (new MerchantTransfer())
            ->setName('Spryker Merchant')
            ->setContactPersonFirstName('Spryker')
            ->setContactPersonLastName('Merchant')
            ->setContactPersonPhone('1234567890')
            ->setContactPersonTitle('Dr')
            ->setRegistrationNumber('1234-56789-12')
            ->setEmail('spryker.merchant@localhost.com')
            ->setAddress($this->tester->createMerchantAddressTransfer());

        (new MerchantFacade())->createMerchant($merchantTransfer);

        $this->assertEquals(static::MERCHANT_STATUS_WAITING_FOR_APPROVAL, $merchantTransfer->getStatus());
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
            ->setName($merchantTransfer->getName())
            ->setContactPersonFirstName('Spryker')
            ->setContactPersonLastName('Merchant')
            ->setContactPersonPhone('1234567890')
            ->setContactPersonTitle('Dr')
            ->setRegistrationNumber('1234-56789-12')
            ->setEmail('spryker.merchant@localhost.com')
            ->setAddress($this->tester->createMerchantAddressTransfer());

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
            ->setName($merchantTransfer->getName())
            ->setContactPersonFirstName('Spryker')
            ->setContactPersonLastName('Merchant')
            ->setContactPersonPhone('1234567890')
            ->setContactPersonTitle('Dr')
            ->setRegistrationNumber('1234-56789-12')
            ->setEmail('spryker.merchant@localhost.com')
            ->setAddress($this->tester->createMerchantAddressTransfer());

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
        $merchantAddressTransfer = $this->tester->haveMerchantAddress(['fkMerchant' => $expectedIdMerchant]);
        $merchantTransfer
            ->setMerchantKey('second-key')
            ->setName('Second Company')
            ->setAddress($merchantAddressTransfer);

        $updatedMerchant = (new MerchantFacade())->updateMerchant($merchantTransfer);

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

        $updatedMerchant = (new MerchantFacade())->updateMerchant($merchantTransfer);

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

        $merchantFacade = new MerchantFacade();
        foreach ($presetStatuses as $presetStatus) {
            $merchantTransfer->setStatus($presetStatus);
            $merchantFacade->updateMerchant($merchantTransfer);
        }

        $expectedIdMerchant = $merchantTransfer->getIdMerchant();
        $merchantTransfer
            ->setStatus($correctlyChangedStatus);

        $updatedMerchant = $merchantFacade->updateMerchant($merchantTransfer);

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
    public function testUpdateMerchantWithIncorrectStatusThrowsException(array $presetStatuses, string $wronglyChangedStatus): void
    {
        $merchantTransfer = $this->tester->haveMerchant();

        $merchantFacade = new MerchantFacade();
        foreach ($presetStatuses as $presetStatus) {
            $merchantTransfer->setStatus($presetStatus);
            $merchantFacade->updateMerchant($merchantTransfer);
        }
        $this->expectException(MerchantStatusTransitionNotAllowedException::class);

        $merchantTransfer
            ->setStatus($wronglyChangedStatus);

        $merchantFacade->updateMerchant($merchantTransfer);
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
    public function testGetMerchantByIdWillThrowOnMerchantNotFound(): void
    {
        $this->expectException(MerchantNotFoundException::class);

        $merchantTransfer = (new MerchantTransfer())
            ->setIdMerchant(123);

        (new MerchantFacade())->getMerchantById($merchantTransfer);

        //$this->assertEquals($expectedMerchant, $actualMerchant);
    }

    /**
     * @return void
     */
    public function testFindMerchantByIdWillFindExistingMerchant(): void
    {
        $expectedMerchant = $this->tester->haveMerchant();

        $merchantTransfer = (new MerchantTransfer())
            ->setIdMerchant($expectedMerchant->getIdMerchant());

        $actualMerchant = (new MerchantFacade())->findMerchantById($merchantTransfer);

        $this->assertEquals($expectedMerchant, $actualMerchant);
    }

    /**
     * @return void
     */
    public function testFindMerchantByIdWillNotFindMerchant(): void
    {
        $merchantTransfer = (new MerchantTransfer())
            ->setIdMerchant(static::ID_MERCHANT);

        $actualMerchant = (new MerchantFacade())->findMerchantById($merchantTransfer);

        $this->assertNull($actualMerchant);
    }

    /**
     * @return void
     */
    public function testFindMerchantByEmailWillFindExistingMerchant(): void
    {
        $expectedMerchant = $this->tester->haveMerchant();

        $merchantTransfer = (new MerchantTransfer())
            ->setEmail($expectedMerchant->getEmail());

        $actualMerchant = (new MerchantFacade())->findMerchantByEmail($merchantTransfer);

        $this->assertEquals($expectedMerchant, $actualMerchant);
    }

    /**
     * @return void
     */
    public function testFindMerchantByEmailWillNotFindMerchant(): void
    {
        $merchantTransfer = (new MerchantTransfer())
            ->setEmail(static::MERCHANT_EMAIL);

        $actualMerchant = (new MerchantFacade())->findMerchantByEmail($merchantTransfer);

        $this->assertNull($actualMerchant);
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
    public function testDeleteMerchantWithoutIdMerchantThrowsException(): void
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

    /**
     * @return void
     */
    public function testCreateMerchantAddress(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();
        $country = $this->tester->haveCountry();

        $merchantAddressTransfer = (new MerchantAddressTransfer())
            ->setKey('Merchant-address-1')
            ->setFkMerchant($merchantTransfer->getIdMerchant())
            ->setAddress1('street')
            ->setAddress2('number')
            ->setCity('city')
            ->setZipCode('12567')
            ->setFkCountry($country->getIdCountry());

        /** @var \Spryker\Zed\Merchant\Business\MerchantFacade $merchantFacade */
        $merchantFacade = $this->tester->getFacade();
        $merchantFacade->createMerchantAddress($merchantAddressTransfer);

        $this->assertNotNull($merchantAddressTransfer->getIdMerchantAddress());
    }

    /**
     * @return void
     */
    public function testCreateMerchantAddressWithEmptyKey(): void
    {
        $merchantTransfer = $this->tester->haveMerchant();
        $country = $this->tester->haveCountry();

        $merchantAddressTransfer = (new MerchantAddressTransfer())
            ->setFkMerchant($merchantTransfer->getIdMerchant())
            ->setAddress1('street')
            ->setAddress2('number')
            ->setCity('city')
            ->setZipCode('12567')
            ->setFkCountry($country->getIdCountry());

        /** @var \Spryker\Zed\Merchant\Business\MerchantFacade $merchantFacade */
        $merchantFacade = $this->tester->getFacade();
        $merchantFacade->createMerchantAddress($merchantAddressTransfer);

        $this->assertNotNull($merchantAddressTransfer->getIdMerchantAddress());
    }

    /**
     * @return void
     */
    public function testCreateMerchantAddressWithExistingKeyThrowsException(): void
    {
        $this->expectException(PropelException::class);

        $merchantTransfer = $this->tester->haveMerchant();
        $country = $this->tester->haveCountry();

        $merchantAddressTransfer = $this->tester->haveMerchantAddress(['key' => 'Merchant-address-1']);

        $merchantAddressTransfer2 = (new MerchantAddressTransfer())
            ->setKey($merchantAddressTransfer->getKey())
            ->setFkMerchant($merchantTransfer->getIdMerchant())
            ->setAddress1('street')
            ->setAddress2('number')
            ->setCity('city')
            ->setZipCode('12567')
            ->setFkCountry($country->getIdCountry());

        /** @var \Spryker\Zed\Merchant\Business\MerchantFacade $merchantFacade */
        $merchantFacade = $this->tester->getFacade();
        $merchantFacade->createMerchantAddress($merchantAddressTransfer);
        $merchantFacade->createMerchantAddress($merchantAddressTransfer2);
    }

    /**
     * @return void
     */
    public function testCreateMerchantAddressWithoutRequiredDataThrowsException(): void
    {
        $this->expectException(RequiredTransferPropertyException::class);

        $merchantAddressTransfer = (new MerchantAddressTransfer());

        /** @var \Spryker\Zed\Merchant\Business\MerchantFacade $merchantFacade */
        $merchantFacade = $this->tester->getFacade();
        $merchantFacade->createMerchantAddress($merchantAddressTransfer);
    }

    /**
     * @return void
     */
    public function testFindMerchantAddressByIdWillFindMerchantAddress(): void
    {
        $expectedMerchantAddressTransfer = $this->tester->haveMerchantAddress();

        /** @var \Spryker\Zed\Merchant\Business\MerchantFacade $merchantFacade */
        $merchantFacade = $this->tester->getFacade();
        $actualMerchantAddressTransfer = $merchantFacade->findMerchantAddressById(
            (new MerchantAddressTransfer())
                ->setIdMerchantAddress($expectedMerchantAddressTransfer->getIdMerchantAddress())
        );

        $this->assertNotEmpty($actualMerchantAddressTransfer->getIdMerchantAddress());
    }

    /**
     * @return void
     */
    public function testFindMerchantAddressByIdWillNotFindMissingMerchantAddress(): void
    {
        /** @var \Spryker\Zed\Merchant\Business\MerchantFacade $merchantFacade */
        $merchantFacade = $this->tester->getFacade();
        $actualMerchantAddressTransfer = $merchantFacade->findMerchantAddressById(
            (new MerchantAddressTransfer())
                ->setIdMerchantAddress(static::ID_MERCHANT_ADDRESS)
        );

        $this->assertNull($actualMerchantAddressTransfer);
    }

    /**
     * @return void
     */
    public function testGetNextStatusesWillReturnArray(): void
    {
        /** @var \Spryker\Zed\Merchant\Business\MerchantFacade $merchantFacade */
        $merchantFacade = $this->tester->getFacade();

        $nextStatuses = $merchantFacade->getNextStatuses('waiting-for-approval');

        $this->assertTrue(is_array($nextStatuses));
        $this->assertNotEmpty($nextStatuses);
    }

    /**
     * @return void
     */
    public function testGetNextStatusesWillReturnEmptyArrayOnNotFoundCurrentStatus(): void
    {
        /** @var \Spryker\Zed\Merchant\Business\MerchantFacade $merchantFacade */
        $merchantFacade = $this->tester->getFacade();

        $nextStatuses = $merchantFacade->getNextStatuses('random-status');

        $this->assertTrue(is_array($nextStatuses));
        $this->assertEmpty($nextStatuses);
    }

    /**
     * @return array
     */
    public function getCorrectStatusTransitions(): array
    {
        return [
            [[], 'approved'],
            [['approved'], 'active'],
            [['approved'], 'inactive'],
            [['approved', 'active'], 'inactive'],
            [['approved', 'inactive'], 'active'],
        ];
    }

    /**
     * @return array
     */
    public function getWrongStatusTransitions(): array
    {
        return [
            [[], 'active'],
            [[], 'inactive'],
            [['approved'], 'waiting-for-approval'],
            [['approved', 'active'], 'waiting-for-approval'],
            [['approved', 'inactive'], 'waiting-for-approval'],
        ];
    }
}
