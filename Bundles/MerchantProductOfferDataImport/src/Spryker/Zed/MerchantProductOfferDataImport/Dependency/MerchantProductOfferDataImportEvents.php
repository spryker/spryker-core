<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Dependency;

interface MerchantProductOfferDataImportEvents
{
    /**
     * @uses \Spryker\Zed\Product\Dependency\ProductEvents::PRODUCT_CONCRETE_UPDATE
     *
     * @var string
     */
    public const PRODUCT_CONCRETE_UPDATE = 'Product.product_concrete.update';

    /**
     * @uses \Spryker\Zed\PriceProductOffer\Dependency\PriceProductOfferEvents::ENTITY_SPY_PRICE_PRODUCT_OFFER_PUBLISH
     *
     * @var string
     */
    public const ENTITY_SPY_PRICE_PRODUCT_OFFER_PUBLISH = 'Entity.spy_price_product_offer.publish';

    /**
     * @uses \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::PRODUCT_OFFER_PUBLISH
     *
     * @var string
     */
    public const PRODUCT_OFFER_PUBLISH = 'ProductOffer.product_offer.publish';

    /**
     * @uses \Spryker\Shared\ProductOfferStorage\ProductOfferStorageConfig::PRODUCT_OFFER_STORE_PUBLISH
     *
     * @var string
     */
    public const PRODUCT_OFFER_STORE_PUBLISH = 'ProductOfferStore.publish';

    /**
     * @uses \Spryker\Zed\ProductOfferStock\Dependency\ProductOfferStockEvents::ENTITY_SPY_PRODUCT_OFFER_STOCK_PUBLISH
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_OFFER_STOCK_PUBLISH = 'ProductOffer.spy_product_offer_stock.publish';
}
