<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Communication\Plugin\ProductPageSearch\DataLoader;

use Generated\Shared\Transfer\ProductPageLoadTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageDataLoaderPluginInterface;

/**
 * @method \Spryker\Zed\ProductListSearch\Business\ProductListSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductListSearch\Communication\ProductListSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductListSearch\Persistence\ProductListSearchRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductListSearch\ProductListSearchConfig getConfig()
 */
class ProductListDataLoaderPlugin extends AbstractPlugin implements ProductPageDataLoaderPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductPageLoadTransfer $loadTransfer
     *
     * @return \Generated\Shared\Transfer\ProductPageLoadTransfer
     */
    public function expandProductPageDataTransfer(ProductPageLoadTransfer $loadTransfer)
    {
        return $this->getFacade()->expandProductPageData($loadTransfer);
    }
}
