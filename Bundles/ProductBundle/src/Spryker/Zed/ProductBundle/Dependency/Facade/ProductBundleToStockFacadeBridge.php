<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Dependency\Facade;

class ProductBundleToStockFacadeBridge implements ProductBundleToStockFacadeInterface
{
    /**
     * @var \Spryker\Zed\Stock\Business\StockFacadeInterface
     */
    protected $stockFacade;

    /**
     * @param \Spryker\Zed\Stock\Business\StockFacadeInterface $stockFacade
     */
    public function __construct($stockFacade)
    {
        $this->stockFacade = $stockFacade;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoresWhereProductStockIsDefined(string $sku): array
    {
        return $this->stockFacade->getStoresWhereProductStockIsDefined($sku);
    }
}
