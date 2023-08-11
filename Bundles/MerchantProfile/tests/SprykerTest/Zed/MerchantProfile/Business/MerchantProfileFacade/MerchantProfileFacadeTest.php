<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfile\Business\MerchantProfileFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileCriteriaTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProfile
 * @group Business
 * @group MerchantProfileFacade
 * @group Facade
 * @group MerchantProfileFacadeTest
 * Add your own group annotations below this line
 */
class MerchantProfileFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProfile\MerchantProfileBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testFindFiltersByIdMerchantProfile(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantProfileTransfer = $this->tester->haveMerchantProfile($merchantTransfer);

        // Act
        $merchantProfileCriteriaTransfer = new MerchantProfileCriteriaTransfer();
        $merchantProfileCriteriaTransfer->setMerchantProfileIds([$merchantProfileTransfer->getIdMerchantProfile()]);
        $merchantProfileTransferCollection = $this->tester->getFacade()->get($merchantProfileCriteriaTransfer);

        // Assert
        $this->assertNotEmpty($merchantProfileTransferCollection->getMerchantProfiles());
        $this->assertCount(1, $merchantProfileTransferCollection->getMerchantProfiles());
    }

    /**
     * @return void
     */
    public function testCreateMerchantProfilePersistsToDatabase(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();

        // Act
        $merchantProfileTransfer = $this->tester->haveMerchantProfile($merchantTransfer);

        // Assert
        $this->assertNotNull($merchantProfileTransfer->getIdMerchantProfile());
    }

    /**
     * @return void
     */
    public function testUpdateMerchantProfilePersistsToDatabase(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantProfileSeedData = [
            'contactPersonRole' => 'Role one',
            'contactPersonFirstName' => 'First Name One',
        ];
        $merchantProfileTransfer = $this->tester->haveMerchantProfile($merchantTransfer, $merchantProfileSeedData);
        $expectedIdMerchantProfile = $merchantProfileTransfer->getIdMerchantProfile();

        $merchantProfileTransfer->setContactPersonRole('Role two')
            ->setContactPersonFirstName('First Name Two');

        // Act
        /** @var \Generated\Shared\Transfer\MerchantProfileTransfer $updatedMerchantProfileTransfer */
        $updatedMerchantProfileTransfer = $this->tester->getFacade()->updateMerchantProfile($merchantProfileTransfer);

        // Assert
        $this->assertSame($expectedIdMerchantProfile, $updatedMerchantProfileTransfer->getIdMerchantProfile());
        $this->assertSame('Role two', $updatedMerchantProfileTransfer->getContactPersonRole());
        $this->assertSame('First Name Two', $updatedMerchantProfileTransfer->getContactPersonFirstName());
        $this->assertNotEmpty($updatedMerchantProfileTransfer->getAddressCollection());
    }

    /**
     * @return void
     */
    public function testCreateMerchantProfileSavesMerchantProfileGlossaryKey(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantProfileTransfer = $this->tester->haveMerchantProfile($merchantTransfer);

        // Act
        $bannerUrlGlossaryKey = $merchantProfileTransfer->getBannerUrlGlossaryKey();
        $cancellationPolicyGlossaryKey = $merchantProfileTransfer->getCancellationPolicyGlossaryKey();
        $dataPrivacyGlossaryKey = $merchantProfileTransfer->getDataPrivacyGlossaryKey();
        $deliveryTimeGlossaryKey = $merchantProfileTransfer->getDeliveryTimeGlossaryKey();
        $descriptionGlossaryKey = $merchantProfileTransfer->getDescriptionGlossaryKey();
        $imprintGlossaryKey = $merchantProfileTransfer->getImprintGlossaryKey();
        $termsConditionsGlossaryKey = $merchantProfileTransfer->getTermsConditionsGlossaryKey();

        $hasBannerUrlGlossaryKey = $this->tester->hasGlossaryKey($bannerUrlGlossaryKey);
        $hasCancellationPolicyGlossaryKey = $this->tester->hasGlossaryKey($cancellationPolicyGlossaryKey);
        $hasDataPrivacyGlossaryKey = $this->tester->hasGlossaryKey($dataPrivacyGlossaryKey);
        $hasDeliveryTimeGlossaryKey = $this->tester->hasGlossaryKey($deliveryTimeGlossaryKey);
        $hasDescriptionGlossaryKey = $this->tester->hasGlossaryKey($descriptionGlossaryKey);
        $hasImprintGlossaryKey = $this->tester->hasGlossaryKey($imprintGlossaryKey);
        $hasTermsConditionsGlossaryKey = $this->tester->hasGlossaryKey($termsConditionsGlossaryKey);
        $hasGlossaryKey = $this->tester->hasGlossaryKey($imprintGlossaryKey);

        // Assert
        $this->assertTrue($hasBannerUrlGlossaryKey);
        $this->assertTrue($hasCancellationPolicyGlossaryKey);
        $this->assertTrue($hasDataPrivacyGlossaryKey);
        $this->assertTrue($hasDeliveryTimeGlossaryKey);
        $this->assertTrue($hasDescriptionGlossaryKey);
        $this->assertTrue($hasImprintGlossaryKey);
        $this->assertTrue($hasTermsConditionsGlossaryKey);
        $this->assertTrue($hasGlossaryKey);
    }

    /**
     * @return void
     */
    public function testFindOneMerchantProfileFiltersByFkMerchant(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $expectedMerchantProfileTransfer = $this->tester->haveMerchantProfile($merchantTransfer);

        // Act
        $merchantProfileCriteriaTransfer = new MerchantProfileCriteriaTransfer();
        $merchantProfileCriteriaTransfer->setMerchantIds([$expectedMerchantProfileTransfer->getFkMerchant()]);
        $merchantProfileTransfer = $this->tester->getFacade()->findOne($merchantProfileCriteriaTransfer);

        // Assert
        $this->assertNotNull($merchantProfileTransfer);
        $this->assertSame($expectedMerchantProfileTransfer->getIdMerchantProfile(), $merchantProfileTransfer->getIdMerchantProfile());
    }

    /**
     * @return void
     */
    public function testHydrateOrderTransferWithMerchantsHasMerchantsInfoWhenMerchantsProvided(): void
    {
        // Arrange
        $merchantTransfer1 = $this->tester->haveMerchant(['merchantReference' => 'M001']);
        $expectedMerchantProfileTransfer1 = $this->tester->haveMerchantProfile($merchantTransfer1);

        $merchantTransfer2 = $this->tester->haveMerchant(['merchantReference' => 'M002']);
        $expectedMerchantProfileTransfer2 = $this->tester->haveMerchantProfile($merchantTransfer2);

        $orderTransfer = new OrderTransfer();
        $orderTransfer->setMerchantReferences([
            $expectedMerchantProfileTransfer1->getMerchantReference(),
            $expectedMerchantProfileTransfer2->getMerchantReference(),
        ]);

        // Act
        $this->tester->getFacade()->hydrateOrderMerchants($orderTransfer);
        $merchants = $orderTransfer->getMerchants();

        // Assert
        $this->assertCount(2, $merchants);

        $this->assertEquals($expectedMerchantProfileTransfer1->getMerchantReference(), $merchants[0]->getMerchantReference());
        $this->assertEquals($expectedMerchantProfileTransfer1->getMerchantName(), $merchants[0]->getName());
        $this->assertEquals($expectedMerchantProfileTransfer1->getLogoUrl(), $merchants[0]->getImageUrl());

        $this->assertEquals($expectedMerchantProfileTransfer2->getMerchantReference(), $merchants[1]->getMerchantReference());
        $this->assertEquals($expectedMerchantProfileTransfer2->getMerchantName(), $merchants[1]->getName());
        $this->assertEquals($expectedMerchantProfileTransfer2->getLogoUrl(), $merchants[1]->getImageUrl());
    }

    /**
     * @return void
     */
    public function testHydrateOrderTransferWithMerchantsHasNoMerchantsWhenNoMerchantsProvided(): void
    {
        // Arrange
        $orderTransfer = new OrderTransfer();

        // Act
        $this->tester->getFacade()->hydrateOrderMerchants($orderTransfer);
        $merchants = $orderTransfer->getMerchants();

        // Assert
        $this->assertCount(0, $merchants);
    }

    /**
     * @return void
     */
    public function testExpandMerchantCollectionWithMerchantProfileReturnsMerchantCollectionWithRelatedMerchantProfileIfExist(): void
    {
        // Arrange
        $merchantCollectionTransfer = new MerchantCollectionTransfer();
        $merchantCollectionTransfer->addMerchants(
            $this->tester->haveMerchantWithProfile(),
        );
        $merchantCollectionTransfer->addMerchants(
            $this->tester->haveMerchant(),
        );
        $merchantCollectionTransfer->addMerchants(
            $this->tester->haveMerchantWithProfile(),
        );
        $merchantCollectionTransfer->addMerchants(
            $this->tester->haveMerchant(),
        );

        $this->tester->haveMerchant();

        // Act
        $resultMerchantCollectionTransfer = $this->tester->getFacade()
            ->expandMerchantCollectionWithMerchantProfile($merchantCollectionTransfer);

        // Assert
        $this->assertCount(4, $resultMerchantCollectionTransfer->getMerchants());
        $this->tester->assertMerchantHasExactMerchantProfile(
            $merchantCollectionTransfer->getMerchants()->offsetGet(0)->getMerchantProfile(),
            $resultMerchantCollectionTransfer->getMerchants()->offsetGet(0),
        );
        $this->tester->assertMerchantHasNotMerchantProfile(
            $resultMerchantCollectionTransfer->getMerchants()->offsetGet(1),
        );
        $this->tester->assertMerchantHasExactMerchantProfile(
            $merchantCollectionTransfer->getMerchants()->offsetGet(2)->getMerchantProfile(),
            $resultMerchantCollectionTransfer->getMerchants()->offsetGet(2),
        );
        $this->tester->assertMerchantHasNotMerchantProfile(
            $resultMerchantCollectionTransfer->getMerchants()->offsetGet(3),
        );
    }

    /**
     * @return void
     */
    public function testExpandMerchantCollectionWithMerchantProfileReturnsEmptyMerchantCollectionIfEmptyMerchantCollectionWasPassed(): void
    {
        // Arrange
        $merchantCollectionTransfer = new MerchantCollectionTransfer();

        // Act
        $resultMerchantCollectionTransfer = $this->tester->getFacade()
            ->expandMerchantCollectionWithMerchantProfile($merchantCollectionTransfer);

        // Assert
        $this->assertCount(0, $resultMerchantCollectionTransfer->getMerchants());
    }

    /**
     * @return void
     */
    public function testMerchantCollectionWithMerchantProfileThrowsExceptionIfMerchantCollectionMerchantHasNoIdMerchant(): void
    {
        // Arrange
        $merchantCollectionTransfer = (new MerchantCollectionTransfer())
            ->addMerchants(new MerchantTransfer());

        // Assert
        $this->expectException(NullValueException::class);

        // Act
        $this->tester->getFacade()
            ->expandMerchantCollectionWithMerchantProfile($merchantCollectionTransfer);
    }
}
