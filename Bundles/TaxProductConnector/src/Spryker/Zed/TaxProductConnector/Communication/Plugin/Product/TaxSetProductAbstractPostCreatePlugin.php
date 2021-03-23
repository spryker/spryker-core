<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Communication\Plugin\Product;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractPostCreatePluginInterface;

/**
 * @method \Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\TaxProductConnector\TaxProductConnectorConfig getConfig()
 * @method \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface getQueryContainer()
 */
class TaxSetProductAbstractPostCreatePlugin extends AbstractPlugin implements ProductAbstractPostCreatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Saves tax set id to product abstract table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function create(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        return $this->getFacade()->saveTaxSetToProductAbstract($productAbstractTransfer);
    }
}
