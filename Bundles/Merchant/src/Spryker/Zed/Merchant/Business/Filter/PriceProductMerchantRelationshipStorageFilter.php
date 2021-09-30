<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer;
use Spryker\Zed\Merchant\Business\Reader\MerchantReaderInterface;

class PriceProductMerchantRelationshipStorageFilter implements PriceProductMerchantRelationshipStorageFilterInterface
{
    /**
     * @var \Spryker\Zed\Merchant\Business\Reader\MerchantReaderInterface
     */
    protected $merchantReader;

    /**
     * @param \Spryker\Zed\Merchant\Business\Reader\MerchantReaderInterface $merchantReader
     */
    public function __construct(MerchantReaderInterface $merchantReader)
    {
        $this->merchantReader = $merchantReader;
    }

    /**
     * @phpstan-return array<int, \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer|null>
     *
     * @param array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer> $priceProductMerchantRelationshipStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer>
     */
    public function filterByMerchant(array $priceProductMerchantRelationshipStorageTransfers): array
    {
        $activeMerchantIds = $this->getActiveMerchantIds($priceProductMerchantRelationshipStorageTransfers);

        if (empty($activeMerchantIds)) {
            return [];
        }

        $priceProductMerchantRelationshipStorageTransfers = array_map(function (PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer) use ($activeMerchantIds) {
            return $this->getPriceProductMerchantRelationshipStorageTransferWithFilteredUngroupedPricesByMerchantActive(
                $priceProductMerchantRelationshipStorageTransfer,
                $activeMerchantIds
            );
        }, $priceProductMerchantRelationshipStorageTransfers);

        return array_filter($priceProductMerchantRelationshipStorageTransfers, function (PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer) {
            return $priceProductMerchantRelationshipStorageTransfer->getUngroupedPrices()->count() > 0;
        });
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer
     * @param array<int> $activeMerchantIds
     *
     * @return \Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer|null
     */
    protected function getPriceProductMerchantRelationshipStorageTransferWithFilteredUngroupedPricesByMerchantActive(
        PriceProductMerchantRelationshipStorageTransfer $priceProductMerchantRelationshipStorageTransfer,
        array $activeMerchantIds
    ): ?PriceProductMerchantRelationshipStorageTransfer {
        $filteredPriceProductMerchantRelationshipValueTransfers = new ArrayObject();
        foreach ($priceProductMerchantRelationshipStorageTransfer->getUngroupedPrices() as $priceProductMerchantRelationshipValueTransfer) {
            if (!in_array($priceProductMerchantRelationshipValueTransfer->getFkMerchantOrFail(), $activeMerchantIds)) {
                continue;
            }
            $filteredPriceProductMerchantRelationshipValueTransfers->append($priceProductMerchantRelationshipValueTransfer);
        }

        return $priceProductMerchantRelationshipStorageTransfer->setUngroupedPrices($filteredPriceProductMerchantRelationshipValueTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\PriceProductMerchantRelationshipStorageTransfer> $priceProductMerchantRelationshipStorageTransfers
     *
     * @return array<int>
     */
    protected function getActiveMerchantIds(array $priceProductMerchantRelationshipStorageTransfers): array
    {
        $merchantIds = [];
        foreach ($priceProductMerchantRelationshipStorageTransfers as $priceProductMerchantRelationshipStorageTransfer) {
            foreach ($priceProductMerchantRelationshipStorageTransfer->getUngroupedPrices() as $ungroupedPrice) {
                $merchantIds[] = $ungroupedPrice->getFkMerchantOrFail();
            }
        }

        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())
            ->setMerchantIds(array_unique($merchantIds))
            ->setIsActive(true);

        $merchantCollectionTransfer = $this->merchantReader->get($merchantCriteriaTransfer);

        $activeMerchantIds = [];
        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $activeMerchantIds[] = $merchantTransfer->getIdMerchantOrFail();
        }

        return $activeMerchantIds;
    }
}
