<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business\Reader;

use Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepository;
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
     * @return string[][]
     */
    public function getMerchantNamesByProductAbstractIds(array $productAbstractIds): array
    {
        $merchantProductAbstractData = $this->merchantProductOfferSearchRepository
            ->getMerchantDataByProductAbstractIds($productAbstractIds);

        return $this->mapMerchantNamesByIdProductAbstract($merchantProductAbstractData);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return string[][]
     */
    public function getMerchantReferencesByProductAbstractIds(array $productAbstractIds): array
    {
        $merchantProductAbstractData = $this->merchantProductOfferSearchRepository
            ->getMerchantDataByProductAbstractIds($productAbstractIds);

        return $this->mapMerchantReferencesByIdProductAbstract($merchantProductAbstractData);
    }

    /**
     * @param array $merchantProductAbstractData
     *
     * @return string[][]
     */
    protected function mapMerchantNamesByIdProductAbstract(array $merchantProductAbstractData): array
    {
        $mappedMerchantNamesByProductAbstractIds = [];

        foreach ($merchantProductAbstractData as $merchantProductAbstract) {
            $idProductAbstract = $merchantProductAbstract[MerchantProductOfferSearchRepository::KEY_ABSTRACT_PRODUCT_ID];
            $merchantName = $merchantProductAbstract[MerchantProductOfferSearchRepository::KEY_MERCHANT_NAME];
            $mappedMerchantNamesByProductAbstractIds[$idProductAbstract][] = $merchantName;
        }

        return $mappedMerchantNamesByProductAbstractIds;
    }

    /**
     * @param array $merchantProductAbstractData
     *
     * @return string[][]
     */
    protected function mapMerchantReferencesByIdProductAbstract(array $merchantProductAbstractData): array
    {
        $mappedMerchantReferencesByProductAbstractIds = [];

        foreach ($merchantProductAbstractData as $merchantProductAbstract) {
            $idProductAbstract = $merchantProductAbstract[MerchantProductOfferSearchRepository::KEY_ABSTRACT_PRODUCT_ID];
            $merchantReference = $merchantProductAbstract[MerchantProductOfferSearchRepository::KEY_MERCHANT_REFERENCE];
            $mappedMerchantReferencesByProductAbstractIds[$idProductAbstract][] = $merchantReference;
        }

        return $mappedMerchantReferencesByProductAbstractIds;
    }
}
