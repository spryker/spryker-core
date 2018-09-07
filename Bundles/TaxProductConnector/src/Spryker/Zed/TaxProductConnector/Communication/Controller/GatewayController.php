<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Communication\Controller;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\TaxSetResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetResponseTransfer
     */
    public function getTaxSetForProductAbstractAction(ProductAbstractTransfer $productAbstractTransfer): TaxSetResponseTransfer
    {
        return $this->getFacade()->getTaxSetForProductAbstract($productAbstractTransfer);
    }
}
