<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet;

interface MerchantCombinedProductDataSetInterface
{
    /**
     * @var string
     */
    public const KEY_ABSTRACT_SKU = 'abstract_sku';

    /**
     * @var string
     */
    public const KEY_CONCRETE_SKU = 'concrete_sku';

    /**
     * @var string
     */
    public const KEY_STORE_RELATIONS = 'store_relations';

    /**
     * @var string
     */
    public const KEY_PRODUCT_ABSTRACT_CATEGORIES = 'product_abstract.categories';

    /**
     * @var string
     */
    public const KEY_PRODUCT_ATTRIBUTE_KEY_PATTERN = 'product.{ATTRIBUTE_KEY}';

    /**
     * @var string
     */
    public const KEY_PRODUCT_ATTRIBUTE_KEY_LOCALIZED = 'product.{ATTRIBUTE_KEY}.{LOCALE}';

    /**
     * @var string
     */
    public const KEY_PRODUCT_ABSTRACT_ATTRIBUTE_KEY_PATTERN = 'product_abstract.{ATTRIBUTE_KEY}';

    /**
     * @var string
     */
    public const KEY_PRODUCT_ABSTRACT_ATTRIBUTE_KEY_LOCALIZED = 'product_abstract.{ATTRIBUTE_KEY}.{LOCALE}';

    /**
     * @var string
     */
    public const KEY_PRODUCT_NAME_LOCALIZED = 'product.name.{LOCALE}';

    /**
     * @var string
     */
    public const KEY_PRODUCT_DESCRIPTION_LOCALIZED = 'product.description.{LOCALE}';

    /**
     * @var string
     */
    public const KEY_PRODUCT_IS_SEARCHABLE_LOCALIZED = 'product.is_searchable.{LOCALE}';

    /**
     * @var string
     */
    public const KEY_PRODUCT_ABSTRACT_NAME_LOCALIZED = 'product_abstract.name.{LOCALE}';

    /**
     * @var string
     */
    public const KEY_PRODUCT_ABSTRACT_DESCRIPTION_LOCALIZED = 'product_abstract.description.{LOCALE}';

    /**
     * @var string
     */
    public const KEY_IS_ACTIVE = 'is_active';

    /**
     * @var string
     */
    public const KEY_PRODUCT_ABSTRACT_META_TITLE_LOCALIZED = 'product_abstract.meta_title.{LOCALE}';

    /**
     * @var string
     */
    public const KEY_PRODUCT_ABSTRACT_META_DESCRIPTION_LOCALIZED = 'product_abstract.meta_description.{LOCALE}';

    /**
     * @var string
     */
    public const KEY_PRODUCT_ABSTRACT_TAX_SET_NAME = 'product_abstract.tax_set_name';

    /**
     * @var string
     */
    public const KEY_PRODUCT_ABSTRACT_NEW_FROM = 'product_abstract.new_from';

    /**
     * @var string
     */
    public const KEY_PRODUCT_ABSTRACT_NEW_TO = 'product_abstract.new_to';

    /**
     * @var string
     */
    public const KEY_PRODUCT_ABSTRACT_META_KEYWORDS_LOCALIZED = 'product_abstract.meta_keywords.{LOCALE}';

    /**
     * @var string
     */
    public const KEY_PRODUCT_ABSTRACT_URL_LOCALIZED = 'product_abstract.url.{LOCALE}';

    /**
     * @var string
     */
    public const KEY_PRODUCT_CONCRETE_IS_QUANTITY_SPLITTABLE = 'product_concrete.is_quantity_splittable';

    /**
     * @var string
     */
    public const KEY_PRODUCT_ASSIGNED_PRODUCT_TYPE = 'product.assigned_product_type';

    /**
     * @var string
     */
    public const KEY_PRODUCT_STOCK_WAREHOUSE_QUANTITY = 'product_stock.{WAREHOUSE_NAME}.quantity';

    /**
     * @var string
     */
    public const KEY_PRODUCT_STOCK_WAREHOUSE_PROPERTY = 'product_stock.{WAREHOUSE_NAME}.{PROPERTY}';

    /**
     * @var string
     */
    public const KEY_PRODUCT_STOCK_WAREHOUSE_IS_NEVER_OUT_OF_STOCK = 'product_stock.{WAREHOUSE_NAME}.is_never_out_of_stock';

    /**
     * @var string
     */
    public const KEY_PRODUCT_PRICE_STORE_PRICE_TYPE_CURRENCY_VALUE_NET = 'product_price.{STORE}.{PRICE_TYPE}.{CURRENCY}.value_net';

    /**
     * @var string
     */
    public const KEY_PRODUCT_PRICE_STORE_PRICE_TYPE_CURRENCY_VALUE_GROSS = 'product_price.{STORE}.{PRICE_TYPE}.{CURRENCY}.value_gross';

    /**
     * @var string
     */
    public const KEY_ABSTRACT_PRODUCT_PRICE_STORE_PRICE_TYPE_CURRENCY_VALUE_NET = 'abstract_product_price.{STORE}.{PRICE_TYPE}.{CURRENCY}.value_net';

    /**
     * @var string
     */
    public const KEY_ABSTRACT_PRODUCT_PRICE_STORE_PRICE_TYPE_CURRENCY_VALUE_GROSS = 'abstract_product_price.{STORE}.{PRICE_TYPE}.{CURRENCY}.value_gross';

    /**
     * @var string
     */
    public const KEY_PRODUCT_IMAGE_LOCALE_IMAGE_SET_NAME_COLUMN = 'product_image.{LOCALE}.{IMAGE_SET_NAME}.{PROPERTY}';

    /**
     * @var string
     */
    public const KEY_ABSTRACT_PRODUCT_IMAGE_LOCALE_IMAGE_SET_NAME_COLUMN = 'abstract_product_image.{LOCALE}.{IMAGE_SET_NAME}.{PROPERTY}';

    /**
     * @var string
     */
    public const PLACEHOLDER_LOCALE = '{LOCALE}';

    /**
     * @var string
     */
    public const PLACEHOLDER_WAREHOUSE_NAME = '{WAREHOUSE_NAME}';

    /**
     * @var string
     */
    public const PLACEHOLDER_STORE = '{STORE}';

    /**
     * @var string
     */
    public const PLACEHOLDER_PRICE_TYPE = '{PRICE_TYPE}';

    /**
     * @var string
     */
    public const PLACEHOLDER_CURRENCY = '{CURRENCY}';

    /**
     * @var string
     */
    public const PLACEHOLDER_ATTRIBUTE_KEY = '{ATTRIBUTE_KEY}';

    /**
     * @var string
     */
    public const PLACEHOLDER_IMAGE_SET_NAME = '{IMAGE_SET_NAME}';

    /**
     * @var string
     */
    public const PLACEHOLDER_PROPERTY = '{PROPERTY}';
}
