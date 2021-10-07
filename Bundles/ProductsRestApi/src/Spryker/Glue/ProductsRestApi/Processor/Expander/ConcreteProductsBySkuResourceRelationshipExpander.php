<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class ConcreteProductsBySkuResourceRelationshipExpander extends AbstractConcreteProductsResourceRelationshipExpander
{
    /**
     * @var string
     */
    protected const KEY_SKU = 'sku';

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return array<string>
     */
    protected function findProductConcreteSkusInAttributes(RestResourceInterface $restResource): array
    {
        $attributes = $restResource->getAttributes();
        if (
            $attributes
            && $attributes->offsetExists(static::KEY_SKU)
            && $attributes->offsetGet(static::KEY_SKU)
        ) {
            return [$attributes->offsetGet(static::KEY_SKU)];
        }

        return [];
    }
}
