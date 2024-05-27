<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Reader;

use Generated\Shared\Transfer\MerchantCommissionGroupCollectionTransfer;
use Generated\Shared\Transfer\MerchantCommissionGroupConditionsTransfer;
use Generated\Shared\Transfer\MerchantCommissionGroupCriteriaTransfer;
use Spryker\Zed\MerchantCommission\Persistence\MerchantCommissionRepositoryInterface;

class MerchantCommissionGroupReader implements MerchantCommissionGroupReaderInterface
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
     * @param list<string> $uuids
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionGroupCollectionTransfer
     */
    public function getMerchantCommissionGroupCollectionByUuids(array $uuids): MerchantCommissionGroupCollectionTransfer
    {
        $merchantCommissionGroupConditionsTransfer = (new MerchantCommissionGroupConditionsTransfer())->setUuids($uuids);
        $merchantCommissionGroupCriteriaTransfer = (new MerchantCommissionGroupCriteriaTransfer())->setMerchantCommissionGroupConditions(
            $merchantCommissionGroupConditionsTransfer,
        );

        return $this->merchantCommissionRepository->getMerchantCommissionGroupCollection(
            $merchantCommissionGroupCriteriaTransfer,
        );
    }

    /**
     * @param list<string> $merchantCommissionGroupKeys
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionGroupCollectionTransfer
     */
    public function getMerchantCommissionGroupCollectionByKeys(array $merchantCommissionGroupKeys): MerchantCommissionGroupCollectionTransfer
    {
        $merchantCommissionGroupConditionsTransfer = (new MerchantCommissionGroupConditionsTransfer())->setKeys($merchantCommissionGroupKeys);
        $merchantCommissionGroupCriteriaTransfer = (new MerchantCommissionGroupCriteriaTransfer())->setMerchantCommissionGroupConditions(
            $merchantCommissionGroupConditionsTransfer,
        );

        return $this->merchantCommissionRepository->getMerchantCommissionGroupCollection(
            $merchantCommissionGroupCriteriaTransfer,
        );
    }
}
