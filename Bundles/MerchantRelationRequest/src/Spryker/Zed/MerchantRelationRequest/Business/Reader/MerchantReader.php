<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Reader;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantFacadeInterface;
use Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantFacadeInterface
     */
    protected MerchantRelationRequestToMerchantFacadeInterface $merchantFacade;

    /**
     * @var \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig
     */
    protected MerchantRelationRequestConfig $merchantRelationRequestConfig;

    /**
     * @param \Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantFacadeInterface $merchantFacade
     * @param \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig $merchantRelationRequestConfig
     */
    public function __construct(
        MerchantRelationRequestToMerchantFacadeInterface $merchantFacade,
        MerchantRelationRequestConfig $merchantRelationRequestConfig
    ) {
        $this->merchantFacade = $merchantFacade;
        $this->merchantRelationRequestConfig = $merchantRelationRequestConfig;
    }

    /**
     * @param list<int> $merchantIds
     *
     * @return array<int, \Generated\Shared\Transfer\MerchantTransfer>
     */
    public function getMerchantsIndexedByIdMerchant(array $merchantIds): array
    {
        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())
            ->setMerchantIds($merchantIds)
            ->setIsActive(true)
            ->setStatus($this->merchantRelationRequestConfig->getMerchantStatusApproved());

        $merchantTransfers = $this->merchantFacade
            ->get($merchantCriteriaTransfer)
            ->getMerchants();

        $indexedMerchantTransfers = [];
        foreach ($merchantTransfers as $merchantTransfer) {
            $indexedMerchantTransfers[$merchantTransfer->getIdMerchantOrFail()] = $merchantTransfer;
        }

        return $indexedMerchantTransfers;
    }
}
