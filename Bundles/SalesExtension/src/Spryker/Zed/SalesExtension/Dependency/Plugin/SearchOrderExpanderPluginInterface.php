<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesExtension\Dependency\Plugin;

/**
 * Provides expansion capabilities.
 *
 * Use this plugin interface for expanding $orderTransfers with additional data.
 */
interface SearchOrderExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands result OrderTransfers with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function expand(array $orderTransfers): array;
}
