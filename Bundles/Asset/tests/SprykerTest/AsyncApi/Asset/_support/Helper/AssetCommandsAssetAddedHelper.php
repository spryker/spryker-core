<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\Asset\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\AssetAddedBuilder;
use Generated\Shared\Transfer\AssetAddedTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Ramsey\Uuid\Uuid;
use SprykerTest\Shared\Asset\Helper\AssetDataHelperTrait;
use SprykerTest\Shared\Store\Helper\StoreReferenceHelper;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

/**
 * This Helper is used for the AssetAdded message that will be published to this application.
 */
class AssetCommandsAssetAddedHelper extends Module
{
    use DataCleanupHelperTrait;
    use AssetDataHelperTrait;

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\AssetAddedTransfer
     */
    public function haveAssetAddedTransfer(array $seed = []): AssetAddedTransfer
    {
        $assetAddedTransfer = (new AssetAddedBuilder($seed))->build();

        /**
         * @deprecated Apparently, we need to set the storeReference manually. This MUST be refactored.
         */
        $storeReference = Uuid::uuid4()->toString();
        /** @var \SprykerTest\Shared\Store\Helper\StoreReferenceHelper $storeReferenceHelper */
        $storeReferenceHelper = $this->getModule('\\' . StoreReferenceHelper::class);
        $storeReferenceHelper->setStoreReferenceData(['DE' => $storeReference]);

        $messageAttributesTransfer = new MessageAttributesTransfer();
        $messageAttributesTransfer->setStoreReference($storeReference);

        $assetAddedTransfer->setMessageAttributes($messageAttributesTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($assetAddedTransfer): void {
            $this->getAssetDataHelper()->removeAsset($assetAddedTransfer->getAssetIdentifier());
        });

        return $assetAddedTransfer;
    }
}
