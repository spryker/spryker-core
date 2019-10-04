<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Expander;

use Generated\Shared\Transfer\ProductListAggregateFormTransfer;

interface ProductListAggregateFormDataProviderExpanderInterface
{
    /**
     * @param array $options
     *
     * @return array
     */
    public function expandOptions(array $options): array;

    /**
     * @param \Generated\Shared\Transfer\ProductListAggregateFormTransfer $productListAggregateFormTransfer
     *
     * @return \Generated\Shared\Transfer\ProductListAggregateFormTransfer
     */
    public function expandProductListAggregateFormData(ProductListAggregateFormTransfer $productListAggregateFormTransfer): ProductListAggregateFormTransfer;
}
