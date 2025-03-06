<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspFileManagement\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use SprykerFeature\Yves\SspFileManagement\Exception\SspFileManagementAccessDeniedHttpException;

class CompanyUserReader implements CompanyUserReaderInterface
{
 /**
  * @param \Spryker\Client\CompanyUser\CompanyUserClientInterface $companyUserClient
  */
    public function __construct(protected CompanyUserClientInterface $companyUserClient)
    {
    }

    /**
     * @throws \SprykerFeature\Yves\SspFileManagement\Exception\SspFileManagementAccessDeniedHttpException
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCurrentCompanyUser(): CompanyUserTransfer
    {
        $companyUserTransfer = $this->companyUserClient->findCompanyUser();
        if (!$companyUserTransfer) {
            throw new SspFileManagementAccessDeniedHttpException('Only company users are allowed to access this page!');
        }

        return $companyUserTransfer;
    }
}
