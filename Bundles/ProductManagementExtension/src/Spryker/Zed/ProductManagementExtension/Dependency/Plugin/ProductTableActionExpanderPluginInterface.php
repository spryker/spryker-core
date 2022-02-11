<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ButtonCollectionTransfer;

/**
 * Provides capabilities to add actions to abstract product table.
 */
interface ProductTableActionExpanderPluginInterface
{
    /**
     * Specification:
     *  - Expands abstract product table actions.
     *
     * @api
     *
     * @param array<mixed> $productData
     * @param \Generated\Shared\Transfer\ButtonCollectionTransfer $buttonCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ButtonCollectionTransfer
     */
    public function execute(array $productData, ButtonCollectionTransfer $buttonCollectionTransfer): ButtonCollectionTransfer;
}
