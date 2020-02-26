<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAbstractMerchantTransfer;

class ProductAbstractMerchantMapper implements ProductAbstractMerchantMapperInterface
{
    /**
     * @uses \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepository::KEY_ABSTRACT_PRODUCT_ID
     */
    protected const KEY_ABSTRACT_PRODUCT_ID = 'id';

    /**
     * @uses \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepository::KEY_MERCHANT_NAMES
     */
    protected const KEY_MERCHANT_NAMES = 'names';

    /**
     * @uses \Spryker\Zed\MerchantProductOfferSearch\Persistence\MerchantProductOfferSearchRepository::KEY_MERCHANT_REFERENCES
     */
    protected const KEY_MERCHANT_REFERENCES = 'references';

    /**
     * @param array $productAbstractMerchantData
     * @param \Generated\Shared\Transfer\ProductAbstractMerchantTransfer $productAbstractMerchantTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractMerchantTransfer
     */
    public function mapProductAbstractMerchantDataToProductAbstractMerchantTransfer(
        array $productAbstractMerchantData,
        ProductAbstractMerchantTransfer $productAbstractMerchantTransfer
    ): ProductAbstractMerchantTransfer {
        return $productAbstractMerchantTransfer
            ->setIdProductAbstract($productAbstractMerchantData[static::KEY_ABSTRACT_PRODUCT_ID])
            ->setMerchantNames($productAbstractMerchantData[static::KEY_MERCHANT_NAMES])
            ->setMerchantReferences($productAbstractMerchantData[static::KEY_MERCHANT_REFERENCES]);
    }
}
