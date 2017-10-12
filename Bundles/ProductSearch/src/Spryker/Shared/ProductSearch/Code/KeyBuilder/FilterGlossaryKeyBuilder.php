<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductSearch\Code\KeyBuilder;

use Spryker\Shared\ProductSearch\ProductSearchConfig;

class FilterGlossaryKeyBuilder implements GlossaryKeyBuilderInterface
{
    /**
     * @param string $attributeKey
     *
     * @return string
     */
    public function buildGlossaryKey($attributeKey)
    {
        return ProductSearchConfig::PRODUCT_SEARCH_FILTER_GLOSSARY_PREFIX . $attributeKey;
    }
}
