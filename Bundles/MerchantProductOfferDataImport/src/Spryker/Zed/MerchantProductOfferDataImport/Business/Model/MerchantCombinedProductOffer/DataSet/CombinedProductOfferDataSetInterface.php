<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\DataSet;

interface CombinedProductOfferDataSetInterface
{
    /**
     * @var string
     */
    public const OFFER_REFERENCE = 'offer_reference';

    /**
     * @var string
     */
    public const CONCRETE_SKU = 'concrete_sku';

    /**
     * @var string
     */
    public const MERCHANT_SKU = 'merchant_sku';

    /**
     * @var string
     */
    public const IS_ACTIVE = 'is_active';

    /**
     * @var string
     */
    public const STORE_RELATIONS = 'store_relations';

    /**
     * @var string
     */
    public const MERCHANT_REFERENCE = 'merchant_reference';

    /**
     * @var string
     */
    public const VALID_FROM = 'valid_from';

    /**
     * @var string
     */
    public const VALID_TO = 'valid_to';

    /**
     * @var string
     */
    public const DATA_IS_NEW_PRODUCT_OFFER = 'DATA_IS_NEW_PRODUCT_OFFER';

    /**
     * @var string
     */
    public const DATA_PRODUCT_ENTITY = 'DATA_PRODUCT_ENTITY';

    /**
     * @var string
     */
    public const DATA_PRODUCT_OFFER_ENTITY = 'DATA_PRODUCT_OFFER_ENTITY';

    /**
     * @var string
     */
    public const DATA_MERCHANT_STOCKS = 'DATA_MERCHANT_STOCKS';

    /**
     * @var string
     */
    public const DATA_PRODUCT_OFFER_STOCKS = 'DATA_PRODUCT_OFFER_STOCKS';

    /**
     * @var string
     */
    public const DATA_PRODUCT_OFFER_PRICES = 'DATA_PRODUCT_OFFER_PRICES';

    /**
     * @var string
     */
    public const DATA_PRICE_TYPE_IDS_INDEXED_BY_NAME = 'DATA_PRICE_TYPE_IDS_INDEXED_BY_NAME';

    /**
     * @var string
     */
    public const DATA_CURRENCY_IDS_INDEXED_BY_CODE = 'DATA_CURRENCY_IDS_INDEXED_BY_CODE';

    /**
     * @var string
     */
    public const DATA_CURRENCY_NAMES_INDEXED_BY_STORE_NAME = 'DATA_CURRENCY_NAMES_INDEXED_BY_STORE_NAME';
}
