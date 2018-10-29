<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductStorage;

/**
 * Declares global environment configuration keys. Do not use it for other class constants.
 */
class ProductStorageConstants
{
    /**
     * Specification:
     * - Queue name as used for processing Product messages
     *
     * @api
     */
    public const PRODUCT_SYNC_STORAGE_QUEUE = 'sync.storage.product';

    /**
     * Specification:
     * - Queue name as used for processing Product messages
     *
     * @api
     */
    public const PRODUCT_SYNC_STORAGE_ERROR_QUEUE = 'sync.storage.product.error';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const PRODUCT_ABSTRACT_RESOURCE_NAME = 'product_abstract';

    /**
     * Specification:
     * - Resource name, this will use for key generating
     *
     * @api
     */
    public const PRODUCT_CONCRETE_RESOURCE_NAME = 'product_concrete';

    /**
     * @uses \Spryker\Shared\Product\ProductConfig::RESOURCE_TYPE_ATTRIBUTE_MAP
     */
    public const RESOURCE_TYPE_ATTRIBUTE_MAP = 'attribute_map';

    /**
     * @uses \Spryker\Shared\Product\ProductConfig::VARIANT_LEAF_NODE_ID
     */
    public const VARIANT_LEAF_NODE_ID = 'id_product_concrete';

    /**
     * @uses \Spryker\Shared\Product\ProductConfig::ATTRIBUTE_MAP_PATH_DELIMITER
     */
    public const ATTRIBUTE_MAP_PATH_DELIMITER = ':';
}
