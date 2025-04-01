<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductAbstractFormDataProviderExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands product abstract form data with additional data.
     * - Uses ProductAbstractTransfer to get required data.
     * - Returns modified form data array.
     *
     * @api
     *
     * @param array<string, mixed> $formData
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return array<string, mixed>
     */
    public function expand(array $formData, ProductAbstractTransfer $productAbstractTransfer): array;
}
