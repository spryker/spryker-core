<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Communication\Plugin\Product;

use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductExtension\Dependency\Plugin\ProductAbstractCollectionExpanderPluginInterface;

/**
 * @method \Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\TaxProductConnector\TaxProductConnectorConfig getConfig()
 * @method \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface getQueryContainer()
 */
class TaxSetProductAbstractCollectionExpanderPlugin extends AbstractPlugin implements ProductAbstractCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands each product abstract with a corresponding tax set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractCollectionTransfer $productAbstractCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionTransfer
     */
    public function expand(
        ProductAbstractCollectionTransfer $productAbstractCollectionTransfer,
        ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
    ): ProductAbstractCollectionTransfer {
        return $this->getFacade()->expandProductAbstractCollectionWithTaxSets($productAbstractCollectionTransfer, $productAbstractCriteriaTransfer);
    }
}
