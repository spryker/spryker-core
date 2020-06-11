<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin;

/**
 * Provides extension capabilities for product abstract Elasticsearch document.
 *
 * Use this plugin if you want "add_to_cart_sku" field to be exported to product abstract Elasticsearch document.
 */
interface ProductAbstractAddToCartPluginInterface
{
    /**
     * Specification:
     * - Returns the concrete products that are eligible for product abstract "add to cart".
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getEligibleConcreteProducts(array $productConcreteTransfers): array;
}
