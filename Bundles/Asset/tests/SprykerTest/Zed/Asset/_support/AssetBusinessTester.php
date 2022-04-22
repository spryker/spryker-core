<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Asset;

use Codeception\Actor;
use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\AssetDeletedTransfer;
use Generated\Shared\Transfer\AssetTransfer;
use Generated\Shared\Transfer\AssetUpdatedTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Generated\Shared\Transfer\PublisherTransfer;
use Ramsey\Uuid\Uuid;

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
     * @var string
     */
    public const STORE_REFERENCE = 'dev-DE';

    /**
     * @param string $storeReference
     * @param string $assetSlot
     * @param string $assetUuid
     *
     * @return \Generated\Shared\Transfer\AssetAddedTransfer
     */
    public function buildAssetAddedTransfer(string $storeReference, string $assetSlot, string $assetUuid): AssetAddedTransfer
    {
        return (new AssetAddedTransfer())
            ->setAssetName('test')
            ->setAssetView('<script>')
            ->setAssetIdentifier($assetUuid)
            ->setAssetSlot($assetSlot)
            ->setMessageAttributes(
                (new MessageAttributesTransfer())
                    ->setPublisher($this->havePublisherTransfer())
                    ->setStoreReference($storeReference),
            );
    }

    /**
     * @param string $storeReference
     * @param string $assetSlot
     * @param string|null $assetUuid
     * @param string|null $assetView
     *
     * @return \Generated\Shared\Transfer\AssetUpdatedTransfer
     */
    public function buildAssetUpdatedTransfer(
        string $storeReference,
        string $assetSlot = 'test',
        ?string $assetUuid = null,
        ?string $assetView = '<script>'
    ): AssetUpdatedTransfer {
        $assetUuid = $assetUuid ?: $this->getUuid();

        return (new AssetUpdatedTransfer())
            ->setAssetView($assetView)
            ->setAssetIdentifier($assetUuid)
            ->setAssetSlot($assetSlot)
            ->setMessageAttributes(
                (new MessageAttributesTransfer())
                    ->setPublisher($this->havePublisherTransfer())
                    ->setStoreReference($storeReference),
            );
    }

    /**
     * @param string $storeReference
     * @param string|null $assetUuid
     *
     * @return \Generated\Shared\Transfer\AssetDeletedTransfer
     */
    public function buildAssetDeletedTransfer(
        string $storeReference,
        ?string $assetUuid = null
    ): AssetDeletedTransfer {
        $assetUuid = $assetUuid ?: $this->getUuid();

        return (new AssetDeletedTransfer())
            ->setAssetIdentifier($assetUuid)
            ->setMessageAttributes(
                (new MessageAttributesTransfer())
                    ->setPublisher($this->havePublisherTransfer())
                    ->setStoreReference($storeReference),
            );
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @return \Generated\Shared\Transfer\PublisherTransfer
     */
    public function havePublisherTransfer(): PublisherTransfer
    {
        return (new PublisherTransfer())->setAppIdentifier($this->getUuid());
    }

    /**
     * @param string $assetContent
     * @param string $assetUuid
     *
     * @return \Generated\Shared\Transfer\AssetTransfer
     */
    public function buildAssetTransfer(string $assetContent, string $assetUuid): AssetTransfer
    {
        return (new AssetTransfer())
            ->setAssetSlot('slt-footer')
            ->setAssetName('test')
            ->setAssetContent($assetContent)
            ->setIdAsset(1)
            ->setAssetUuid($assetUuid);
    }
}
