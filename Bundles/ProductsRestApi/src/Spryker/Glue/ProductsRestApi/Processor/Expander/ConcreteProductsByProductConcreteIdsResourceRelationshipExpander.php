<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi\Processor\Expander;

use Generated\Shared\Transfer\AbstractProductsRestAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class ConcreteProductsByProductConcreteIdsResourceRelationshipExpander extends AbstractConcreteProductsResourceRelationshipExpander
{
    /**
     * @var string
     */
    protected const KEY_PRODUCT_CONCRETE_IDS = 'product_concrete_ids';

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
            && $attributes instanceof AbstractProductsRestAttributesTransfer
            && !empty($attributes->getAttributeMap()[static::KEY_PRODUCT_CONCRETE_IDS])
        ) {
            return $attributes->getAttributeMap()[static::KEY_PRODUCT_CONCRETE_IDS];
        }

        return [];
    }
}
