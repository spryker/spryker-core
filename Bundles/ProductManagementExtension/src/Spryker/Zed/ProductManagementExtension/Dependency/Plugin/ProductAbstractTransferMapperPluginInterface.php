<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductAbstractTransferMapperPluginInterface
{
    /**
     * Specification:
     * - Maps form data to ProductAbstractTransfer.
     * - Returns modified ProductAbstractTransfer.
     *
     * @api
     *
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function map(array $data, ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer;
}
