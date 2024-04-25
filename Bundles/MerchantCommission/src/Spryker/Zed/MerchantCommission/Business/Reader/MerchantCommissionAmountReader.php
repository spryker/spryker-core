<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Reader;

use Generated\Shared\Transfer\MerchantCommissionAmountCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionAmountConditionsTransfer;
use Generated\Shared\Transfer\MerchantCommissionAmountCriteriaTransfer;
use Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface;

class MerchantCommissionAmountReader implements MerchantCommissionAmountReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface
     */
    protected MerchantCommissionRepositoryInterface $merchantCommissionRepository;

    /**
     * @param \Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface $merchantCommissionRepository
     */
    public function __construct(MerchantCommissionRepositoryInterface $merchantCommissionRepository)
    {
        $this->merchantCommissionRepository = $merchantCommissionRepository;
    }

    /**
     * @param list<int> $merchantCommissionIds
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionAmountCollectionTransfer
     */
    public function getMerchantCommissionAmountCollectionByMerchantCommissionIds(
        array $merchantCommissionIds
    ): MerchantCommissionAmountCollectionTransfer {
        $merchantCommissionAmountConditionsTransfer = (new MerchantCommissionAmountConditionsTransfer())->setMerchantCommissionIds($merchantCommissionIds);
        $merchantCommissionAmountCriteriaTransfer = (new MerchantCommissionAmountCriteriaTransfer())->setMerchantCommissionAmountConditions(
            $merchantCommissionAmountConditionsTransfer,
        );

        return $this->merchantCommissionRepository->getMerchantCommissionAmountCollection(
            $merchantCommissionAmountCriteriaTransfer,
        );
    }
}
