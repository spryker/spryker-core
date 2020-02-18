<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business\Reader;

use Spryker\Zed\MerchantProductOfferSearch\Business\Mapper\ProductAbstractMerchantMapperInterface;
use Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface;

class MerchantProductOfferSearchReader implements MerchantProductOfferSearchReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface
     */
    protected $merchantProductOfferSearchRepository;

    /**
     * @var \Spryker\Zed\MerchantProductOfferSearch\Business\Mapper\ProductAbstractMerchantMapperInterface
     */
    protected $productAbstractMerchantMapper;

    /**
     * @param \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface $merchantProductOfferSearchRepository
     * @param \Spryker\Zed\MerchantProductOfferSearch\Business\Mapper\ProductAbstractMerchantMapperInterface $productAbstractMerchantMapper
     */
    public function __construct(
        MerchantProductOfferSearchRepositoryInterface $merchantProductOfferSearchRepository,
        ProductAbstractMerchantMapperInterface $productAbstractMerchantMapper
    ) {
        $this->merchantProductOfferSearchRepository = $merchantProductOfferSearchRepository;
        $this->productAbstractMerchantMapper = $productAbstractMerchantMapper;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\ProductAbstractMerchantTransfer[]
     */
    public function getProductAbstractMerchantDataByProductAbstractIds(array $productAbstractIds): array
    {
        $productAbstractMerchantData = $this->merchantProductOfferSearchRepository
            ->getMerchantDataByProductAbstractIds($productAbstractIds);

        return $this->productAbstractMerchantMapper
            ->mapProductAbstractMerchantDataToProductAbstractMerchantTransfers($productAbstractMerchantData);
    }
}
