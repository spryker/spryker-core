<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeProductLabelConnector\Communication\Plugin;

use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductAlternativeExtension\Dependency\Plugin\PostProductUpdateAlternativesPluginInterface;

/**
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\Business\ProductAlternativeProductLabelConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductAlternativeProductLabelConnector\ProductAlternativeProductLabelConnectorConfig getConfig()
 */
class PostProductUpdateAlternativesPlugin extends AbstractPlugin implements PostProductUpdateAlternativesPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return void
     */
    public function execute(ProductAlternativeTransfer $productAlternativeTransfer): void
    {
        $this->getFacade()->updateAbstractProductWithAlternativesAvailableLabel($productAlternativeTransfer->getIdProduct());
    }
}
