<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\Asset\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\AssetDeletedBuilder;
use Generated\Shared\Transfer\AssetDeletedTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Ramsey\Uuid\Uuid;
use SprykerTest\Shared\Store\Helper\StoreReferenceHelper;

/**
 * This Helper is used for the AssetDeleted message that will be published to this application.
 */
class AssetCommandsAssetDeletedHelper extends Module
{
    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\AssetDeletedTransfer
     */
    public function haveAssetDeletedTransfer(array $seed): AssetDeletedTransfer
    {
        $assetUpdatedTransfer = (new AssetDeletedBuilder($seed))->build();

        /**
         * @deprecated Apparently, we need to set the storeReference manually. This MUST be refactored
         */
        $storeReference = Uuid::uuid4()->toString();
        /** @var \SprykerTest\Shared\Store\Helper\StoreReferenceHelper $storeReferenceHelper */
        $storeReferenceHelper = $this->getModule('\\' . StoreReferenceHelper::class);
        $storeReferenceHelper->setStoreReferenceData(['DE' => $storeReference]);

        $messageAttributesTransfer = new MessageAttributesTransfer();
        $messageAttributesTransfer->setStoreReference($storeReference);

        $assetUpdatedTransfer->setMessageAttributes($messageAttributesTransfer);

        return $assetUpdatedTransfer;
    }
}
