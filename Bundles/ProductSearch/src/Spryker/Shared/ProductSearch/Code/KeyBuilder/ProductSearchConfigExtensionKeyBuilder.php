<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductSearch\Code\KeyBuilder;

use Spryker\Shared\KeyBuilder\KeyBuilderInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderTrait;
use Spryker\Shared\ProductSearch\ProductSearchConfig;

class ProductSearchConfigExtensionKeyBuilder implements KeyBuilderInterface
{
    use KeyBuilderTrait;

    /**
     * @param mixed $data
     *
     * @return string
     */
    protected function buildKey($data)
    {
        return ProductSearchConfig::RESOURCE_TYPE_PRODUCT_SEARCH_CONFIG_EXTENSION;
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'product_search';
    }
}
