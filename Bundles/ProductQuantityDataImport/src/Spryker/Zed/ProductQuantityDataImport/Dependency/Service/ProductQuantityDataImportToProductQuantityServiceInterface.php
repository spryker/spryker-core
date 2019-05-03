<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductQuantityDataImport\Dependency\Service;

interface ProductQuantityDataImportToProductQuantityServiceInterface
{
    /**
     * @return float
     */
    public function getDefaultMinimumQuantity(): float;
}
