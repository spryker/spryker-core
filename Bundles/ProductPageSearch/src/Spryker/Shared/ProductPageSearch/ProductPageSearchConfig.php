<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductPageSearch;

use Spryker\Shared\Kernel\AbstractSharedConfig;

class ProductPageSearchConfig extends AbstractSharedConfig
{
    /**
     * @see \Spryker\Shared\PriceProduct\PriceProductConfig::PRICE_MODES
     *
     * @var array<string>
     */
    public const PRICE_MODES = [
        'NET_MODE',
        'GROSS_MODE',
    ];

    /**
     * Specification:
     * - This constant is used for extracting data from plugin array.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_ABSTRACT_PAGE_LOAD_DATA = 'PRODUCT_ABSTRACT_PAGE_LOAD_DATA';

    /**
     * @var string
     */
    public const PLUGIN_PRODUCT_PRICE_PAGE_DATA = 'PLUGIN_PRODUCT_PRICE_PAGE_DATA';

    /**
     * @var string
     */
    public const PLUGIN_PRODUCT_CATEGORY_PAGE_DATA = 'PLUGIN_PRODUCT_CATEGORY_PAGE_DATA';

    /**
     * @var string
     */
    public const PLUGIN_PRODUCT_IMAGE_PAGE_DATA = 'PLUGIN_PRODUCT_IMAGE_PAGE_DATA';

    /**
     * Specification:
     *  - Default Price Dimension name.
     *
     * @uses \Spryker\Shared\PriceProductStorage\PriceProductStorageConstants::PRICE_DIMENSION_DEFAULT
     *
     * @var string
     */
    public const PRICE_DIMENSION_DEFAULT = 'PRICE_DIMENSION_DEFAULT';

    /**
     * Defines queue name for publish.
     *
     * @var string
     */
    public const PUBLISH_PRODUCT_ABSTRACT_PAGE = 'publish.page_product_abstract';

    /**
     * Defines queue name for publish.
     *
     * @var string
     */
    public const PUBLISH_PRODUCT_CONCRETE_PAGE = 'publish.page_product_concrete';

    /**
     * Specification:
     * - This event will be used for product concrete publishing.
     *
     * @api
     *
     * @var string
     */
    public const PRODUCT_CONCRETE_PUBLISH = 'Product.product_concrete.publish';

    /**
     * Specification
     * - This event will be used for `spy_product_image_set_to_product_image` entity creation.
     *
     * @uses \Spryker\Zed\ProductImage\Dependency\ProductImageEvents::ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_CREATE
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_PRODUCT_IMAGE_SET_TO_PRODUCT_IMAGE_CREATE = 'Entity.spy_product_image_set_to_product_image.create';

    /**
     * Specification:
     * - This event will be used for `spy_category_store` entity creation.
     *
     * @uses \Spryker\Shared\CategoryStorage\CategoryStorageConstants::ENTITY_SPY_CATEGORY_STORE_CREATE
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_CATEGORY_STORE_CREATE = 'Entity.spy_category_store.create';

    /**
     * Specification:
     * - This event will be used for `spy_category_store` entity changes.
     *
     * @uses \Spryker\Shared\CategoryStorage\CategoryStorageConstants::ENTITY_SPY_CATEGORY_STORE_UPDATE
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_CATEGORY_STORE_UPDATE = 'Entity.spy_category_store.update';

    /**
     * Specification:
     * - This event will be used for `spy_category_store` entity deletion.
     *
     * @uses \Spryker\Shared\CategoryStorage\CategoryStorageConstants::ENTITY_SPY_CATEGORY_STORE_DELETE
     *
     * @api
     *
     * @var string
     */
    public const ENTITY_SPY_CATEGORY_STORE_DELETE = 'Entity.spy_category_store.delete';
}
