<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PushNotification\Business\PushNotificationFacade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PushNotificationProviderCollectionRequestTransfer;
use SprykerTest\Zed\PushNotification\PushNotificationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PushNotification
 * @group Business
 * @group PushNotificationFacade
 * @group Facade
 * @group PushNotificationFacadeCreatePushNotificationProviderCollectionTest
 * Add your own group annotations below this line
 */
class PushNotificationFacadeCreatePushNotificationProviderCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PushNotification\PushNotificationBusinessTester
     */
    protected PushNotificationBusinessTester $tester;

    /**
     * @return void
     */
    public function testCreatePushNotificationProviderCollectionShouldReturnCreatedPushNotificationProviderCollectionWhenValidPushNotificationProviderCollectionGiven(): void
    {
        // Arrange
        $pushNotificationProviderTransfers = $this->tester->createValidPushNotificationProviders();
        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setPushNotificationProviders(new ArrayObject($pushNotificationProviderTransfers));
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $pushNotificationFacade->createPushNotificationProviderCollection(
            $pushNotificationProviderCollectionRequestTransfer,
        );

        // Assert
        $this->assertCount(
            count($pushNotificationProviderTransfers),
            $pushNotificationProviderCollectionResponseTransfer->getPushNotificationProviders(),
        );
        $this->assertEmpty($pushNotificationProviderCollectionResponseTransfer->getErrors());
    }

    /**
     * @return void
     */
    public function testCreatePushNotificationProviderCollectionShouldReturnValidationErrorsWhenInvalidPushNotificationProviderCollectionGiven(): void
    {
        // Arrange
        $pushNotificationProviderTransfers = $this->tester->createInvalidPushNotificationProviders();
        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setPushNotificationProviders(new ArrayObject($pushNotificationProviderTransfers));
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $pushNotificationFacade->createPushNotificationProviderCollection(
            $pushNotificationProviderCollectionRequestTransfer,
        );

        // Assert
        $this->assertEmpty($pushNotificationProviderCollectionResponseTransfer->getPushNotificationProviders());
        $this->assertCount(
            count($pushNotificationProviderTransfers),
            $pushNotificationProviderCollectionResponseTransfer->getErrors(),
        );
    }

    /**
     * @return void
     */
    public function testCreatePushNotificationProviderCollectionShouldCreateOnlyValidPushNotificationProvidersWhenNonTransactionalModeUsed(): void
    {
        // Arrange
        $validPushNotificationProviderTransfers = $this->tester->createValidPushNotificationProviders();
        $invalidPushNotificationProviderTransfers = $this->tester->createInvalidPushNotificationProviders();
        $pushNotificationProviderTransfers = array_merge($validPushNotificationProviderTransfers, $invalidPushNotificationProviderTransfers);

        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->setIsTransactional(false)
            ->setPushNotificationProviders(
                new ArrayObject(
                    $pushNotificationProviderTransfers,
                ),
            );
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $pushNotificationFacade->createPushNotificationProviderCollection(
            $pushNotificationProviderCollectionRequestTransfer,
        );

        // Assert
        $this->assertCount(
            count($validPushNotificationProviderTransfers),
            $pushNotificationProviderCollectionResponseTransfer->getPushNotificationProviders(),
        );
        $this->assertCount(
            count($invalidPushNotificationProviderTransfers),
            $pushNotificationProviderCollectionResponseTransfer->getErrors(),
        );
    }

    /**
     * @return void
     */
    public function testCreatePushNotificationProviderCollectionShouldNotCreatePushNotificationProvidersWhenAtLeastOneInvalidPushNotificationProviderGivenAndTransactionalModeUsed(): void
    {
        // Arrange
        $validPushNotificationProviderTransfers = $this->tester->createValidPushNotificationProviders();
        $invalidPushNotificationProviderTransfers = $this->tester->createInvalidPushNotificationProviders();
        $pushNotificationProviderTransfers = array_merge($validPushNotificationProviderTransfers, $invalidPushNotificationProviderTransfers);

        $pushNotificationProviderCollectionRequestTransfer = (new PushNotificationProviderCollectionRequestTransfer())
            ->setIsTransactional(true)
            ->setPushNotificationProviders(
                new ArrayObject(
                    $pushNotificationProviderTransfers,
                ),
            );
        $pushNotificationFacade = $this->tester->getFacade();

        // Act
        $pushNotificationProviderCollectionResponseTransfer = $pushNotificationFacade->createPushNotificationProviderCollection(
            $pushNotificationProviderCollectionRequestTransfer,
        );

        // Assert
        $this->assertEmpty(
            $pushNotificationProviderCollectionResponseTransfer->getPushNotificationProviders(),
        );
        $this->assertCount(
            count($invalidPushNotificationProviderTransfers),
            $pushNotificationProviderCollectionResponseTransfer->getErrors(),
        );
    }
}
