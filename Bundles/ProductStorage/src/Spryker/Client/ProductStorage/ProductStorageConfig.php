<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\ProductStorage\ProductStorageConstants;

class ProductStorageConfig extends AbstractBundleConfig
{
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

    /**
     * To be able to work with data exported with collectors to redis, we need to bring this module into compatibility
     * mode. If this is turned on the ProductClient will be used instead.
     *
     * @return bool
     */
    public static function isCollectorCompatibilityMode(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isSendingToQueue(): bool
    {
        return $this->get(ProductStorageConstants::STORAGE_SYNC_ENABLED, true);
    }
}
