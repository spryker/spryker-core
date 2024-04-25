<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Creator;

use Generated\Shared\Transfer\MerchantCommissionTransfer;

class MerchantCommissionRelationCreator implements MerchantCommissionRelationCreatorInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Creator\MerchantCommissionAmountCreatorInterface
     */
    protected MerchantCommissionAmountCreatorInterface $merchantCommissionAmountCreator;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Creator\MerchantCommissionMerchantCreatorInterface
     */
    protected MerchantCommissionMerchantCreatorInterface $merchantCommissionMerchantCreator;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Creator\MerchantCommissionStoreCreatorInterface
     */
    protected MerchantCommissionStoreCreatorInterface $merchantCommissionStoreCreator;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Creator\MerchantCommissionAmountCreatorInterface $merchantCommissionAmountCreator
     * @param \Spryker\Zed\MerchantCommission\Business\Creator\MerchantCommissionMerchantCreatorInterface $merchantCommissionMerchantCreator
     * @param \Spryker\Zed\MerchantCommission\Business\Creator\MerchantCommissionStoreCreatorInterface $merchantCommissionStoreCreator
     */
    public function __construct(
        MerchantCommissionAmountCreatorInterface $merchantCommissionAmountCreator,
        MerchantCommissionMerchantCreatorInterface $merchantCommissionMerchantCreator,
        MerchantCommissionStoreCreatorInterface $merchantCommissionStoreCreator
    ) {
        $this->merchantCommissionAmountCreator = $merchantCommissionAmountCreator;
        $this->merchantCommissionMerchantCreator = $merchantCommissionMerchantCreator;
        $this->merchantCommissionStoreCreator = $merchantCommissionStoreCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionTransfer
     */
    public function createMerchantCommissionRelations(MerchantCommissionTransfer $merchantCommissionTransfer): MerchantCommissionTransfer
    {
        if ($merchantCommissionTransfer->getMerchantCommissionAmounts()->count() !== 0) {
            $this->merchantCommissionAmountCreator->createMerchantCommissionAmounts($merchantCommissionTransfer);
        }

        if ($merchantCommissionTransfer->getMerchants()->count() !== 0) {
            $this->merchantCommissionMerchantCreator->createMerchantCommissionMerchantRelations($merchantCommissionTransfer);
        }

        if ($merchantCommissionTransfer->getStoreRelation() && $merchantCommissionTransfer->getStoreRelationOrFail()->getStores()->count() !== 0) {
            $this->merchantCommissionStoreCreator->createMerchantCommissionStoreRelations($merchantCommissionTransfer);
        }

        return $merchantCommissionTransfer;
    }
}
