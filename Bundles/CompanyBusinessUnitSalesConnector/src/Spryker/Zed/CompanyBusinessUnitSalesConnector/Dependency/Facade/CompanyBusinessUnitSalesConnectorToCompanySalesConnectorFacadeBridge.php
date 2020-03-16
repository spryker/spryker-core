<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade;

class CompanyBusinessUnitSalesConnectorToCompanySalesConnectorFacadeBridge implements CompanyBusinessUnitSalesConnectorToCompanySalesConnectorFacadeInterface
{
    /**
     * @var \Spryker\Zed\CompanySalesConnector\Business\CompanySalesConnectorFacadeInterface
     */
    protected $companySalesConnectorFacade;

    /**
     * @param \Spryker\Zed\CompanySalesConnector\Business\CompanySalesConnectorFacadeInterface $companySalesConnectorFacade
     */
    public function __construct($companySalesConnectorFacade)
    {
        $this->companySalesConnectorFacade = $companySalesConnectorFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterFieldTransfer[] $filterFieldTransfers
     *
     * @return bool
     */
    public function isCompanyFilterApplicable(array $filterFieldTransfers): bool
    {
        return $this->companySalesConnectorFacade->isCompanyFilterApplicable($filterFieldTransfers);
    }
}
