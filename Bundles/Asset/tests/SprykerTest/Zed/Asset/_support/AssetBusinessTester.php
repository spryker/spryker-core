<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Asset;

use Codeception\Actor;
use DateTime;
use DateTimeZone;
use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetDeletedTransfer;
use Generated\Shared\Transfer\AssetTransfer;
use Generated\Shared\Transfer\AssetUpdatedTransfer;
use Orm\Zed\Asset\Persistence\SpyAssetQuery;
use Spryker\Zed\Asset\Business\TimeStamp\AssetTimeStamp;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class AssetBusinessTester extends Actor
{
    use _generated\AssetBusinessTesterActions;

    /**
     * @return array {string, string}
     */
    public function createOldAndNewDateTime(): array
    {
        $timeOld = new DateTime('now', new DateTimeZone('UTC'));
        $timeNew = new DateTime('now', new DateTimeZone('UTC'));
        $timeNew->modify('+50 microseconds');

        return [$timeOld->format(AssetTimeStamp::TIMESTAMP_FORMAT), $timeNew->format(AssetTimeStamp::TIMESTAMP_FORMAT)];
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return void
     */
    public function assertAssetTransferAndAssetEntityAreEqual(AssetTransfer $assetTransfer): void
    {
        $assetEntity = SpyAssetQuery::create()->filterByAssetUuid($assetTransfer->getAssetUuid())->findOne();

        $this->assertEquals($assetTransfer->getAssetContent(), $assetEntity->getAssetContent());
        $this->assertEquals($assetTransfer->getIsActive(), $assetEntity->getIsActive());
        $this->assertEquals($assetTransfer->getAssetSlot(), $assetEntity->getAssetSlot());
        $this->assertEquals($assetTransfer->getLastMessageTimestamp(), $assetEntity->getLastMessageTimestamp()->format(AssetTimeStamp::TIMESTAMP_FORMAT));
    }

    /**
     * @param \Generated\Shared\Transfer\AssetTransfer $assetTransfer
     *
     * @return void
     */
    public function assertAssetTransferAndAssetEntityAreEqualWithNewerTimestamp(AssetTransfer $assetTransfer): void
    {
        $assetEntity = SpyAssetQuery::create()->filterByAssetUuid($assetTransfer->getAssetUuid())->findOne();

        $this->assertEquals($assetTransfer->getAssetContent(), $assetEntity->getAssetContent());
        $this->assertEquals($assetTransfer->getIsActive(), $assetEntity->getIsActive());
        $this->assertEquals($assetTransfer->getAssetSlot(), $assetEntity->getAssetSlot());
        $this->assertGreaterThan($assetTransfer->getLastMessageTimestamp(), $assetEntity->getLastMessageTimestamp()->format(AssetTimeStamp::TIMESTAMP_FORMAT));
    }

    /**
     * @param \Generated\Shared\Transfer\AssetAddedTransfer $assetAddedTransfer
     *
     * @return void
     */
    public function assertAssetAddedTransferAndAssetEntityAreEqual(AssetAddedTransfer $assetAddedTransfer): void
    {
        $assetEntity = SpyAssetQuery::create()->filterByAssetUuid($assetAddedTransfer->getAssetIdentifierOrFail())->findOne();

        $this->assertEquals($assetAddedTransfer->getAssetview(), $assetEntity->getAssetContent());
        $this->assertTrue($assetEntity->getIsActive());
        $this->assertEquals($assetAddedTransfer->getAssetSlot(), $assetEntity->getAssetSlot());
        $this->assertEquals($assetAddedTransfer->getMessageAttributesOrFail()->getTimestampOrFail(), $assetEntity->getLastMessageTimestamp()->format(AssetTimeStamp::TIMESTAMP_FORMAT));
    }

    /**
     * @param \Generated\Shared\Transfer\AssetUpdatedTransfer $assetUpdatedTransfer
     *
     * @return void
     */
    public function assertAssetUpdatedTransferAndAssetEntityAreEqual(AssetUpdatedTransfer $assetUpdatedTransfer): void
    {
        $assetEntity = SpyAssetQuery::create()->filterByAssetUuid($assetUpdatedTransfer->getAssetIdentifierOrFail())->findOne();

        $this->assertEquals($assetUpdatedTransfer->getAssetview(), $assetEntity->getAssetContent());
        $this->assertTrue($assetEntity->getIsActive());
        $this->assertEquals($assetUpdatedTransfer->getAssetSlot(), $assetEntity->getAssetSlot());
        $this->assertEquals($assetUpdatedTransfer->getMessageAttributesOrFail()->getTimestampOrFail(), $assetEntity->getLastMessageTimestamp()->format(AssetTimeStamp::TIMESTAMP_FORMAT));
    }

    /**
     * @param \Generated\Shared\Transfer\AssetDeletedTransfer $assetDeletedTransfer
     *
     * @return void
     */
    public function assertAssetDeletedTransferAndAssetEntityAreEqual(AssetDeletedTransfer $assetDeletedTransfer): void
    {
        $assetEntity = SpyAssetQuery::create()->filterByAssetUuid($assetDeletedTransfer->getAssetIdentifierOrFail())->findOne();

        $this->assertEquals($assetDeletedTransfer->getAssetView(), $assetEntity->getAssetContent());
        $this->assertFalse($assetEntity->getIsActive());
        $this->assertEquals($assetDeletedTransfer->getAssetSlot(), $assetEntity->getAssetSlot());
        $this->assertEquals($assetDeletedTransfer->getMessageAttributesOrFail()->getTimestampOrFail(), $assetEntity->getLastMessageTimestamp()->format(AssetTimeStamp::TIMESTAMP_FORMAT));
    }
}
