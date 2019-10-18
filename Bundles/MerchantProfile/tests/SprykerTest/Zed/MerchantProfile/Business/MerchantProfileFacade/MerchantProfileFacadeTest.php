<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfile\Business\MerchantProfileFacade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;

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
    public function testCreateMerchantProfile(): void
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
    public function testUpdateMerchantProfile(): void
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
        $updatedMerchantProfileTransfer = $this->tester->getFacade()->updateMerchantProfile($merchantProfileTransfer);

        // Assert
        $this->assertSame($expectedIdMerchantProfile, $updatedMerchantProfileTransfer->getIdMerchantProfile());
        $this->assertEquals('Role two', $updatedMerchantProfileTransfer->getContactPersonRole());
        $this->assertEquals('First Name Two', $updatedMerchantProfileTransfer->getContactPersonFirstName());
    }

    /**
     * @return void
     */
    public function testMerchantProfileGlossaryKey(): void
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
    public function testFindOneMerchantProfile(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $expectedMerchantProfileTransfer = $this->tester->haveMerchantProfile($merchantTransfer);

        // Act
        $merchantProfileCriteriaFilterTransfer = new MerchantProfileCriteriaFilterTransfer();
        $merchantProfileCriteriaFilterTransfer->setFkMerchant($expectedMerchantProfileTransfer->getFkMerchant());
        $merchantProfileTransfer = $this->tester->getFacade()->findOne($merchantProfileCriteriaFilterTransfer);

        // Assert
        $this->assertNotNull($merchantProfileTransfer);
        $this->assertEquals($expectedMerchantProfileTransfer->getIdMerchantProfile(), $merchantProfileTransfer->getIdMerchantProfile());
    }

    /**
     * @return void
     */
    public function testExpandMerchantWithMerchantProfile(): void
    {
        // Arrange
        $merchantTransfer = $this->tester->haveMerchant();
        $merchantProfileTransfer = $this->tester->haveMerchantProfile($merchantTransfer);

        // Act
        $expandedMerchantTransfer = $this->tester->getFacade()->expandMerchantWithMerchantProfile($merchantTransfer);
        $expectedMerchantProfileTransfer = $expandedMerchantTransfer->getMerchantProfile();

        // Assert
        $this->assertEquals($merchantProfileTransfer->getIdMerchantProfile(), $expectedMerchantProfileTransfer->getIdMerchantProfile());
    }
}
