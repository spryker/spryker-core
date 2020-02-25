<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Dependency\Client;

use Generated\Shared\Transfer\ReturnReasonCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;

class SalesReturnsRestApiToSalesReturnClientBridge implements SalesReturnsRestApiToSalesReturnClientInterface
{
    /**
     * @var \Spryker\Client\SalesReturn\SalesReturnClientInterface
     */
    protected $salesReturnClient;

    /**
     * @param \Spryker\Client\SalesReturn\SalesReturnClientInterface $salesReturnClient
     */
    public function __construct($salesReturnClient)
    {
        $this->salesReturnClient = $salesReturnClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnReasonFilterTransfer $returnReasonFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnReasonCollectionTransfer
     */
    public function getReturnReasons(ReturnReasonFilterTransfer $returnReasonFilterTransfer): ReturnReasonCollectionTransfer
    {
        return $this->salesReturnClient->getReturnReasons($returnReasonFilterTransfer);
    }
}
