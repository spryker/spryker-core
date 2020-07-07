<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesReturn;

use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\SalesReturn\Zed\SalesReturnStubInterface;

/**
 * @method \Spryker\Client\SalesReturn\SalesReturnFactory getFactory()
 */
class SalesReturnClient extends AbstractClient implements SalesReturnClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    public function getReturns(ReturnFilterTransfer $returnFilterTransfer): ReturnCollectionTransfer
    {
        return $this->getZedStub()->getReturns($returnFilterTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnResponseTransfer
    {
        return $this->getZedStub()->createReturn($returnCreateRequestTransfer);
    }

    /**
     * @return \Spryker\Client\SalesReturn\Zed\SalesReturnStubInterface
     */
    protected function getZedStub(): SalesReturnStubInterface
    {
        return $this->getFactory()->createSalesReturnStub();
    }
}
