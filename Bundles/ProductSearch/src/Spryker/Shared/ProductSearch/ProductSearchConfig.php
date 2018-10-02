<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductSearch;

use Spryker\Shared\Product\ProductConfig;

interface ProductSearchConfig
{
    public const PRODUCT_SEARCH_FILTER_GLOSSARY_PREFIX = 'product.filter.';

    public const RESOURCE_TYPE_PRODUCT_ABSTRACT = ProductConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT;

    public const RESOURCE_TYPE_PRODUCT_SEARCH_CONFIG_EXTENSION = 'product_search_config_extension';

    public const PRODUCT_SEARCH_CONFIG_EXPANDER_RESOURCE_ID = 1;
}
