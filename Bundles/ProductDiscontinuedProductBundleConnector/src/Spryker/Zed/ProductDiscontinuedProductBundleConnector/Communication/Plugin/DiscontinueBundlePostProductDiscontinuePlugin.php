<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedProductBundleConnector\Communication\Plugin;

use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductDiscontinuedExtension\Dependency\Plugin\PostProductDiscontinuePluginInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinuedProductBundleConnector\Business\ProductDiscontinuedProductBundleConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinuedProductBundleConnector\ProductDiscontinuedProductBundleConnectorConfig getConfig()
 */
class DiscontinueBundlePostProductDiscontinuePlugin extends AbstractPlugin implements PostProductDiscontinuePluginInterface
{
    /**
     * Specification:
     *  - Mark bundle product related to discontinued product as discontinue.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return void
     */
    public function execute(ProductDiscontinuedTransfer $productDiscontinuedTransfer): void
    {
        $this->getFacade()->markRelatedBundleAsDiscontinued($productDiscontinuedTransfer);
    }
}
