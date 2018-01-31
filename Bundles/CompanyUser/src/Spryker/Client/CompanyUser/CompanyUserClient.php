<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUser;

use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CompanyUser\CompanyUserFactory getFactory()
 */
class CompanyUserClient extends AbstractClient implements CompanyUserClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    public function createCompanyUser(CompanyUserTransfer $companyUserTransfer): CompanyUserResponseTransfer
    {
        return $this->getFactory()->createZedCompanyUserStub()->createCompanyUser($companyUserTransfer);
    }

    /**
     * Specification:
     * - Retrieve current company user information from session.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function getCompanyUser(): ?CompanyUserTransfer
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customerTransfer !== null) {
            $companyUserTransfer = $this->getFactory()->createZedCompanyUserStub()
                ->findCompanyUserByCustomerId($customerTransfer);

            return $companyUserTransfer;
        }

        return null;
    }
}
