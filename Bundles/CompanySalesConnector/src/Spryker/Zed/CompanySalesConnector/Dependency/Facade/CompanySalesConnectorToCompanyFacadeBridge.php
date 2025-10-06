<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Dependency\Facade;

use Generated\Shared\Transfer\CompanyTransfer;

class CompanySalesConnectorToCompanyFacadeBridge implements CompanySalesConnectorToCompanyFacadeInterface
{
    /**
     * @var \Spryker\Zed\Company\Business\CompanyFacadeInterface
     */
    protected $companyFacade;

    /**
     * @param \Spryker\Zed\Company\Business\CompanyFacadeInterface $companyFacade
     */
    public function __construct($companyFacade)
    {
        $this->companyFacade = $companyFacade;
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer|null
     */
    public function findCompanyById(int $idCompany): ?CompanyTransfer
    {
        return $this->companyFacade->findCompanyById($idCompany);
    }
}
