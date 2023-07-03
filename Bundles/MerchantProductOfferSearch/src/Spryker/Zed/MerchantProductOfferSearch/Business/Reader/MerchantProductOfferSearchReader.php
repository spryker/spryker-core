<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business\Reader;

use Generated\Shared\Transfer\ProductAbstractMerchantConditionsTransfer;
use Generated\Shared\Transfer\ProductAbstractMerchantCriteriaTransfer;
use Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface;

class MerchantProductOfferSearchReader implements MerchantProductOfferSearchReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface
     */
    protected $merchantProductOfferSearchRepository;

    /**
     * @param \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface $merchantProductOfferSearchRepository
     */
    public function __construct(MerchantProductOfferSearchRepositoryInterface $merchantProductOfferSearchRepository)
    {
        $this->merchantProductOfferSearchRepository = $merchantProductOfferSearchRepository;
    }

    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractMerchantTransfer>
     */
    public function getProductAbstractMerchantDataByProductAbstractIds(array $productAbstractIds): array
    {
        $productAbstractMerchantCriteriaTransfer = (new ProductAbstractMerchantCriteriaTransfer())
            ->setProductAbstractMerchantConditions(
                (new ProductAbstractMerchantConditionsTransfer())
                    ->setProductAbstractIds($productAbstractIds)
                    ->setIsProductOfferActive(true),
            );

        return $this->merchantProductOfferSearchRepository
            ->getProductAbstractMerchantCollection($productAbstractMerchantCriteriaTransfer)
            ->getProductAbstractMerchants()
            ->getArrayCopy();
    }
}
