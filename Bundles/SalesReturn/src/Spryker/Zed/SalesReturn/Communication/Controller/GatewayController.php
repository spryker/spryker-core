<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Communication\Controller;

use Generated\Shared\Transfer\CreateReturnRequestTransfer;
use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnReasonCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\SalesReturn\Business\SalesReturnFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\ReturnReasonFilterTransfer $returnReasonFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnReasonCollectionTransfer
     */
    public function getReturnReasonsAction(ReturnReasonFilterTransfer $returnReasonFilterTransfer): ReturnReasonCollectionTransfer
    {
        return $this->getFacade()->getReturnReasons($returnReasonFilterTransfer);
    }

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
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturnAction(CreateReturnRequestTransfer $createReturnRequestTransfer): ReturnResponseTransfer
    {
        return $this->getFacade()->createReturn($createReturnRequestTransfer);
    }
}
