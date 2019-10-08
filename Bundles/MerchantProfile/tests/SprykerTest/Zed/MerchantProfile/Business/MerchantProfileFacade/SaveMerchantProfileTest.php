<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantProfile\Business\MerchantProfileFacade;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantProfile
 * @group Business
 * @group MerchantProfileFacade
 * @group SaveMerchantProfileTest
 * Add your own group annotations below this line
 */
class SaveMerchantProfileTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\MerchantProfile\MerchantProfileBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testSaveMerchantProfile(): void
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
}
