<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Reader;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitFacadeInterface;
use Spryker\Zed\Kernel\PermissionAwareTrait;

class CompanyBusinessUnitReader implements CompanyBusinessUnitReaderInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitFacadeInterface
     */
    protected $companyBusinessUnitFacade;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
     */
    public function __construct(
        CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitFacadeInterface $companyBusinessUnitFacade
    ) {
        $this->companyBusinessUnitFacade = $companyBusinessUnitFacade;
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

            return $this->companyBusinessUnitFacade->getRawCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);
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
