<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Resolver;

use Generated\Shared\Transfer\ProductConcreteStorageTransfer;

class ProductConcreteStorageUrlResolver implements ProductConcreteStorageUrlResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteStorageTransfer $productConcreteStorageTransfer
     *
     * @return string
     */
    public function resolveProductConcreteUrl(ProductConcreteStorageTransfer $productConcreteStorageTransfer): string
    {
        $productConcreteStorageTransfer->requireUrl();

        return sprintf(
            '%s?%s',
            $productConcreteStorageTransfer->getUrl(),
            $this->resolveProductConcreteQueryString($productConcreteStorageTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteStorageTransfer $productConcreteStorageTransfer
     *
     * @return string
     */
    protected function resolveProductConcreteQueryString(
        ProductConcreteStorageTransfer $productConcreteStorageTransfer
    ): string {
        $productConcreteStorageTransfer
            ->requireAttributes()
            ->requireSuperAttributesDefinition();

        $superAttributes = $this->filterOutSuperAttributes(
            $productConcreteStorageTransfer->getAttributes(),
            $productConcreteStorageTransfer->getSuperAttributesDefinition()
        );

        return http_build_query($superAttributes);
    }

    /**
     * @param string[] $attributes
     * @param string[] $superAttributesDefinition
     *
     * @return string[]
     */
    protected function filterOutSuperAttributes(array $attributes, array $superAttributesDefinition): array
    {
        return array_filter($attributes, function ($attributeName) use ($superAttributesDefinition) {
            return in_array($attributeName, $superAttributesDefinition, true);
        }, ARRAY_FILTER_USE_KEY);
    }
}
