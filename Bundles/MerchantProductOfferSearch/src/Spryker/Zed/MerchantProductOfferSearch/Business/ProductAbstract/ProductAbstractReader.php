<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business\ProductAbstract;

use Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface;

class ProductAbstractReader implements ProductAbstractReaderInterface
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
     * @param int[] $merchantIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByMerchantIds(array $merchantIds): array
    {
        return $this->merchantProductOfferSearchRepository->getProductAbstractIdsByMerchantIds($merchantIds);
    }

    /**
     * @param int[] $merchantProfileIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByMerchantProfileIds(array $merchantProfileIds): array
    {
        return $this->merchantProductOfferSearchRepository->getProductAbstractIdsByMerchantProfileIds($merchantProfileIds);
    }

    /**
     * @param int[] $productOfferIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByProductOfferIds(array $productOfferIds): array
    {
        return $this->merchantProductOfferSearchRepository->getProductAbstractIdsByProductOfferIds($productOfferIds);
    }
}
