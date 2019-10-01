<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductListAggregateFormTransfer;

interface ProductListAggregateFormDataProviderExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands form options for ProductListAggregateFormType with additional data.
     *
     * @api
     *
     * @param array $options
     *
     * @return array
     */
    public function expandOptions(array $options): array;

    /**
     * Specification:
     * - Expands form data for ProductListAggregateFormType with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductListAggregateFormTransfer $productListAggregateFormTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListAggregateFormTransfer
     */
    public function expandData(ProductListAggregateFormTransfer $productListAggregateFormTransfer): ProductListAggregateFormTransfer;
}
