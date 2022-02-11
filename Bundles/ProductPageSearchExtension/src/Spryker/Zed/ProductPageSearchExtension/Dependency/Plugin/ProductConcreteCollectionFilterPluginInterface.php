<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin;

/**
 * Provides capabilities to filter ProductConcrete transfers before storing to search.
 */
interface ProductConcreteCollectionFilterPluginInterface
{
    /**
     * Specification:
     * - Filters product concrete transfers.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function filter(array $productConcreteTransfers): array;
}
