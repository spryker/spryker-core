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
use Generated\Shared\Transfer\StoreTransfer;
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

        $assetAddedTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $time->format(AssetTimeStamp::TIMESTAMP_FORMAT),
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->addAsset($assetAddedTransfer);

        // Assert
        $this->tester->assertAssetAddedTransferAndAssetEntityAreEqual($assetAddedTransfer);
    }

    /**
     * @return void
     */
    public function testCreateAssetWhenTheAssetDoesNotExistThenTheAssetIsCreated(): void
    {
        // Arrange
        $time = new DateTime('now', new DateTimeZone('UTC'));

        $assetAddedTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $time->format(AssetTimeStamp::TIMESTAMP_FORMAT),
            ],
        ]);

        // Act
        $this->tester->getFacade()->createAsset($assetAddedTransfer);

        // Assert
        $this->tester->assertAssetAddedTransferAndAssetEntityAreEqual($assetAddedTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetWhenTheAssetAlreadyExistsAndTheMessageTimestampIsNullThenTheAssetIsUpdated(): void
    {
        // Arrange
        $assetTransfer = $this->tester->haveAsset([]);

        $assetAddedTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::ASSET_IDENTIFIER => $assetTransfer->getAssetUuid(),
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->addAsset($assetAddedTransfer);

        // Assert
        $this->tester->assertAssetAddedTransferAndAssetEntityAreEqual($assetAddedTransfer);
    }

    /**
     * @return void
     */
    public function testCreateAssetWhenTheAssetAlreadyExistsAndTheMessageTimestampIsNullThenTheAssetIsUpdated(): void
    {
        // Arrange
        $assetTransfer = $this->tester->haveAsset([]);

        $assetAddedTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::ASSET_IDENTIFIER => $assetTransfer->getAssetUuid(),
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [],
        ]);

        // Act
        $this->tester->getFacade()->createAsset($assetAddedTransfer);

        // Assert
        $this->tester->assertAssetAddedTransferAndAssetEntityAreEqual($assetAddedTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetWhenTheAssetAlreadyExistsAndTheAssetTimestampIsOlderThanTheMessageThenTheAssetIsUpdated(): void
    {
        // Arrange
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $assetTransfer = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeOld,
        ]);

        $assetAddedTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::ASSET_IDENTIFIER => $assetTransfer->getAssetUuid(),
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeNew,
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->addAsset($assetAddedTransfer);

        // Assert
        $this->tester->assertAssetAddedTransferAndAssetEntityAreEqual($assetAddedTransfer);
    }

    /**
     * @return void
     */
    public function testCreateAssetWhenTheAssetAlreadyExistsAndTheAssetTimestampIsOlderThanTheMessageThenTheAssetIsUpdated(): void
    {
        // Arrange
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $assetTransfer = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeOld,
        ]);

        $assetAddedTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::ASSET_IDENTIFIER => $assetTransfer->getAssetUuid(),
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeNew,
            ],
        ]);

        // Act
        $this->tester->getFacade()->createAsset($assetAddedTransfer);

        // Assert
        $this->tester->assertAssetAddedTransferAndAssetEntityAreEqual($assetAddedTransfer);
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

        $assetAddedTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::ASSET_IDENTIFIER => $expectedAssetTransfer->getAssetUuid(),
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeOld,
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->addAsset($assetAddedTransfer);

        // Assert
        $this->tester->assertAssetTransferAndAssetEntityAreEqual($expectedAssetTransfer);
    }

    /**
     * @return void
     */
    public function testCreateAssetWhenTheAssetAlreadyExistsAndTheAssetTimestampIsNewerThanTheMessageTimestampThenTheAssetIsNotUpdated(): void
    {
        // Arrange
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $expectedAssetTransfer = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeNew,
        ]);

        $assetAddedTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::ASSET_IDENTIFIER => $expectedAssetTransfer->getAssetUuid(),
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeOld,
            ],
        ]);

        // Act
        $this->tester->getFacade()->createAsset($assetAddedTransfer);

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

        $assetTransfer = $this->tester->haveAsset([
            AssetTransfer::IS_ACTIVE => false,
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeOld,
        ]);

        $assetAddedTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::ASSET_IDENTIFIER => $assetTransfer->getAssetUuid(),
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeNew,
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->addAsset($assetAddedTransfer);

        // Assert
        $this->tester->assertAssetAddedTransferAndAssetEntityAreEqual($assetAddedTransfer);
    }

    /**
     * @return void
     */
    public function testCreateAssetWhenTheAssetAlreadyExistsButWasDeletedAndTheAssetTimestampIsOlderThanTheMessageTimestampThenTheAssetIsUpdated(): void
    {
        // Arrange
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $assetTransfer = $this->tester->haveAsset([
            AssetTransfer::IS_ACTIVE => false,
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeOld,
        ]);

        $assetAddedTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::ASSET_IDENTIFIER => $assetTransfer->getAssetUuid(),
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeNew,
            ],
        ]);

        // Act
        $this->tester->getFacade()->createAsset($assetAddedTransfer);

        // Assert
        $this->tester->assertAssetAddedTransferAndAssetEntityAreEqual($assetAddedTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetWhenTheAssetDoesNotExistThenTheAssetIsAdded(): void
    {
        // Arrange
        $assetUpdatedTransfer = $this->tester->generateAssetUpdatedTransfer([
            AssetUpdatedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->updateAsset($assetUpdatedTransfer);

        // Assert
        $this->tester->assertAssetUpdatedTransferAndAssetEntityAreEqual($assetUpdatedTransfer);
    }

    /**
     * @return void
     */
    public function testSaveAssetWhenTheAssetDoesNotExistThenTheAssetIsAdded(): void
    {
        // Arrange
        $assetUpdatedTransfer = $this->tester->generateAssetUpdatedTransfer([
            AssetUpdatedTransfer::MESSAGE_ATTRIBUTES => [],
        ]);

        // Act
        $this->tester->getFacade()->saveAsset($assetUpdatedTransfer);

        // Assert
        $this->tester->assertAssetUpdatedTransferAndAssetEntityAreEqual($assetUpdatedTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetWhenTheAssetAlreadyExistsAndTheMessageTimestampIsNullThenTheAssetIsUpdated(): void
    {
        // Arrange
        $assetTransfer = $this->tester->haveAsset([]);

        $assetUpdatedTransfer = $this->tester->generateAssetUpdatedTransfer([
            AssetUpdatedTransfer::ASSET_IDENTIFIER => $assetTransfer->getAssetUuid(),
            AssetUpdatedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->updateAsset($assetUpdatedTransfer);

        // Assert
        $this->tester->assertAssetUpdatedTransferAndAssetEntityAreEqual($assetUpdatedTransfer);
    }

    /**
     * @return void
     */
    public function testSaveAssetWhenTheAssetAlreadyExistsAndTheMessageTimestampIsNullThenTheAssetIsUpdated(): void
    {
        // Arrange
        $assetTransfer = $this->tester->haveAsset([]);

        $assetUpdatedTransfer = $this->tester->generateAssetUpdatedTransfer([
            AssetUpdatedTransfer::ASSET_IDENTIFIER => $assetTransfer->getAssetUuid(),
            AssetUpdatedTransfer::MESSAGE_ATTRIBUTES => [],
        ]);

        // Act
        $this->tester->getFacade()->saveAsset($assetUpdatedTransfer);

        // Assert
        $this->tester->assertAssetUpdatedTransferAndAssetEntityAreEqual($assetUpdatedTransfer);
    }

    /**
     * @return void
     */
    public function testUpdateAssetWhenTheAssetAlreadyExistsAndTheAssetTimestampIsOlderThanTheMessageTimestampThenTheAssetIsUpdated(): void
    {
        // Arrange
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $assetTransfer = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeOld,
        ]);

        $assetUpdatedTransfer = $this->tester->generateAssetUpdatedTransfer([
            AssetUpdatedTransfer::ASSET_IDENTIFIER => $assetTransfer->getAssetUuid(),
            AssetUpdatedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
                MessageAttributesTransfer::TIMESTAMP => $timeNew,
            ],
        ]);

        // Act
        $this->tester->getFacade()->updateAsset($assetUpdatedTransfer);

        // Assert
        $this->tester->assertAssetUpdatedTransferAndAssetEntityAreEqual($assetUpdatedTransfer);
    }

    /**
     * @return void
     */
    public function testSaveAssetWhenTheAssetAlreadyExistsAndTheAssetTimestampIsOlderThanTheMessageTimestampThenTheAssetIsUpdated(): void
    {
        // Arrange
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $assetTransfer = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeOld,
        ]);

        $assetUpdatedTransfer = $this->tester->generateAssetUpdatedTransfer([
            AssetUpdatedTransfer::ASSET_IDENTIFIER => $assetTransfer->getAssetUuid(),
            AssetUpdatedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeNew,
            ],
        ]);

        // Act
        $this->tester->getFacade()->saveAsset($assetUpdatedTransfer);

        // Assert
        $this->tester->assertAssetUpdatedTransferAndAssetEntityAreEqual($assetUpdatedTransfer);
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

        $assetUpdatedTransfer = $this->tester->generateAssetUpdatedTransfer([
            AssetUpdatedTransfer::ASSET_IDENTIFIER => $expectedAsset->getAssetUuid(),
            AssetUpdatedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
                MessageAttributesTransfer::TIMESTAMP => $timeOld,
            ],
        ]);

        // Act
        $this->tester->getFacade()->updateAsset($assetUpdatedTransfer);

        // Assert
        $this->tester->assertAssetTransferAndAssetEntityAreEqual($expectedAsset);
    }

    /**
     * @return void
     */
    public function testSaveAssetWhenTheAssetAlreadyExistsAndTheAssetTimestampIsNewerThanTheMessageTimestampThenTheAssetIsNotUpdated(): void
    {
        // Arrange
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $expectedAsset = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeNew,
        ]);

        $assetUpdatedTransfer = $this->tester->generateAssetUpdatedTransfer([
            AssetUpdatedTransfer::ASSET_IDENTIFIER => $expectedAsset->getAssetUuid(),
            AssetUpdatedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeOld,
            ],
        ]);

        // Act
        $this->tester->getFacade()->saveAsset($assetUpdatedTransfer);

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

        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $assetFacadeMock->deleteAsset($assetDeletedTransfer);

        // Assert
        $assetEntity = SpyAssetQuery::create()->filterByAssetUuid($assetDeletedTransfer->getAssetIdentifierOrFail())->findOne();
        $this->assertNull($assetEntity);
    }

    /**
     * @return void
     */
    public function testRemoveAssetWhenTheAssetDoesNotExistAndIsActiveColumnNotPresentThenNoAssetIsCreated(): void
    {
        // Arrange
        $entityManagerMockedMethods = ['hasIsActiveColumn' => false];
        $assetFacadeMock = $this->getFacadeMockWithMockedEntitymanager($entityManagerMockedMethods);

        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [],
        ]);

        // Act
        $assetFacadeMock->removeAsset($assetDeletedTransfer);

        // Assert
        $assetEntity = SpyAssetQuery::create()->filterByAssetUuid($assetDeletedTransfer->getAssetIdentifierOrFail())->findOne();
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

        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $initialAsset->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $assetFacadeMock->deleteAsset($assetDeletedTransfer);

        // Assert
        $assetEntity = SpyAssetQuery::create()->filterByAssetUuid($initialAsset->getAssetUuid())->findOne();
        $this->assertNull($assetEntity);
    }

    /**
     * @return void
     */
    public function testRemoveAssetWhenTheAssetAlreadyExistsAndIsActiveColumnNotPresentAndTheMessageTimestampIsNullThenTheAssetIsDeleted(): void
    {
        // Arrange
        $entityManagerMockedMethods = ['hasIsActiveColumn' => false];
        $assetFacadeMock = $this->getFacadeMockWithMockedEntitymanager($entityManagerMockedMethods);

        $initialAsset = $this->tester->haveAsset([]);

        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $initialAsset->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [],
        ]);

        // Act
        $assetFacadeMock->removeAsset($assetDeletedTransfer);

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

        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $initialAsset->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
                MessageAttributesTransfer::TIMESTAMP => $timeNew,
            ],
        ]);

        // Act
        $assetFacadeMock->deleteAsset($assetDeletedTransfer);

        // Assert
        $assetEntity = SpyAssetQuery::create()->filterByAssetUuid($initialAsset->getAssetUuid())->findOne();
        $this->assertNull($assetEntity);
    }

    /**
     * @return void
     */
    public function testRemoveAssetWhenTheAssetAlreadyExistsAndIsActiveColumnNotPresentAndTheAssetTimestampIsOlderThanTheMessageTimestampThenTheAssetIsDeleted(): void
    {
        // Arrange
        $entityManagerMockedMethods = ['hasIsActiveColumn' => false];
        $assetFacadeMock = $this->getFacadeMockWithMockedEntitymanager($entityManagerMockedMethods);
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $initialAsset = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeOld,
        ]);

        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $initialAsset->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeNew,
            ],
        ]);

        // Act
        $assetFacadeMock->removeAsset($assetDeletedTransfer);

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

        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $expectedAsset->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
                MessageAttributesTransfer::TIMESTAMP => $timeOld,
            ],
        ]);

        // Act
        $assetFacadeMock->deleteAsset($assetDeletedTransfer);

        // Assert
        $this->tester->assertAssetTransferAndAssetEntityAreEqual($expectedAsset);
    }

    /**
     * @return void
     */
    public function testRemoveAssetWhenTheAssetAlreadyExistsAndIsActiveColumnNotPresentAndTheAssetTimestampIsNewerThanTheMessageTimestampThenTheAssetIsDeleted(): void
    {
        // Arrange
        $entityManagerMockedMethods = ['hasIsActiveColumn' => false];
        $assetFacadeMock = $this->getFacadeMockWithMockedEntitymanager($entityManagerMockedMethods);
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $expectedAsset = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeNew,
        ]);

        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $expectedAsset->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeOld,
            ],
        ]);

        // Act
        $assetFacadeMock->removeAsset($assetDeletedTransfer);

        // Assert
        $this->tester->assertAssetTransferAndAssetEntityAreEqual($expectedAsset);
    }

    /**
     * @return void
     */
    public function testDeleteAssetWhenTheAssetDoesNotExistThenAnInactiveAssetIsCreated(): void
    {
        // Arrange
        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->deleteAsset($assetDeletedTransfer);

        // Assert
        $this->tester->assertAssetDeletedTransferAndAssetEntityAreEqual($assetDeletedTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveAssetWhenTheAssetDoesNotExistThenAnInactiveAssetIsCreated(): void
    {
        // Arrange
        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [],
        ]);

        // Act
        $this->tester->getFacade()->removeAsset($assetDeletedTransfer);

        // Assert
        $this->tester->assertAssetDeletedTransferAndAssetEntityAreEqual($assetDeletedTransfer);
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

        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $initialAssetTransfer->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);
        $expectedAssetTransfer = clone $initialAssetTransfer;
        $expectedAssetTransfer->setIsActive(false);

        // Act
        $this->tester->getFacade()->deleteAsset($assetDeletedTransfer);

        // Assert
        $this->tester->assertAssetTransferAndAssetEntityAreEqualWithNewerTimestamp($expectedAssetTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveAssetWhenTheAssetAlreadyExistsAndTheMessageTimestampIsNullThenTheAssetIsSetInactive(): void
    {
        // Arrange
        $time = new DateTime('now', new DateTimeZone('UTC'));

        $initialAssetTransfer = $this->tester->haveAsset([
            AssetTransfer::IS_ACTIVE => true,
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $time->format(AssetTimeStamp::TIMESTAMP_FORMAT),
        ]);

        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $initialAssetTransfer->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [],
        ]);
        $expectedAssetTransfer = clone $initialAssetTransfer;
        $expectedAssetTransfer->setIsActive(false);

        // Act
        $this->tester->getFacade()->removeAsset($assetDeletedTransfer);

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

        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $initialAssetTransfer->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeNew,
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        $expectedAssetTransfer = clone $initialAssetTransfer;
        $expectedAssetTransfer->setIsActive(false);

        // Act
        $this->tester->getFacade()->deleteAsset($assetDeletedTransfer);

        // Assert
        $this->tester->assertAssetTransferAndAssetEntityAreEqualWithNewerTimestamp($expectedAssetTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveAssetWhenTheAssetAlreadyExistsAndTheAssetTimestampIsOlderThanTheMessageTimestampThenTheAssetIsSetInactive(): void
    {
        // Arrange
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $initialAssetTransfer = $this->tester->haveAsset([
            AssetTransfer::IS_ACTIVE => true,
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeOld,
        ]);

        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $initialAssetTransfer->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeNew,
            ],
        ]);

        $expectedAssetTransfer = clone $initialAssetTransfer;
        $expectedAssetTransfer->setIsActive(false);

        // Act
        $this->tester->getFacade()->removeAsset($assetDeletedTransfer);

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

        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $initialAssetTransfer->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeOld,
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->deleteAsset($assetDeletedTransfer);

        // Assert
        $this->tester->assertAssetTransferAndAssetEntityAreEqual($initialAssetTransfer);
    }

    /**
     * @return void
     */
    public function testRemoveAssetWhenTheAssetAlreadyExistsAndTheAssetTimestampIsNewerThanTheMessageTimestampThenTheAssetIsNotModified(): void
    {
        // Arrange
        [$timeOld, $timeNew] = $this->tester->createOldAndNewDateTime();

        $initialAssetTransfer = $this->tester->haveAsset([
            AssetTransfer::IS_ACTIVE => true,
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => $timeNew,
        ]);

        $assetDeletedTransfer = $this->tester->generateAssetDeletedTransfer([
            AssetDeletedTransfer::ASSET_IDENTIFIER => $initialAssetTransfer->getAssetUuid(),
            AssetDeletedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::TIMESTAMP => $timeOld,
            ],
        ]);

        // Act
        $this->tester->getFacade()->removeAsset($assetDeletedTransfer);

        // Assert
        $this->tester->assertAssetTransferAndAssetEntityAreEqual($initialAssetTransfer);
    }

    /**
     * @return void
     */
    public function testAddAssetWhenTheAssetAlreadyExistsAndTheAssetLastMessageTimestampIsNullThenTheAssetIsUpdated(): void
    {
        // Arrange
        $assetTransfer = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => null,
        ]);

        $assetAddedTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::ASSET_IDENTIFIER => $assetTransfer->getAssetUuid(),
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [
                MessageAttributesTransfer::STORE_REFERENCE => static::STORE_REFERENCE,
            ],
        ]);

        // Act
        $this->tester->getFacade()->addAsset($assetAddedTransfer);

        // Assert
        $this->tester->assertAssetAddedTransferAndAssetEntityAreEqual($assetAddedTransfer);
    }

    /**
     * @return void
     */
    public function testCreateAssetWhenTheAssetAlreadyExistsAndTheAssetLastMessageTimestampIsNullThenTheAssetIsUpdated(): void
    {
        // Arrange
        $assetTransfer = $this->tester->haveAsset([
            AssetTransfer::LAST_MESSAGE_TIMESTAMP => null,
        ]);

        $assetAddedTransfer = $this->tester->generateAssetAddedTransfer([
            AssetAddedTransfer::ASSET_IDENTIFIER => $assetTransfer->getAssetUuid(),
            AssetAddedTransfer::MESSAGE_ATTRIBUTES => [],
        ]);

        // Act
        $this->tester->getFacade()->createAsset($assetAddedTransfer);

        // Assert
        $this->tester->assertAssetAddedTransferAndAssetEntityAreEqual($assetAddedTransfer);
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

    /**
     * @return void
     */
    public function testRefreshAllAssetStoreRelationsIfNewStoreWasAddedAppendsThisStoreIntoAssetStoreRelations(): void
    {
        if (!$this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('This test is not applicable for non-dynamic stores.');
        }

        // Arrange
        $assetTransfer = $this->tester->haveAsset();

        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'test-store',
        ]);

        $this->tester->assertAssetStoreRelationDoesNotExist($assetTransfer->getIdAsset(), $storeTransfer->getIdStore());

        // Act
        $this->tester->getFacade()->refreshAllAssetStoreRelations();

        // Assert
        $this->tester->assertAssetStoreRelationExists($assetTransfer->getIdAsset(), $storeTransfer->getIdStore());
    }

    /**
     * @return void
     */
    public function testRefreshAllAssetStoreRelationsDoesNothingToExistingRelationsIfNoStoreWasAdded(): void
    {
        if (!$this->tester->isDynamicStoreEnabled()) {
            $this->markTestSkipped('This test is not applicable for non-dynamic stores.');
        }

        // Arrange
        $assetTransfer = $this->tester->haveAsset();

        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'test-store',
        ]);

        $this->tester->haveAssetStoreRelation($assetTransfer->getIdAsset(), $storeTransfer->getIdStore());

        $this->tester->assertAssetStoreRelationExists($assetTransfer->getIdAsset(), $storeTransfer->getIdStore());

        // Act
        $this->tester->getFacade()->refreshAllAssetStoreRelations();

        // Assert
        $this->tester->assertAssetStoreRelationExists($assetTransfer->getIdAsset(), $storeTransfer->getIdStore());
    }
}
