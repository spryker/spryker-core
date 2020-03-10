<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Reader;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Client\CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitClientInterface;
use Spryker\Zed\Kernel\PermissionAwareTrait;

class CompanyBusinessUnitReader implements CompanyBusinessUnitReaderInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Client\CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitClientInterface
     */
    protected $companyBusinessUnitClient;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Client\CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
     */
    public function __construct(
        CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
    ) {
        $this->companyBusinessUnitClient = $companyBusinessUnitClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function getPermittedCompanyBusinessUnitCollection(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyBusinessUnitCollectionTransfer {
        $companyUserTransfer->requireIdCompanyUser();

        $idCompanyUser = $companyUserTransfer->getIdCompanyUser();

        if ($this->can('SeeCompanyOrdersPermissionPlugin', $idCompanyUser)) {
            $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
                ->setIdCompany($companyUserTransfer->getFkCompany());

            return $this->companyBusinessUnitClient->getRawCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);
        }

        $companyBusinessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();

        if ($companyUserTransfer->getCompanyBusinessUnit() && $this->can('SeeBusinessUnitOrdersPermissionPlugin', $idCompanyUser)) {
            $companyBusinessUnitCollectionTransfer->addCompanyBusinessUnit(
                $companyUserTransfer->getCompanyBusinessUnit()
            );
        }

        return $companyBusinessUnitCollectionTransfer;
    }
}
