<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
namespace Spryker\Zed\TaxProductConnector\Communication\Plugin;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\Plugin\ProductAbstractPluginInterface;

/**
 * @method \Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorFacade getFacade()
 */
class TaxSetProductAbstractAfterCreatePlugin extends AbstractPlugin implements ProductAbstractPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function run(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFacade()->saveTaxSetToProductAbstract($productAbstractTransfer);
    }

}
