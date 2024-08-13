<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantApp\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingInitializationRequestTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingStatusTransfer;
use Generated\Shared\Transfer\MerchantAppOnboardingTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\OnboardingTransfer;
use Ramsey\Uuid\Uuid;
use Spryker\Shared\MerchantApp\Message\MerchantAppMessage;
use Spryker\Zed\MerchantApp\Business\Exception\MerchantAppOnboardingLogicException;
use Spryker\Zed\MerchantApp\Business\Exception\MerchantAppOnboardingNotFoundException;
use Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboarding;
use Spryker\Zed\MerchantApp\Business\MerchantAppOnboarding\MerchantAppOnboardingStatusInterface;
use Spryker\Zed\MerchantApp\Dependency\Facade\MerchantAppToKernelAppFacadeBridge;
use Spryker\Zed\MerchantApp\MerchantAppDependencyProvider;
use SprykerTest\Zed\MerchantApp\MerchantAppBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MerchantApp
 * @group Business
 * @group Facade
 * @group MerchantAppFacadeInitializeOnboardingTest
 * Add your own group annotations below this line
 */
class MerchantAppFacadeInitializeOnboardingTest extends Unit
{
    /**
     * @var string
     */
    public const ONBOARDING_TYPE = 'payment';

    /**
     * @var \SprykerTest\Zed\MerchantApp\MerchantAppBusinessTester
     */
    protected MerchantAppBusinessTester $tester;

    /**
     * @return void
     */
    public function testGivenNoMerchantAppOnboardingExistsForTheRequestedAppIdentifierAndTypeWhenITryToInitializeTheOnboardingProcessThenAnExceptionWillBeThrown(): void
    {
        // Arrange
        $appIdentifier = Uuid::uuid4()->toString();
        $merchantReference = Uuid::uuid4()->toString();

        $merchantTransfer = (new MerchantTransfer())->setMerchantReference($merchantReference);

        $merchantAppOnboardingInitializationRequestTransfer = new MerchantAppOnboardingInitializationRequestTransfer();
        $merchantAppOnboardingInitializationRequestTransfer
            ->setMerchant($merchantTransfer)
            ->setAppIdentifier($appIdentifier)
            ->setType(static::ONBOARDING_TYPE);

        // Expect
        $this->expectException(MerchantAppOnboardingNotFoundException::class);
        $this->expectExceptionMessage(MerchantAppMessage::getMerchantAppOnboardingNotFoundExceptionMessage($appIdentifier, static::ONBOARDING_TYPE));

        // Act
        $this->tester->getFacade()->initializeMerchantAppOnboarding($merchantAppOnboardingInitializationRequestTransfer);
    }

    /**
     * @return void
     */
    public function testGivenMerchantAppOnboardingWithIFrameStrategyWhenIInitializeTheOnboardingProcessThenTheOnboardingStatusIsInitializedAndTheResponseContainsTheIFrameUrl(): void
    {
        // Arrange
        $appIdentifier = Uuid::uuid4()->toString();
        $merchantReference = Uuid::uuid4()->toString();

        $this->tester->haveAppConfigPersisted([AppConfigTransfer::APP_IDENTIFIER => $appIdentifier]);

        $merchantAppOnboardingTransfer = $this->tester->haveMerchantAppOnboardingPersisted([
            MerchantAppOnboardingTransfer::APP_IDENTIFIER => $appIdentifier,
            MerchantAppOnboardingTransfer::TYPE => static::ONBOARDING_TYPE,
            OnboardingTransfer::STRATEGY => MerchantAppOnboarding::STRATEGY_IFRAME,
            OnboardingTransfer::URL => 'iframe-url',
        ]);

        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => $merchantReference]);

        $merchantAppOnboardingInitializationRequestTransfer = new MerchantAppOnboardingInitializationRequestTransfer();
        $merchantAppOnboardingInitializationRequestTransfer->setAppIdentifier($appIdentifier);
        $merchantAppOnboardingInitializationRequestTransfer->setType(static::ONBOARDING_TYPE);
        $merchantAppOnboardingInitializationRequestTransfer->setMerchant($merchantTransfer);

        // Act
        $merchantAppOnboardingInitializationResponseTransfer = $this->tester->getFacade()->initializeMerchantAppOnboarding($merchantAppOnboardingInitializationRequestTransfer);

        // Assert
        $this->assertSame($merchantAppOnboardingTransfer->getOnboarding()->getStrategy(), $merchantAppOnboardingInitializationResponseTransfer->getStrategy());
        $this->assertSame($merchantAppOnboardingTransfer->getOnboarding()->getUrl(), $merchantAppOnboardingInitializationResponseTransfer->getUrl());
    }

    /**
     * @return void
     */
    public function testGivenMerchantAppOnboardingWithRedirectStrategyWhenIInitializeTheOnboardingProcessThenTheOnboardingStatusIsInitializedAndTheResponseContainsTheRedirectUrl(): void
    {
        // Arrange
        $appIdentifier = Uuid::uuid4()->toString();
        $merchantReference = Uuid::uuid4()->toString();

        $this->tester->haveAppConfigPersisted([AppConfigTransfer::APP_IDENTIFIER => $appIdentifier]);

        $merchantAppOnboardingTransfer = $this->tester->haveMerchantAppOnboardingPersisted([
            MerchantAppOnboardingTransfer::APP_IDENTIFIER => $appIdentifier,
            MerchantAppOnboardingTransfer::TYPE => static::ONBOARDING_TYPE,
            OnboardingTransfer::STRATEGY => MerchantAppOnboarding::STRATEGY_REDIRECT,
            OnboardingTransfer::URL => 'redirect-url',
        ]);

        $merchantTransfer = $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => $merchantReference]);

        $merchantAppOnboardingInitializationRequestTransfer = new MerchantAppOnboardingInitializationRequestTransfer();
        $merchantAppOnboardingInitializationRequestTransfer->setAppIdentifier($appIdentifier);
        $merchantAppOnboardingInitializationRequestTransfer->setType(static::ONBOARDING_TYPE);
        $merchantAppOnboardingInitializationRequestTransfer->setMerchant($merchantTransfer);

        // Act
        $merchantAppOnboardingInitializationResponseTransfer = $this->tester->getFacade()->initializeMerchantAppOnboarding($merchantAppOnboardingInitializationRequestTransfer);

        // Assert
        $this->assertSame($merchantAppOnboardingTransfer->getOnboarding()->getStrategy(), $merchantAppOnboardingInitializationResponseTransfer->getStrategy());
        $this->assertSame($merchantAppOnboardingTransfer->getOnboarding()->getUrl(), $merchantAppOnboardingInitializationResponseTransfer->getUrl());

        $expectedMerchantAppOnboardingStatusTransfer = new MerchantAppOnboardingStatusTransfer();
        $expectedMerchantAppOnboardingStatusTransfer
            ->setMerchantReference($merchantReference)
            ->setStatus(MerchantAppOnboardingStatusInterface::INCOMPLETE);

        $this->tester->seeMerchantAppOnboardingStatusEntityInDatabase($expectedMerchantAppOnboardingStatusTransfer);
    }

    /**
     * @return void
     */
    public function testGivenMerchantAppOnboardingWithApiStrategyWhenIInitializeTheOnboardingProcessThenTheOnboardingStatusIsInitializedAndTheAppApiIsCalledAndTheResponseContainsTheRedirectUrl(): void
    {
        // Arrange
        $appIdentifier = Uuid::uuid4()->toString();

        $this->tester->haveAppConfigPersisted([AppConfigTransfer::APP_IDENTIFIER => $appIdentifier]);

        $this->tester->haveMerchantAppOnboardingPersisted([
            MerchantAppOnboardingTransfer::APP_IDENTIFIER => $appIdentifier,
            MerchantAppOnboardingTransfer::TYPE => static::ONBOARDING_TYPE,
            OnboardingTransfer::STRATEGY => MerchantAppOnboarding::STRATEGY_API,
            OnboardingTransfer::URL => 'api-url',
        ]);

        $merchantTransfer = $this->tester->haveMerchant();

        $merchantAppOnboardingInitializationRequestTransfer = new MerchantAppOnboardingInitializationRequestTransfer();
        $merchantAppOnboardingInitializationRequestTransfer->setAppIdentifier($appIdentifier);
        $merchantAppOnboardingInitializationRequestTransfer->setType(static::ONBOARDING_TYPE);
        $merchantAppOnboardingInitializationRequestTransfer->setMerchant($merchantTransfer);

        $acpHttpResponseTransfer = new AcpHttpResponseTransfer();
        $acpHttpResponseTransfer->setContent(json_encode([
            'strategy' => MerchantAppOnboarding::STRATEGY_REDIRECT,
            'url' => 'new-url',
        ]));

        $kernelAppFacadeMock = $this->tester->mockFacadeMethod('makeRequest', $acpHttpResponseTransfer, 'KernelApp');
        $this->tester->setDependency(MerchantAppDependencyProvider::FACADE_KERNEL_APP, new MerchantAppToKernelAppFacadeBridge($kernelAppFacadeMock));

        // Act
        $merchantAppOnboardingInitializationResponseTransfer = $this->tester->getFacade()->initializeMerchantAppOnboarding($merchantAppOnboardingInitializationRequestTransfer);

        // Assert
        $this->assertSame(MerchantAppOnboarding::STRATEGY_REDIRECT, $merchantAppOnboardingInitializationResponseTransfer->getStrategy());
        $this->assertSame('new-url', $merchantAppOnboardingInitializationResponseTransfer->getUrl());

        $expectedMerchantAppOnboardingStatusTransfer = new MerchantAppOnboardingStatusTransfer();
        $expectedMerchantAppOnboardingStatusTransfer
            ->setMerchantReference($merchantTransfer->getMerchantReference())
            ->setStatus(MerchantAppOnboardingStatusInterface::INCOMPLETE);

        $this->tester->seeMerchantAppOnboardingStatusEntityInDatabase($expectedMerchantAppOnboardingStatusTransfer);
    }

    /**
     * @return void
     */
    public function testValidationThrowsExceptionWhenTheOnboardingTypeIsMissingInTheInitializationRequest(): void
    {
        // Arrange
        $merchantAppOnboardingInitializationRequestTransfer = new MerchantAppOnboardingInitializationRequestTransfer();

        // Expect
        $this->expectException(MerchantAppOnboardingLogicException::class);
        $this->expectExceptionMessage(MerchantAppMessage::getMerchantAppOnboardingMissingOnboardingTypeExceptionMessage());

        // Act
        $this->tester->getFacade()->initializeMerchantAppOnboarding($merchantAppOnboardingInitializationRequestTransfer);
    }

    /**
     * @return void
     */
    public function testValidationThrowsExceptionWhenTheAppIdentifierIsMissingInTheInitializationRequest(): void
    {
        // Arrange
        $merchantAppOnboardingInitializationRequestTransfer = new MerchantAppOnboardingInitializationRequestTransfer();
        $merchantAppOnboardingInitializationRequestTransfer->setType(static::ONBOARDING_TYPE);

        // Expect
        $this->expectException(MerchantAppOnboardingLogicException::class);
        $this->expectExceptionMessage(MerchantAppMessage::getMerchantAppOnboardingMissingAppIdentifierExceptionMessage());

        // Act
        $this->tester->getFacade()->initializeMerchantAppOnboarding($merchantAppOnboardingInitializationRequestTransfer);
    }

    /**
     * @return void
     */
    public function testValidationThrowsExceptionWhenTheMerchantIsMissingInTheInitializationRequest(): void
    {
        // Arrange
        $merchantAppOnboardingInitializationRequestTransfer = new MerchantAppOnboardingInitializationRequestTransfer();
        $merchantAppOnboardingInitializationRequestTransfer->setType(static::ONBOARDING_TYPE);
        $merchantAppOnboardingInitializationRequestTransfer->setAppIdentifier(Uuid::uuid4());

        // Expect
        $this->expectException(MerchantAppOnboardingLogicException::class);
        $this->expectExceptionMessage(MerchantAppMessage::getMerchantAppOnboardingMissingMerchantExceptionMessage());

        // Act
        $this->tester->getFacade()->initializeMerchantAppOnboarding($merchantAppOnboardingInitializationRequestTransfer);
    }
}
