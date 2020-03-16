<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Communication\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\FilterFieldCheckRequestTransfer;
use Generated\Shared\Transfer\FilterFieldCheckResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function getPermittedCompanyBusinessUnitCollectionAction(
        CompanyUserTransfer $companyUserTransfer
    ): CompanyBusinessUnitCollectionTransfer {
        return $this->getFacade()->getPermittedCompanyBusinessUnitCollection($companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldCheckRequestTransfer $filterFieldCheckRequestTransfer
     *
     * @return \Generated\Shared\Transfer\FilterFieldCheckResponseTransfer
     */
    public function isCompanyRelatedFiltersSetAction(
        FilterFieldCheckRequestTransfer $filterFieldCheckRequestTransfer
    ): FilterFieldCheckResponseTransfer {
        return $this->getFacade()->isCompanyRelatedFiltersSet($filterFieldCheckRequestTransfer);
    }
}
