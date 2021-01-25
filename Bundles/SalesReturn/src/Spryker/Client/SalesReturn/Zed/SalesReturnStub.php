<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesReturn\Zed;

use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
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
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function createReturn(ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnResponseTransfer
    {
        /** @var \Generated\Shared\Transfer\ReturnResponseTransfer $returnResponseTransfer */
        $returnResponseTransfer = $this->zedRequestClient->call(
            '/sales-return/gateway/create-return',
            $returnCreateRequestTransfer
        );

        return $returnResponseTransfer;
    }
}
