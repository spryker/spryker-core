<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantCommissionConnector\Business\Reader;

use Generated\Shared\Transfer\ProductConcreteConditionsTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaTransfer;
use Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade\ProductMerchantCommissionConnectorToProductFacadeInterface;

class ProductReader implements ProductReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade\ProductMerchantCommissionConnectorToProductFacadeInterface
     */
    protected ProductMerchantCommissionConnectorToProductFacadeInterface $productFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade\ProductMerchantCommissionConnectorToProductFacadeInterface $productFacade
     */
    public function __construct(ProductMerchantCommissionConnectorToProductFacadeInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param list<string> $productConcreteSkus
     *
     * @return array<string, \Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductTransfersIndexedBySku(array $productConcreteSkus): array
    {
        $productConcreteConditionsTransfer = (new ProductConcreteConditionsTransfer())->setSkus($productConcreteSkus);
        $productConcreteCriteriaTransfer = (new ProductConcreteCriteriaTransfer())
            ->setProductConcreteConditions($productConcreteConditionsTransfer);

        $productConcreteCollectionTransfer = $this->productFacade->getProductConcreteCollection($productConcreteCriteriaTransfer);

        $productTransfers = [];
        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            $productTransfers[$productConcreteTransfer->getSkuOrFail()] = $productConcreteTransfer;
        }

        return $productTransfers;
    }
}
