<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserTransfer;

class QuoteApprovalToCompanyUserClientBridge implements QuoteApprovalToCompanyUserClientInterface
{
    /**
     * @var \Spryker\Client\CompanyUser\CompanyUserClientInterface
     */
    protected $companyUserClient;

    /**
     * @param \Spryker\Client\CompanyUser\CompanyUserClientInterface $companyUserClient
     */
    public function __construct($companyUserClient)
    {
        $this->companyUserClient = $companyUserClient;
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findCompanyUser(): ?CompanyUserTransfer
    {
        return $this->companyUserClient->findCompanyUser();
    }
}
