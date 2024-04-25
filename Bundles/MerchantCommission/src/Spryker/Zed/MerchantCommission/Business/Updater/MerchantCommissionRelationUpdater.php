<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Updater;

use Generated\Shared\Transfer\MerchantCommissionTransfer;

class MerchantCommissionRelationUpdater implements MerchantCommissionRelationUpdaterInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Updater\MerchantCommissionAmountUpdaterInterface
     */
    protected MerchantCommissionAmountUpdaterInterface $merchantCommissionAmountUpdater;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Updater\MerchantCommissionMerchantUpdaterInterface
     */
    protected MerchantCommissionMerchantUpdaterInterface $merchantCommissionMerchantUpdater;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Updater\MerchantCommissionStoreUpdaterInterface
     */
    protected MerchantCommissionStoreUpdaterInterface $merchantCommissionStoreUpdater;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Updater\MerchantCommissionAmountUpdaterInterface $merchantCommissionAmountUpdater
     * @param \Spryker\Zed\MerchantCommission\Business\Updater\MerchantCommissionMerchantUpdaterInterface $merchantCommissionMerchantUpdater
     * @param \Spryker\Zed\MerchantCommission\Business\Updater\MerchantCommissionStoreUpdaterInterface $merchantCommissionStoreUpdater
     */
    public function __construct(
        MerchantCommissionAmountUpdaterInterface $merchantCommissionAmountUpdater,
        MerchantCommissionMerchantUpdaterInterface $merchantCommissionMerchantUpdater,
        MerchantCommissionStoreUpdaterInterface $merchantCommissionStoreUpdater
    ) {
        $this->merchantCommissionAmountUpdater = $merchantCommissionAmountUpdater;
        $this->merchantCommissionMerchantUpdater = $merchantCommissionMerchantUpdater;
        $this->merchantCommissionStoreUpdater = $merchantCommissionStoreUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function updateMerchantCommissionRelations(
        MerchantCommissionTransfer $merchantCommissionTransfer
    ): MerchantCommissionTransfer {
        if ($merchantCommissionTransfer->getMerchantCommissionAmounts()->count() !== 0) {
            $merchantCommissionTransfer = $this->merchantCommissionAmountUpdater->updateMerchantCommissionAmounts($merchantCommissionTransfer);
        }

        if ($merchantCommissionTransfer->getMerchants()->count() !== 0) {
            $merchantCommissionTransfer = $this->merchantCommissionMerchantUpdater->updateMerchantCommissionMerchantRelations($merchantCommissionTransfer);
        }

        if ($merchantCommissionTransfer->getStoreRelation() && $merchantCommissionTransfer->getStoreRelationOrFail()->getStores()->count() !== 0) {
            $merchantCommissionTransfer = $this->merchantCommissionStoreUpdater->updateMerchantCommissionStoreRelations($merchantCommissionTransfer);
        }

        return $merchantCommissionTransfer;
    }
}
