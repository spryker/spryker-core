<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationGui\Communication\Expander;

interface ProductConfigurationTableDataExpanderInterface
{
    /**
     * @param array $item
     *
     * @return array
     */
    public function expandProductItemWithProductConfigurationType(array $item): array;

    /**
     * @param array<array<string, mixed>> $items
     * @param array<array<string, mixed>> $productData
     *
     * @return array<array<string, mixed>>
     */
    public function expandProductItemsWithProductData(array $items, array $productData): array;
}
