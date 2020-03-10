<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyBusinessUnitSalesConnector\Zed;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Client\CompanyBusinessUnitSalesConnector\Dependency\Client\CompanyBusinessUnitSalesConnectorToZedRequestClientInterface;

class CompanyBusinessUnitSalesConnectorStub implements CompanyBusinessUnitSalesConnectorStubInterface
{
    /**
     * @var \Spryker\Client\CompanyBusinessUnitSalesConnector\Dependency\Client\CompanyBusinessUnitSalesConnectorToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\CompanyBusinessUnitSalesConnector\Dependency\Client\CompanyBusinessUnitSalesConnectorToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(CompanyBusinessUnitSalesConnectorToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @uses \Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Controller\GatewayController::getPermittedCompanyBusinessUnitsAction()
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function getPermittedCompanyBusinessUnitCollection(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyBusinessUnitCollectionTransfer {
        /** @var \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $companyBusinessUnitCollectionTransfer */
        $companyBusinessUnitCollectionTransfer = $this->zedRequestClient->call(
            '/company-business-unit-sales-connector/gateway/get-permitted-company-business-unit-collection',
            $companyUserTransfer
        );

        return $companyBusinessUnitCollectionTransfer;
    }
}
