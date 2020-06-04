<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnSearch\Dependency\Facade;

use Generated\Shared\Transfer\ReturnReasonCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;

class SalesReturnSearchToSalesReturnFacadeBridge implements SalesReturnSearchToSalesReturnFacadeInterface
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
     * @param \Generated\Shared\Transfer\ReturnReasonFilterTransfer $returnReasonFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnReasonCollectionTransfer
     */
    public function getReturnReasons(ReturnReasonFilterTransfer $returnReasonFilterTransfer): ReturnReasonCollectionTransfer
    {
        return $this->salesReturnFacade->getReturnReasons($returnReasonFilterTransfer);
    }
}
