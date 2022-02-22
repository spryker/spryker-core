<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductExtension\Dependency\Plugin;

/**
 * Implement this plugin to extend the transfers of product concrete with extra information.
 */
interface ProductConcreteExpanderPluginInterface
{
    /**
     * Specification:
     * - Executed on retrieved persisted product concrete data.
     * - Can be used to extend the transfers of product concrete with extra information.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expand(array $productConcreteTransfers): array;
}
