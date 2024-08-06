<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantApp\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingCriteriaTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Ramsey\Uuid\Uuid;
use SprykerTest\Zed\MerchantApp\MerchantAppBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantApp
 * @group Business
 * @group Facade
 * @group MerchantAppFacadeTest
 * Add your own group annotations below this line
 */
class MerchantAppFacadeTest extends Unit
{
    protected MerchantAppBusinessTester $tester;

    /**
     * @return void
     */
    public function testGivenMerchantAppOnboardingExistsWhenIFetchThoseThenIGetBackAListOfAll(): void
    {
        // Arrange
        $appIdentifier = Uuid::uuid4()->toString();
        $this->tester->haveAppConfigPersisted([AppConfigTransfer::APP_IDENTIFIER => $appIdentifier]);

        $this->tester->haveMerchantAppOnboardingPersisted([MerchantAppOnboardingTransfer::TYPE => 'foo', MerchantAppOnboardingTransfer::APP_IDENTIFIER => $appIdentifier]);
        $this->tester->haveMerchantAppOnboardingPersisted([MerchantAppOnboardingTransfer::TYPE => 'bar', MerchantAppOnboardingTransfer::APP_IDENTIFIER => $appIdentifier]);

        $merchantAppOnboardingCriteriaTransfer = new MerchantAppOnboardingCriteriaTransfer();

        // Act
        $merchantAppOnboardingCollectionTransfer = $this->tester->getFacade()->getMerchantAppOnboardingCollection($merchantAppOnboardingCriteriaTransfer);

        // Assert
        $this->tester->assertMerchantAppOnboardingCountByType($merchantAppOnboardingCollectionTransfer, 'foo', 1);
        $this->tester->assertMerchantAppOnboardingCountByType($merchantAppOnboardingCollectionTransfer, 'bar', 1);
        $this->assertNotNull($merchantAppOnboardingCollectionTransfer->getOnboardings()[1]->getAdditionalContent());
        $this->assertNotEmpty($merchantAppOnboardingCollectionTransfer->getOnboardings()[1]->getAdditionalContent()->getLinks());
    }

    /**
     * @return void
     */
    public function testGivenMerchantAppOnboardingExistsWhenIFetchThoseForASpecificTypeThenIGetBackAListOfThisType(): void
    {
        // Arrange
        $appIdentifier = Uuid::uuid4()->toString();
        $this->tester->haveAppConfigPersisted([AppConfigTransfer::APP_IDENTIFIER => $appIdentifier]);

        $this->tester->haveMerchantAppOnboardingPersisted([MerchantAppOnboardingTransfer::TYPE => 'foo', MerchantAppOnboardingTransfer::APP_IDENTIFIER => $appIdentifier]);
        $this->tester->haveMerchantAppOnboardingPersisted([MerchantAppOnboardingTransfer::TYPE => 'bar', MerchantAppOnboardingTransfer::APP_IDENTIFIER => $appIdentifier]);

        $merchantAppOnboardingCriteriaTransfer = new MerchantAppOnboardingCriteriaTransfer();
        $merchantAppOnboardingCriteriaTransfer->setType('foo');

        // Act
        $merchantAppOnboardingCollectionTransfer = $this->tester->getFacade()->getMerchantAppOnboardingCollection($merchantAppOnboardingCriteriaTransfer);

        // Assert
        $this->tester->assertMerchantAppOnboardingCountByType($merchantAppOnboardingCollectionTransfer, 'foo', 1);
    }

    /**
     * @return void
     */
    public function testGivenMerchantAppOnboardingExistsWhenIFetchThoseForASpecificAppThenIGetBackAListOfOnboardingsForTheRequestedApp(): void
    {
        // Arrange
        $appIdentifier = Uuid::uuid4()->toString();
        $this->tester->haveAppConfigPersisted([AppConfigTransfer::APP_IDENTIFIER => $appIdentifier]);

        $merchantOnboardingDetailsTransfer = $this->tester->haveMerchantAppOnboardingPersisted([MerchantAppOnboardingTransfer::APP_IDENTIFIER => $appIdentifier]);

        $merchantAppOnboardingCriteriaTransfer = new MerchantAppOnboardingCriteriaTransfer();
        $merchantAppOnboardingCriteriaTransfer->addAppIdentifier($merchantOnboardingDetailsTransfer->getAppIdentifier());

        // Act
        $merchantAppOnboardingCollectionTransfer = $this->tester->getFacade()->getMerchantAppOnboardingCollection($merchantAppOnboardingCriteriaTransfer);

        // Assert
        $this->tester->assertMerchantAppOnboardingCountByAppIdentifier($merchantAppOnboardingCollectionTransfer, $merchantOnboardingDetailsTransfer->getAppIdentifier(), 1);
    }

    /**
     * @return void
     */
    public function testGivenMerchantAppOnboardingExistsAndMerchantAppOnboardingWasInitializedWhenIFetchTheOnboardingDetailsThenICanSeeTheMerchantAppOnboardingStatus(): void
    {
        // Arrange
        $appIdentifier = Uuid::uuid4()->toString();
        $merchantTransfer = $this->tester->haveMerchant();

        $this->tester->haveAppConfigPersisted([AppConfigTransfer::APP_IDENTIFIER => $appIdentifier]);

        $expectedMerchantAppOnboardingTransfer = $this->tester->haveMerchantAppOnboardingPersisted([MerchantAppOnboardingTransfer::APP_IDENTIFIER => $appIdentifier]);
        $expectedMerchantAppOnboardingStatusTransfer = $this->tester->haveMerchantAppOnboardingStatusPersisted([
            MerchantAppOnboardingStatusTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            MerchantAppOnboardingStatusTransfer::MERCHANT_APP_ONBOARDING => [
                MerchantAppOnboardingTransfer::APP_IDENTIFIER => $expectedMerchantAppOnboardingTransfer->getAppIdentifier(),
                MerchantAppOnboardingTransfer::TYPE => $expectedMerchantAppOnboardingTransfer->getType(),
            ],
        ]);

        $merchantAppOnboardingCriteriaTransfer = new MerchantAppOnboardingCriteriaTransfer();
        $merchantAppOnboardingCriteriaTransfer
            ->addAppIdentifier($expectedMerchantAppOnboardingTransfer->getAppIdentifier())
            ->setMerchant((new MerchantTransfer())->setMerchantReference($expectedMerchantAppOnboardingStatusTransfer->getMerchantReference()))
            ->setType($expectedMerchantAppOnboardingTransfer->getType());

        // Act
        $merchantAppOnboardingCollectionTransfer = $this->tester->getFacade()->getMerchantAppOnboardingCollection($merchantAppOnboardingCriteriaTransfer);

        // Assert
        /** @var \Generated\Shared\Transfer\MerchantAppOnboardingTransfer $merchantAppOnboardingTransfer */
        $merchantAppOnboardingTransfer = $merchantAppOnboardingCollectionTransfer->getOnboardings()->offsetGet(0);
        $this->assertSame($expectedMerchantAppOnboardingTransfer->getAppIdentifier(), $merchantAppOnboardingTransfer->getAppIdentifier());

        $this->assertNotNull($merchantAppOnboardingTransfer->getStatus());
    }
}
