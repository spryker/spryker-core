<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Asset\Business;

use Codeception\Test\Unit;
use DateTime;
use DateTimeZone;
use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetDeletedTransfer;
use Generated\Shared\Transfer\AssetTransfer;
use Generated\Shared\Transfer\AssetUpdatedTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Orm\Zed\Asset\Persistence\SpyAssetQuery;
use Spryker\Zed\Asset\Business\AssetBusinessFactory;
use Spryker\Zed\Asset\Business\AssetFacade;
use Spryker\Zed\Asset\Business\TimeStamp\AssetTimeStamp;
use Spryker\Zed\Asset\Persistence\AssetEntityManager;
use Spryker\Zed\Asset\Persistence\AssetPersistenceFactory;
use Spryker\Zed\Store\Business\Exception\StoreReferenceNotFoundException;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Asset
 * @group Business
 * @group Facade
 * @group AssetFacadeTest
 * Add your own group annotations below this line
 */
class AssetFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_STORE_NAME = 'DE';

    /**
     * @var string
     */
    protected const STORE_REFERENCE = 'dev-DE';

    /**
     * @var \SprykerTest\Zed\Asset\AssetBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setStoreReferenceData([static::TEST_STORE_NAME => static::STORE_REFERENCE]);
    }

    /**
     * @return void
     */
    public function testAddAssetAssertThrowsExceptionWhenStoreReferenceIsInvalid(): void
    {
        // Arrange
        $assetAddedTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => 'this-storeReference-does-not-exist',
            ],
        ]);

        // Assert
        $this->expectException(StoreReferenceNotFoundException::class);

        // Act
        $this->tester->getFacade()->addAsset($assetAddedTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetThrowsExceptionWhenStoreReferenceIsInvalid(): void
    {
        // Arrange
        $assetUpdatedTransfer = $this->tester->generateAssetUpdatedTransfer([
            AssetUpdatedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => 'this-storeReference-does-not-exist',
            ],
        ]);

        // Assert
        $this->expectException(StoreReferenceNotFoundException::class);

        // Act
        $this->tester->getFacade()->updateAsset($assetUpdatedTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteAssetAssertThrowsExceptionWhenStoreReferenceIsInvalid(): void
    {
        // Arrange
        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => 'this-storeReference-does-not-exist',
            ],
        ]);

        // Assert
        $this->expectException(StoreReferenceNotFoundException::class);

        // Act
        $this->tester->getFacade()->deleteAsset($assetDeletedTransfer);
    }

    /**
     * @return void
     */
    public function testFindAssetById(): void
    {
        // Arrange
        $expectedAssetTransfer = $this->tester->haveAsset([]);

        // Act
        $assetTransfer = $this->tester->getFacade()->findAssetById($expectedAssetTransfer->getIdAsset());

        // Assert
        $this->assertEquals($expectedAssetTransfer, $assetTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetWhenTheAssetDoesNotExistThenTheAssetIsAdded(): void
    {
        // Arrange
        $time = new DateTime('now', new DateTimeZone('UTC'));

        $assetMessageTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $time->format(AssetTimeStamp::TIMESTAMP_FORMAT),
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->addAsset($assetMessageTransfer);

        // Assert
        $this->tester->assertAssetAddedTransferAndAssetEntityAreEqual($assetMessageTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetWhenTheAssetAlreadyExistsAndTheMessageTimestampIsNullThenTheAssetIsUpdated(): void
    {
        // Arrange
        $initialTransfer = $this->tester->haveAsset([]);

        $assetMessageTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::ASSET_IDENTIFIER => $initialTransfer->getAssetUuid(),
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->addAsset($assetMessageTransfer);

        // Assert
        $this->tester->assertAssetAddedTransferAndAssetEntityAreEqual($assetMessageTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetWhenTheAssetAlreadyExistsAndTheAssetTimestampIsOlderThanTheMessageThenTheAssetIsUpdated(): void
    {
        // Arrange
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $initialTransfer = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeOld,
        ]);

        $assetMessageTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::ASSET_IDENTIFIER => $initialTransfer->getAssetUuid(),
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeNew,
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->addAsset($assetMessageTransfer);

        // Assert
        $this->tester->assertAssetAddedTransferAndAssetEntityAreEqual($assetMessageTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetWhenTheAssetAlreadyExistsAndTheAssetTimestampIsNewerThanTheMessageTimestampThenTheAssetIsNotUpdated(): void
    {
        // Arrange
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $expectedAssetTransfer = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeNew,
        ]);

        $assetMessageTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::ASSET_IDENTIFIER => $expectedAssetTransfer->getAssetUuid(),
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeOld,
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->addAsset($assetMessageTransfer);

        // Assert
        $this->tester->assertAssetTransferAndAssetEntityAreEqual($expectedAssetTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetWhenTheAssetAlreadyExistsButWasDeletedAndTheAssetTimestampIsOlderThanTheMessageTimestampThenTheAssetIsUpdated(): void
    {
        // Arrange
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $initialTransfer = $this->tester->haveAsset([
            AssetTransfer::IS_ACTIVE => false,
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeOld,
        ]);

        $assetMessageTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::ASSET_IDENTIFIER => $initialTransfer->getAssetUuid(),
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeNew,
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->addAsset($assetMessageTransfer);

        // Assert
        $this->tester->assertAssetAddedTransferAndAssetEntityAreEqual($assetMessageTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetWhenTheAssetDoesNotExistThenTheAssetIsAdded(): void
    {
        // Arrange
        $assetMessageTransfer = $this->tester->generateAssetUpdatedTransfer([
            AssetUpdatedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->updateAsset($assetMessageTransfer);

        // Assert
        $this->tester->assertAssetUpdatedTransferAndAssetEntityAreEqual($assetMessageTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetWhenTheAssetAlreadyExistsAndTheMessageTimestampIsNullThenTheAssetIsUpdated(): void
    {
        // Arrange
        $initialTransfer = $this->tester->haveAsset([]);

        $assetMessageTransfer = $this->tester->generateAssetUpdatedTransfer([
            AssetUpdatedTransfer::ASSET_IDENTIFIER => $initialTransfer->getAssetUuid(),
            AssetUpdatedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->updateAsset($assetMessageTransfer);

        // Assert
        $this->tester->assertAssetUpdatedTransferAndAssetEntityAreEqual($assetMessageTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetWhenTheAssetAlreadyExistsAndTheAssetTimestampIsOlderThanTheMessageTimestampThenTheAssetIsUpdated(): void
    {
        // Arrange
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $initialTransfer = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeOld,
        ]);

        $assetMessageTransfer = $this->tester->generateAssetUpdatedTransfer([
            AssetUpdatedTransfer::ASSET_IDENTIFIER => $initialTransfer->getAssetUuid(),
            AssetUpdatedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
                MessageAttributesTransfer::TIMESTAMP => $timeNew,
            ],
        ]);

        // Act
        $this->tester->getFacade()->updateAsset($assetMessageTransfer);

        // Assert
        $this->tester->assertAssetUpdatedTransferAndAssetEntityAreEqual($assetMessageTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetWhenTheAssetAlreadyExistsAndTheAssetTimestampIsNewerThanTheMessageTimestampThenTheAssetIsNotUpdated(): void
    {
        // Arrange
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $expectedAsset = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeNew,
        ]);

        $assetMessageTransfer = $this->tester->generateAssetUpdatedTransfer([
            AssetUpdatedTransfer::ASSET_IDENTIFIER => $expectedAsset->getAssetUuid(),
            AssetUpdatedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
                MessageAttributesTransfer::TIMESTAMP => $timeOld,
            ],
        ]);

        // Act
        $this->tester->getFacade()->updateAsset($assetMessageTransfer);

        // Assert
        $this->tester->assertAssetTransferAndAssetEntityAreEqual($expectedAsset);
    }

    /**
     * @return void
     */
    public function testDeleteAssetWhenTheAssetDoesNotExistAndIsActiveColumnNotPresentThenNoAssetIsCreated(): void
    {
        // Arrange
        $entityManagerMockedMethods = ['hasIsActiveColumn' => false];
        $assetFacadeMock = $this->getFacadeMockWithMockedEntitymanager($entityManagerMockedMethods);

        $assetMessageTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $assetFacadeMock->deleteAsset($assetMessageTransfer);

        // Assert
        $assetEntity = SpyAssetQuery::create()->filterByAssetUuid($assetMessageTransfer->getAssetIdentifierOrFail())->findOne();
        $this->assertNull($assetEntity);
    }

    /**
     * @return void
     */
    public function testDeleteAssetWhenTheAssetAlreadyExistsAndIsActiveColumnNotPresentAndTheMessageTimestampIsNullThenTheAssetIsDeleted(): void
    {
        // Arrange
        $entityManagerMockedMethods = ['hasIsActiveColumn' => false];
        $assetFacadeMock = $this->getFacadeMockWithMockedEntitymanager($entityManagerMockedMethods);

        $initialAsset = $this->tester->haveAsset([]);

        $assetMessageTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $initialAsset->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $assetFacadeMock->deleteAsset($assetMessageTransfer);

        // Assert
        $assetEntity = SpyAssetQuery::create()->filterByAssetUuid($initialAsset->getAssetUuid())->findOne();
        $this->assertNull($assetEntity);
    }

    /**
     * @return void
     */
    public function testDeleteAssetWhenTheAssetAlreadyExistsAndIsActiveColumnNotPresentAndTheAssetTimestampIsOlderThanTheMessageTimestampThenTheAssetIsDeleted(): void
    {
        // Arrange
        $entityManagerMockedMethods = ['hasIsActiveColumn' => false];
        $assetFacadeMock = $this->getFacadeMockWithMockedEntitymanager($entityManagerMockedMethods);
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $initialAsset = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeOld,
        ]);

        $assetMessageTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $initialAsset->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
                MessageAttributesTransfer::TIMESTAMP => $timeNew,
            ],
        ]);

        // Act
        $assetFacadeMock->deleteAsset($assetMessageTransfer);

        // Assert
        $assetEntity = SpyAssetQuery::create()->filterByAssetUuid($initialAsset->getAssetUuid())->findOne();
        $this->assertNull($assetEntity);
    }

    /**
     * @return void
     */
    public function testDeleteAssetWhenTheAssetAlreadyExistsAndIsActiveColumnNotPresentAndTheAssetTimestampIsNewerThanTheMessageTimestampThenTheAssetIsDeleted(): void
    {
        // Arrange
        $entityManagerMockedMethods = ['hasIsActiveColumn' => false];
        $assetFacadeMock = $this->getFacadeMockWithMockedEntitymanager($entityManagerMockedMethods);
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $expectedAsset = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeNew,
        ]);

        $assetMessageTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $expectedAsset->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
                MessageAttributesTransfer::TIMESTAMP => $timeOld,
            ],
        ]);

        // Act
        $assetFacadeMock->deleteAsset($assetMessageTransfer);

        // Assert
        $this->tester->assertAssetTransferAndAssetEntityAreEqual($expectedAsset);
    }

    /**
     * @return void
     */
    public function testDeleteAssetWhenTheAssetDoesNotExistThenAnInactiveAssetIsCreated(): void
    {
        // Arrange

        $assetMessageTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->deleteAsset($assetMessageTransfer);

        // Assert
        $this->tester->assertAssetDeletedTransferAndAssetEntityAreEqual($assetMessageTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteAssetWhenTheAssetAlreadyExistsAndTheMessageTimestampIsNullThenTheAssetIsSetInactive(): void
    {
        // Arrange
        $time = new DateTime('now', new DateTimeZone('UTC'));

        $initialAssetTransfer = $this->tester->haveAsset([
            AssetTransfer::IS_ACTIVE => true,
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $time->format(AssetTimeStamp::TIMESTAMP_FORMAT),
        ]);

        $assetMessageTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $initialAssetTransfer->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);
        $expectedAssetTransfer = clone $initialAssetTransfer;
        $expectedAssetTransfer->setIsActive(false);

        // Act
        $this->tester->getFacade()->deleteAsset($assetMessageTransfer);

        // Assert
        $this->tester->assertAssetTransferAndAssetEntityAreEqualWithNewerTimestamp($expectedAssetTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteAssetWhenTheAssetAlreadyExistsAndTheAssetTimestampIsOlderThanTheMessageTimestampThenTheAssetIsSetInactive(): void
    {
        // Arrange
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $initialAssetTransfer = $this->tester->haveAsset([
            AssetTransfer::IS_ACTIVE => true,
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeOld,
        ]);

        $assetMessageTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $initialAssetTransfer->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeNew,
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        $expectedAssetTransfer = clone $initialAssetTransfer;
        $expectedAssetTransfer->setIsActive(false);

        // Act
        $this->tester->getFacade()->deleteAsset($assetMessageTransfer);

        // Assert
        $this->tester->assertAssetTransferAndAssetEntityAreEqualWithNewerTimestamp($expectedAssetTransfer);
    }

    /**
     * @return void
     */
    public function testDeleteAssetWhenTheAssetAlreadyExistsAndTheAssetTimestampIsNewerThanTheMessageTimestampThenTheAssetIsNotModified(): void
    {
        // Arrange
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $initialAssetTransfer = $this->tester->haveAsset([
            AssetTransfer::IS_ACTIVE => true,
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeNew,
        ]);

        $assetMessageTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $initialAssetTransfer->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeOld,
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->deleteAsset($assetMessageTransfer);

        // Assert
        $this->tester->assertAssetTransferAndAssetEntityAreEqual($initialAssetTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetWhenTheAssetAlreadyExistsAndTheAssetLastMessageTimestampIsNullThenTheAssetIsUpdated(): void
    {
        // Arrange
        $initialTransfer = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => null,
        ]);

        $assetMessageTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::ASSET_IDENTIFIER => $initialTransfer->getAssetUuid(),
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->addAsset($assetMessageTransfer);

        // Assert
        $this->tester->assertAssetAddedTransferAndAssetEntityAreEqual($assetMessageTransfer);
    }

    /**
     * @param array $entityManagerMockedMethods
     *
     * @return \Spryker\Zed\Asset\Business\AssetFacade
     */
    public function getFacadeMockWithMockedEntityManager(array $entityManagerMockedMethods): AssetFacade
    {
        $mockedMethods = array_keys($entityManagerMockedMethods);
        $entityManagerMock = $this->getMockBuilder(AssetEntityManager::class)->onlyMethods($mockedMethods)->getMock();
        foreach ($entityManagerMockedMethods as $method => $value) {
            $entityManagerMock->method($method)->willReturn($value);
        }

        $entityManagerMock->setFactory(new AssetPersistenceFactory());
        $assetFacadeMock = new AssetFacade();
        $assetBusinessFactory = new AssetBusinessFactory();
        $assetBusinessFactory->setEntityManager($entityManagerMock);
        $assetFacadeMock->setFactory($assetBusinessFactory);

        return $assetFacadeMock;
    }
}
