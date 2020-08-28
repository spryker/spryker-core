<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Communication\Controller;

use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\SalesReturn\Business\SalesReturnFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    public function getReturnsAction(ReturnFilterTransfer $returnFilterTransfer): ReturnCollectionTransfer
    {
        return $this->getFacade()->getReturns($returnFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturnAction(ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnResponseTransfer
    {
        return $this->getFacade()->createReturn($returnCreateRequestTransfer);
    }
}
