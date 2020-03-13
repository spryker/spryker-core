<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesReturn\Zed;

use Generated\Shared\Transfer\CreateReturnRequestTransfer;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\ReturnableItemFilterTransfer;
use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnReasonCollectionTransfer;
use Generated\Shared\Transfer\ReturnReasonFilterTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Spryker\Client\SalesReturn\Dependency\Client\SalesReturnToZedRequestClientInterface;

class SalesReturnStub implements SalesReturnStubInterface
{
    /**
     * @var \Spryker\Client\SalesReturn\Dependency\Client\SalesReturnToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\SalesReturn\Dependency\Client\SalesReturnToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(SalesReturnToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \Spryker\Zed\SalesReturn\Communication\Controller\GatewayController::getReturnReasonsAction()
     *
     * @param \Generated\Shared\Transfer\ReturnReasonFilterTransfer $returnReasonFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnReasonCollectionTransfer
     */
    public function getReturnReasons(ReturnReasonFilterTransfer $returnReasonFilterTransfer): ReturnReasonCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\ReturnReasonCollectionTransfer $returnReasonCollectionTransfer */
        $returnReasonCollectionTransfer = $this->zedRequestClient->call(
            '/sales-return/gateway/get-return-reasons',
            $returnReasonFilterTransfer
        );

        return $returnReasonCollectionTransfer;
    }

    /**
     * @uses \Spryker\Zed\SalesReturn\Communication\Controller\GatewayController::getReturnsAction()
     *
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    public function getReturns(ReturnFilterTransfer $returnFilterTransfer): ReturnCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\ReturnCollectionTransfer $returnCollectionTransfer */
        $returnCollectionTransfer = $this->zedRequestClient->call(
            '/sales-return/gateway/get-returns',
            $returnFilterTransfer
        );

        return $returnCollectionTransfer;
    }

    /**
     * @uses \Spryker\Zed\SalesReturn\Communication\Controller\GatewayController::createReturnAction()
     *
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(CreateReturnRequestTransfer $createReturnRequestTransfer): ReturnResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\ReturnResponseTransfer $returnResponseTransfer */
        $returnResponseTransfer = $this->zedRequestClient->call(
            '/sales-return/gateway/create-return',
            $createReturnRequestTransfer
        );

        return $returnResponseTransfer;
    }

    /**
     * @uses \Spryker\Zed\SalesReturn\Communication\Controller\GatewayController::getReturnableItemsAction()
     *
     * @param \Generated\Shared\Transfer\ReturnableItemFilterTransfer $returnableItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\ItemCollectionTransfer
     */
    public function getReturnableItems(ReturnableItemFilterTransfer $returnableItemFilterTransfer): ItemCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\ItemCollectionTransfer $itemCollectionTransfer */
        $itemCollectionTransfer = $this->zedRequestClient->call(
            '/sales-return/gateway/get-returnable-items',
            $returnableItemFilterTransfer
        );

        return $itemCollectionTransfer;
    }
}
