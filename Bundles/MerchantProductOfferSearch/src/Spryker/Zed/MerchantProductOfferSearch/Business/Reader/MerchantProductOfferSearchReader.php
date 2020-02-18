<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business\Reader;

use Generated\Shared\Transfer\MerchantProductAbstractTransfer;
use Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepositoryInterface;

class MerchantProductOfferSearchReader implements MerchantProductOfferSearchReaderInterface
{
    /**
     * @uses Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepository::KEY_ABSTRACT_PRODUCT_ID
     */
    protected const KEY_ABSTRACT_PRODUCT_ID = 'id';

    /**
     * @uses Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepository::KEY_MERCHANT_NAME
     */
    protected const KEY_MERCHANT_NAME = 'name';

    /**
     * @uses Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepository::KEY_MERCHANT_REFERENCE
     */
    protected const KEY_MERCHANT_REFERENCE = 'reference';

    protected const KEY_MERCHANT_NAMES = 'names';
    protected const KEY_MERCHANT_REFERENCES = 'references';

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
     * @return \Generated\Shared\Transfer\MerchantProductAbstractTransfer[]
     */
    public function getProductAbstractMerchantDataByProductAbstractIds(array $productAbstractIds): array
    {
        $merchantProductAbstractData = $this->merchantProductOfferSearchRepository
            ->getMerchantDataByProductAbstractIds($productAbstractIds);

        return $this->mapMerchantProductAbstractDataToMerchantProductAbstractTransfers($merchantProductAbstractData);
    }

    /**
     * @param array $merchantProductAbstractData
     *
     * @return \Generated\Shared\Transfer\MerchantProductAbstractTransfer[]
     */
    protected function mapMerchantProductAbstractDataToMerchantProductAbstractTransfers(array $merchantProductAbstractData): array
    {
        $groupedMerchantProductAbstractData = [];

        foreach ($merchantProductAbstractData as $merchantProductAbstract) {
            $idProductAbstract = $merchantProductAbstract[static::KEY_ABSTRACT_PRODUCT_ID];
            $merchantName = $merchantProductAbstract[static::KEY_MERCHANT_NAME];
            $merchantReference = $merchantProductAbstract[static::KEY_MERCHANT_REFERENCE];

            $groupedMerchantProductAbstractData[$idProductAbstract][static::KEY_MERCHANT_NAMES][] = $merchantName;
            $groupedMerchantProductAbstractData[$idProductAbstract][static::KEY_MERCHANT_REFERENCES][] = $merchantReference;
        }

        return $this->mapGroupedMerchantsToMerchantProductAbstractTransfers($groupedMerchantProductAbstractData);
    }

    /**
     * @param array $groupedMerchantProductAbstractData
     *
     * @return \Generated\Shared\Transfer\MerchantProductAbstractTransfer[]
     */
    protected function mapGroupedMerchantsToMerchantProductAbstractTransfers(array $groupedMerchantProductAbstractData): array
    {
        $merchantProductAbstractTransfers = [];

        foreach ($groupedMerchantProductAbstractData as $idProductAbstract => $merchantProductAbstractData) {
            $merchantProductAbstractTransfers[] = (new MerchantProductAbstractTransfer())
                ->setIdProductAbstract($idProductAbstract)
                ->setMerchantNames($merchantProductAbstractData[static::KEY_MERCHANT_NAMES])
                ->setMerchantReferences($merchantProductAbstractData[static::KEY_MERCHANT_REFERENCES]);
        }

        return $merchantProductAbstractTransfers;
    }
}
