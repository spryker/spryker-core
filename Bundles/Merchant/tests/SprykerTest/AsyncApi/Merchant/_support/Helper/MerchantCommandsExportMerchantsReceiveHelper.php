<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\AsyncApi\Merchant\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ExportMerchantsBuilder;
use Generated\Shared\Transfer\ExportMerchantsTransfer;
use Generated\Shared\Transfer\MessageAttributesTransfer;
use Ramsey\Uuid\Uuid;
use SprykerTest\Shared\Store\Helper\StoreReferenceHelper;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class MerchantCommandsExportMerchantsReceiveHelper extends Module
{
    use DataCleanupHelperTrait;

    /**
     * @param array<string, mixed> $seed
     *
     * @return \Generated\Shared\Transfer\ExportMerchantsTransfer
     */
    public function haveExportMerchantsTransfer(array $seed = []): ExportMerchantsTransfer
    {
        $exportMerchantsTransfer = (new ExportMerchantsBuilder($seed))->build();

        /**
         * @deprecated Apparently, we need to set the storeReference manually. This MUST be refactored.
         */
        $storeReference = Uuid::uuid4()->toString();
        /** @var \SprykerTest\Shared\Store\Helper\StoreReferenceHelper $storeReferenceHelper */
        $storeReferenceHelper = $this->getModule('\\' . StoreReferenceHelper::class);
        $storeReferenceHelper->setStoreReferenceData(['DE' => $storeReference]);

        $messageAttributesTransfer = new MessageAttributesTransfer();
        $messageAttributesTransfer->setStoreReference($storeReference);

        $exportMerchantsTransfer->setMessageAttributes($messageAttributesTransfer);

        return $exportMerchantsTransfer;
    }
}
