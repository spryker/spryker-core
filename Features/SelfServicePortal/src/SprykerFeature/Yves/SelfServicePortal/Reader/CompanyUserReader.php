<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Reader;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CompanyUserReader implements CompanyUserReaderInterface
{
    public function __construct(protected CompanyUserClientInterface $companyUserClient)
    {
    }

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function getCurrentCompanyUser(): CompanyUserTransfer
    {
        $companyUserTransfer = $this->companyUserClient->findCompanyUser();
        if (!$companyUserTransfer) {
            throw new AccessDeniedHttpException('Only company users are allowed to access the page!');
        }

        return $companyUserTransfer;
    }
}
