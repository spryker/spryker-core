<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Communication\Plugin\ProductPageSearch;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractCollectionRefreshPluginInterface;

/**
 * @method \Spryker\Zed\SalesProductConnector\Business\SalesProductConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\SalesProductConnector\SalesProductConnectorConfig getConfig()
 */
class ProductListWasBoughtByIntervalPlugin extends AbstractPlugin implements ProductAbstractCollectionRefreshPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns productAbstractIds wich was bought by interval based on config.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function getProductPageLoadTransferForRefresh(): ProductPageLoadTransfer
    {
        return $this->getFacade()->getProductPageLoadTransferForRefresh();
    }
}
