<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductConcreteExpanderPluginInterface
{
    /**
     * Specification:
     * - expands and returns ProductConcreteTransfer. Used in ProductStorageClient::mapProductStorageDataToProductConcreteTransfer().
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expand(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer;
}
