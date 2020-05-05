<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Dependency\Facade;

use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;

class SalesReturnGuiToSalesReturnFacadeBridge implements SalesReturnGuiToSalesReturnFacadeInterface
{
    /**
     * @var \Spryker\Zed\SalesReturn\Business\SalesReturnFacadeInterface
     */
    protected $salesReturnFacade;

    /**
     * @param \Spryker\Zed\SalesReturn\Business\SalesReturnFacadeInterface $salesReturnFacade
     */
    public function __construct($salesReturnFacade)
    {
        $this->salesReturnFacade = $salesReturnFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    public function getReturns(ReturnFilterTransfer $returnFilterTransfer): ReturnCollectionTransfer
    {
        return $this->salesReturnFacade->getReturns($returnFilterTransfer);
    }
}
