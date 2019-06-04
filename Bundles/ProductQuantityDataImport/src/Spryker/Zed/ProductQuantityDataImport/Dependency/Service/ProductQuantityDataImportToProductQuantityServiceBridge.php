<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductQuantityDataImport\Dependency\Service;

class ProductQuantityDataImportToProductQuantityServiceBridge implements ProductQuantityDataImportToProductQuantityServiceInterface
{
    /**
     * @var \Spryker\Service\ProductQuantity\ProductQuantityServiceInterface
     */
    protected $productQuantityService;

    /**
     * @param \Spryker\Service\ProductQuantity\ProductQuantityServiceInterface $productQuantityService
     */
    public function __construct($productQuantityService)
    {
        $this->productQuantityService = $productQuantityService;
    }

    /**
     * @return float
     */
    public function getDefaultMinimumQuantity(): float
    {
        return $this->productQuantityService->getDefaultMinimumQuantity();
    }
}
