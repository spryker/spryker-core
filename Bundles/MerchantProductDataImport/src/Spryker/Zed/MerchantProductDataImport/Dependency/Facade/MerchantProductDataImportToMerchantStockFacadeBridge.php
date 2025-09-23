<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Dependency\Facade;

use Generated\Shared\Transfer\MerchantStockCriteriaTransfer;
use Generated\Shared\Transfer\StockCollectionTransfer;

class MerchantProductDataImportToMerchantStockFacadeBridge implements MerchantProductDataImportToMerchantStockFacadeInterface
{
    /**
     * @var \Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface
     */
    protected $merchantStockFacade;

    /**
     * @param \Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface $merchantStockFacade
     */
    public function __construct($merchantStockFacade)
    {
        $this->merchantStockFacade = $merchantStockFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStockCriteriaTransfer $merchantStockCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function get(MerchantStockCriteriaTransfer $merchantStockCriteriaTransfer): StockCollectionTransfer
    {
        return $this->merchantStockFacade->get($merchantStockCriteriaTransfer);
    }
}
