<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Communication\Controller;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Product\Business\ProductFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function findProductConcreteIdBySkuAction(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $productConcreteTransfer->setIdProductConcrete(
            $this->getFacade()->findProductConcreteIdBySku($productConcreteTransfer->getSku())
        );

        return $productConcreteTransfer;
    }
}
