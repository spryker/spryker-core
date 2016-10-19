<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorBusinessFactory getFactory()
 */
class TaxProductConnectorFacade extends AbstractFacade implements TaxProductConnectorFacadeInterface
{

    /**
     * @api
     *
     * @return \Spryker\Zed\TaxProductConnector\Business\Plugin\TaxChangeTouchPlugin
     */
    public function getTaxChangeTouchPlugin()
    {
        return $this->getFactory()->createTaxChangeTouchPlugin();
    }

    /**
     * Specification:
     * - Save tax set id to product abstract table
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function saveTaxSetToProductAbstract(ProductAbstractTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createProductAbstractTaxWriter()
            ->saveTaxSetToProductAbstract($productConcreteTransfer);
    }

    /**
     * Specification:
     * - Read tax set from product abstract table and store into transfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function addTaxSet(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createProductAbstractTaxSetMapper()
            ->addTaxSet($productAbstractTransfer);
    }

}
