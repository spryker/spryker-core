<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrderExtension\Dependency\Plugin;

interface ProductConcreteExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands provided array of ProductConcreteTransfers with additional data.
     * - Executed on quick order product search result.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function expand(array $productConcreteTransfers): array;
}
