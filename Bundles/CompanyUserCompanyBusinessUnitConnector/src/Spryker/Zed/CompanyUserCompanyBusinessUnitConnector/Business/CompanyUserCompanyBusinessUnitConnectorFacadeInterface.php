<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserCompanyBusinessUnitConnector\Business;

use Generated\Shared\Transfer\CompanyUserCompanyBusinessUnitConnectionUpdateTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;

interface CompanyUserCompanyBusinessUnitConnectorFacadeInterface
{
    /**
     * Specification:
     * - Assigns provided company user in transfer object to the "transfer.idCompanyBusinessUnitToAssign" company
     * business units.
     * - De-assigns company users from "transfer.idCompanyBusinessUnitToDeAssign" company business units.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserCompanyBusinessUnitConnectionUpdateTransfer $companyUserCompanyBusinessUnitConnectionUpdateTransfer
     *
     * @return void
     */
    public function updateCompanyUserCompanyBusinessUnitConnection(
        CompanyUserCompanyBusinessUnitConnectionUpdateTransfer $companyUserCompanyBusinessUnitConnectionUpdateTransfer
    );

    /**
     * Specification:
     * - Retrieves all business unit assigned to company user from DB.
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function getCompanyUserBusinessUnits(CompanyUserTransfer $companyUserTransfer);
}
