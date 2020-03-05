<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesReturn;

use Generated\Shared\Transfer\CreateReturnRequestTransfer;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ReturnableItemFilterTransfer;
use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnReasonCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
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
     * @param \Generated\Shared\Transfer\ReturnReasonFilterTransfer $returnReasonFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnReasonCollectionTransfer
     */
    public function getReturnReasons(ReturnReasonFilterTransfer $returnReasonFilterTransfer): ReturnReasonCollectionTransfer
    {
        return $this->getZedStub()->getReturnReasons($returnReasonFilterTransfer);
    }

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
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(CreateReturnRequestTransfer $createReturnRequestTransfer): ReturnResponseTransfer
    {
        return $this->getZedStub()->createReturn($createReturnRequestTransfer);
    }

    /**
     * {@inheritDoc}
     * - ...
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnableItemFilterTransfer $returnableItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getReturnableItems(ReturnableItemFilterTransfer $returnableItemFilterTransfer): ItemCollectionTransfer
    {
        return $this->getZedStub()->getReturnableItems($returnableItemFilterTransfer);
    }

    /**
     * @return \Spryker\Client\SalesReturn\Zed\SalesReturnStubInterface
     */
    protected function getZedStub(): SalesReturnStubInterface
    {
        return $this->getFactory()->createSalesReturnStub();
    }
}
