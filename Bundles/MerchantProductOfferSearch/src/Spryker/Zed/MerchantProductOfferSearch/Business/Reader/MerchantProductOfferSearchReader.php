<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business\Reader;

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
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractMerchantTransfer[]
     */
    public function getProductAbstractMerchantDataByProductAbstractIds(array $productAbstractIds): array
    {
        return $this->merchantProductOfferSearchRepository
            ->getMerchantDataByProductAbstractIds($productAbstractIds);
    }
}
