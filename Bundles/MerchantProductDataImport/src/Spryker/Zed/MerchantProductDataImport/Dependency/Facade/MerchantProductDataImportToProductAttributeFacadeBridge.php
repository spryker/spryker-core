<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Dependency\Facade;

class MerchantProductDataImportToProductAttributeFacadeBridge implements MerchantProductDataImportToProductAttributeFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface
     */
    protected $productAttributeFacade;

    /**
     * @param \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface $productAttributeFacade
     */
    public function __construct($productAttributeFacade)
    {
        $this->productAttributeFacade = $productAttributeFacade;
    }

    /**
     * @return list<\Generated\Shared\Transfer\ProductManagementAttributeTransfer>
     */
    public function getProductAttributeCollection(): array
    {
        return $this->productAttributeFacade->getProductAttributeCollection();
    }
}
