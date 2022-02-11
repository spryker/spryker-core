<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin;

/**
 * Provides capabilities to filter ProductAbstractStorage transfers before storing to search.
 * Filtered transfers will be removed from storage.
 */
interface ProductPageSearchCollectionFilterPluginInterface
{
    /**
     * Specification:
     * - Filters product page search transfers.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductPageSearchTransfer> $productPageSearchTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductPageSearchTransfer>
     */
    public function filter(array $productPageSearchTransfers): array;
}
