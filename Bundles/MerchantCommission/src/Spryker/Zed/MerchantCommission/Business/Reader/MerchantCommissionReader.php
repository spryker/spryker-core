<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Reader;

use Generated\Shared\Transfer\MerchantCommissionCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer;
use Spryker\Zed\MerchantCommission\Business\Expander\MerchantCommissionRelationExpanderInterface;
use Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface;

class MerchantCommissionReader implements MerchantCommissionReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface
     */
    protected MerchantCommissionRepositoryInterface $merchantCommissionRepository;

    /**
     * @var \Spryker\Zed\MerchantCommission\Business\Expander\MerchantCommissionRelationExpanderInterface
     */
    protected MerchantCommissionRelationExpanderInterface $merchantCommissionExpander;

    /**
     * @param \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface $merchantCommissionRepository
     * @param \Spryker\Zed\MerchantCommission\Business\Expander\MerchantCommissionRelationExpanderInterface $merchantCommissionExpander
     */
    public function __construct(
        MerchantCommissionRepositoryInterface $merchantCommissionRepository,
        MerchantCommissionRelationExpanderInterface $merchantCommissionExpander
    ) {
        $this->merchantCommissionRepository = $merchantCommissionRepository;
        $this->merchantCommissionExpander = $merchantCommissionExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionTransfer
     */
    public function getMerchantCommissionCollection(
        MerchantCommissionCriteriaTransfer $merchantCommissionCriteriaTransfer
    ): MerchantCommissionCollectionTransfer {
        $merchantCommissionCollectionTransfer = $this->merchantCommissionRepository->getMerchantCommissionCollection(
            $merchantCommissionCriteriaTransfer,
        );

        if ($merchantCommissionCollectionTransfer->getMerchantCommissions()->count() === 0) {
            return $merchantCommissionCollectionTransfer;
        }

        return $this->merchantCommissionExpander->expandMerchantCommissionCollection(
            $merchantCommissionCollectionTransfer,
            $merchantCommissionCriteriaTransfer,
        );
    }
}
