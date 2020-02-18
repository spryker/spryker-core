<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business\Mapper;

use Generated\Shared\Transfer\ProductAbstractMerchantTransfer;

class ProductAbstractMerchantMapper implements ProductAbstractMerchantMapperInterface
{
    /**
     * @uses \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepository::KEY_ABSTRACT_PRODUCT_ID
     */
    protected const KEY_ABSTRACT_PRODUCT_ID = 'id';

    /**
     * @uses \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepository::KEY_MERCHANT_NAME
     */
    protected const KEY_MERCHANT_NAME = 'name';

    /**
     * @uses \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepository::KEY_MERCHANT_REFERENCE
     */
    protected const KEY_MERCHANT_REFERENCE = 'reference';

    protected const KEY_MERCHANT_NAMES = 'names';
    protected const KEY_MERCHANT_REFERENCES = 'references';

    /**
     * @param array $productAbstractMerchantData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractMerchantTransfer[]
     */
    public function mapProductAbstractMerchantDataToProductAbstractMerchantTransfers(array $productAbstractMerchantData): array
    {
        $groupedProductAbstractMerchantData = [];

        foreach ($productAbstractMerchantData as $productAbstractMerchant) {
            $idProductAbstract = $productAbstractMerchant[static::KEY_ABSTRACT_PRODUCT_ID];
            $merchantName = $productAbstractMerchant[static::KEY_MERCHANT_NAME];
            $merchantReference = $productAbstractMerchant[static::KEY_MERCHANT_REFERENCE];

            $groupedProductAbstractMerchantData[$idProductAbstract][static::KEY_MERCHANT_NAMES][] = $merchantName;
            $groupedProductAbstractMerchantData[$idProductAbstract][static::KEY_MERCHANT_REFERENCES][] = $merchantReference;
        }

        return $this->mapGroupedMerchantsToProductAbstractMerchantTransfers($groupedProductAbstractMerchantData);
    }

    /**
     * @param array $groupedProductAbstractMerchantData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractMerchantTransfer[]
     */
    protected function mapGroupedMerchantsToProductAbstractMerchantTransfers(array $groupedProductAbstractMerchantData): array
    {
        $productAbstractMerchantTransfers = [];

        foreach ($groupedProductAbstractMerchantData as $idProductAbstract => $productAbstractMerchantData) {
            $productAbstractMerchantTransfers[] = (new ProductAbstractMerchantTransfer())
                ->setIdProductAbstract($idProductAbstract)
                ->setMerchantNames($productAbstractMerchantData[static::KEY_MERCHANT_NAMES])
                ->setMerchantReferences($productAbstractMerchantData[static::KEY_MERCHANT_REFERENCES]);
        }

        return $productAbstractMerchantTransfers;
    }
}
