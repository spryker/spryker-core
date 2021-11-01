<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAbstractMerchantTransfer;

class MerchantProductAbstractMapper
{
    /**
     * @uses \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepository::KEY_PRODUCT_ABSTRACT_ID
     *
     * @var string
     */
    protected const KEY_PRODUCT_ABSTRACT_ID = 'id_product_abstract';

    /**
     * @uses \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepository::KEY_MERCHANT_NAME
     *
     * @var string
     */
    protected const KEY_MERCHANT_NAME = 'merchant_name';

    /**
     * @uses \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepository::KEY_MERCHANT_NAMES
     *
     * @var string
     */
    protected const KEY_MERCHANT_NAMES = 'merchant_names';

    /**
     * @uses \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepository::KEY_STORE_NAME
     *
     * @var string
     */
    protected const KEY_STORE_NAME = 'store_name';

    /**
     * @param array<int, mixed> $merchantData
     * @param array<\Generated\Shared\Transfer\ProductAbstractMerchantTransfer> $productAbstractMerchantTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractMerchantTransfer>
     */
    public function mapProductAbstractMerchantDataToProductAbstractMerchantTransfers(array $merchantData, array $productAbstractMerchantTransfers = []): array
    {
        $groupedMerchantDataByIdProductAbstract = $this->groupMerchantDataByIdProductAbstract($merchantData);

        foreach ($groupedMerchantDataByIdProductAbstract as $productAbstractMerchantData) {
            $productAbstractMerchantTransfers[] = (new ProductAbstractMerchantTransfer())->fromArray($productAbstractMerchantData, true);
        }

        return $productAbstractMerchantTransfers;
    }

    /**
     * @param array<int, mixed> $merchantData
     *
     * @return array<int, mixed>
     */
    protected function groupMerchantDataByIdProductAbstract(array $merchantData): array
    {
        $groupedProductAbstractMerchantData = [];

        foreach ($merchantData as $productAbstractMerchant) {
            $idProductAbstract = $productAbstractMerchant[static::KEY_PRODUCT_ABSTRACT_ID];
            $merchantName = $productAbstractMerchant[static::KEY_MERCHANT_NAME];
            $storeName = $productAbstractMerchant[static::KEY_STORE_NAME];

            $groupedProductAbstractMerchantData[$idProductAbstract][static::KEY_MERCHANT_NAMES][$storeName][] = $merchantName;
            $groupedProductAbstractMerchantData[$idProductAbstract][static::KEY_PRODUCT_ABSTRACT_ID] = $idProductAbstract;
        }

        return $groupedProductAbstractMerchantData;
    }
}
