<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Updater;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Spryker\Zed\MerchantRelationRequestGui\Dependency\Facade\MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface;

class MerchantRelationRequestUpdater implements MerchantRelationRequestUpdaterInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationRequestGui\Dependency\Facade\MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface
     */
    protected MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade;

    /**
     * @param \Spryker\Zed\MerchantRelationRequestGui\Dependency\Facade\MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade
     */
    public function __construct(
        MerchantRelationRequestGuiToMerchantRelationRequestFacadeInterface $merchantRelationRequestFacade
    ) {
        $this->merchantRelationRequestFacade = $merchantRelationRequestFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer
     */
    public function updateMerchantRelationRequest(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer
    ): MerchantRelationRequestCollectionResponseTransfer {
        $merchantRelationRequestCollectionRequestTransfer = (new MerchantRelationRequestCollectionRequestTransfer())
            ->addMerchantRelationRequest($merchantRelationRequestTransfer)
            ->setIsTransactional(true);

        return $this->merchantRelationRequestFacade
            ->updateMerchantRelationRequestCollection($merchantRelationRequestCollectionRequestTransfer);
    }
}
