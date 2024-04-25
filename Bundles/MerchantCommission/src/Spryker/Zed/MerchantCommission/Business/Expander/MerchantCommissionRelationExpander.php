<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Expander;

use Generated\Shared\Transfer\MerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer;

class MerchantCommissionRelationExpander implements MerchantCommissionRelationExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Expander\MerchantCommissionExpanderInterface
     */
    protected MerchantCommissionExpanderInterface $merchantCommissionAmountExpander;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Expander\MerchantCommissionStoreRelationExpanderInterface
     */
    protected MerchantCommissionStoreRelationExpanderInterface $merchantCommissionStoreRelationExpander;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Expander\MerchantCommissionMerchantRelationExpanderInterface
     */
    protected MerchantCommissionMerchantRelationExpanderInterface $merchantCommissionMerchantRelationExpander;

    /**
     * @param \Spryker\Zed\MerchantCommission\Business\Expander\MerchantCommissionExpanderInterface $merchantCommissionAmountExpander
     * @param \Spryker\Zed\MerchantCommission\Business\Expander\MerchantCommissionStoreRelationExpanderInterface $merchantCommissionStoreRelationExpander
     * @param \Spryker\Zed\MerchantCommission\Business\Expander\MerchantCommissionMerchantRelationExpanderInterface $merchantCommissionMerchantRelationExpander
     */
    public function __construct(
        MerchantCommissionExpanderInterface $merchantCommissionAmountExpander,
        MerchantCommissionStoreRelationExpanderInterface $merchantCommissionStoreRelationExpander,
        MerchantCommissionMerchantRelationExpanderInterface $merchantCommissionMerchantRelationExpander
    ) {
        $this->merchantCommissionAmountExpander = $merchantCommissionAmountExpander;
        $this->merchantCommissionStoreRelationExpander = $merchantCommissionStoreRelationExpander;
        $this->merchantCommissionMerchantRelationExpander = $merchantCommissionMerchantRelationExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer $merchantCommissionCollectionTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer
     */
    public function expandMerchantCommissionCollection(
        MerchantCommissionCollectionTransfer $merchantCommissionCollectionTransfer,
        MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
    ): MerchantCommissionCollectionTransfer {
        if (!$this->isExpandingRequired($merchantCommissionCriteriaTransfer)) {
            return $merchantCommissionCollectionTransfer;
        }

        $merchantCommissionConditionsTransfer = $merchantCommissionCriteriaTransfer->getMerchantCommissionConditionsOrFail();

        if ($merchantCommissionConditionsTransfer->getWithCommissionMerchantAmountRelations()) {
            $merchantCommissionCollectionTransfer = $this->merchantCommissionAmountExpander->expandMerchantCommissionCollectionWithMerchantCommissionAmounts(
                $merchantCommissionCollectionTransfer,
            );
        }

        if ($merchantCommissionConditionsTransfer->getWithStoreRelations()) {
            $merchantCommissionCollectionTransfer = $this->merchantCommissionStoreRelationExpander->expandMerchantCommissionCollectionWithStores(
                $merchantCommissionCollectionTransfer,
            );
        }

        if ($merchantCommissionConditionsTransfer->getWithMerchantRelations()) {
            $merchantCommissionCollectionTransfer = $this->merchantCommissionMerchantRelationExpander->expandMerchantCommissionCollectionWithMerchants(
                $merchantCommissionCollectionTransfer,
            );
        }

        return $merchantCommissionCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
     *
     * @return bool
     */
    protected function isExpandingRequired(MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer): bool
    {
        $merchantCommissionConditionsTransfer = $merchantCommissionCriteriaTransfer->getMerchantCommissionConditions();
        if ($merchantCommissionConditionsTransfer === null) {
            return false;
        }

        return $merchantCommissionConditionsTransfer->getWithStoreRelations()
            || $merchantCommissionConditionsTransfer->getWithMerchantRelations()
            || $merchantCommissionConditionsTransfer->getWithCommissionMerchantAmountRelations();
    }
}
