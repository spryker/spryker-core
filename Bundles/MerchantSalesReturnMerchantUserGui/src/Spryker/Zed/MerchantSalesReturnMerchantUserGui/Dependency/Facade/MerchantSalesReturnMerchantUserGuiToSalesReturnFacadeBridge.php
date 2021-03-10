<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade;

use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnReasonCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;

class MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeBridge implements
    MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeInterface
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

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonFilterTransfer $returnReasonFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnReasonCollectionTransfer
     */
    public function getReturnReasons(ReturnReasonFilterTransfer $returnReasonFilterTransfer): ReturnReasonCollectionTransfer
    {
        return $this->salesReturnFacade->getReturnReasons($returnReasonFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnResponseTransfer
    {
        return $this->salesReturnFacade->createReturn($returnCreateRequestTransfer);
    }
}
