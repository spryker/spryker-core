<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SspServiceManagement\Zed;

use Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Generated\Shared\Transfer\SspServiceCollectionTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class SspServiceManagementStub implements SspServiceManagementStubInterface
{
    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedRequestClient
     */
    public function __construct(protected ZedRequestClientInterface $zedRequestClient)
    {
    }

    /**
     * @uses \SprykerFeature\Zed\SspServiceManagement\Communication\Controller\GatewayController::getServiceCollectionAction()
     *
     * @param \Generated\Shared\Transfer\SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SspServiceCollectionTransfer
     */
    public function getServiceCollection(SspServiceCriteriaTransfer $sspServiceCriteriaTransfer): SspServiceCollectionTransfer
    {
        /** @var \Generated\Shared\Transfer\SspServiceCollectionTransfer $sspServiceCollectionTransfer */
        $sspServiceCollectionTransfer = $this->zedRequestClient->call(
            '/ssp-service-management/gateway/get-service-collection',
            $sspServiceCriteriaTransfer,
        );

        return $sspServiceCollectionTransfer;
    }

    /**
     * @uses \SprykerFeature\Zed\SspServiceManagement\Communication\Controller\GatewayController::updateSalesOrderItemCollectionAction()
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function updateSalesOrderItemCollection(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        /** @var \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer */
        $salesOrderItemCollectionResponseTransfer = $this->zedRequestClient->call(
            '/ssp-service-management/gateway/update-sales-order-item-collection',
            $salesOrderItemCollectionRequestTransfer,
        );

        return $salesOrderItemCollectionResponseTransfer;
    }

    /**
     * @uses \SprykerFeature\Zed\SspServiceManagement\Communication\Controller\GatewayController::cancelSalesOrderItemCollectionAction()
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function cancelSalesOrderItemCollection(
        SalesOrderItemCollectionRequestTransfer $salesOrderItemCollectionRequestTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        /** @var \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer */
        $salesOrderItemCollectionResponseTransfer = $this->zedRequestClient->call(
            '/ssp-service-management/gateway/cancel-sales-order-item-collection',
            $salesOrderItemCollectionRequestTransfer,
        );

        return $salesOrderItemCollectionResponseTransfer;
    }
}
