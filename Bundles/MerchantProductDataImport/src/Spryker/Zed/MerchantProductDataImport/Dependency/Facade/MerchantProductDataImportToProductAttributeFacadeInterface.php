<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Dependency\Facade;

interface MerchantProductDataImportToProductAttributeFacadeInterface
{
    /**
     * @return list<\Generated\Shared\Transfer\ProductManagementAttributeTransfer>
     */
    public function getProductAttributeCollection(): array;
}
