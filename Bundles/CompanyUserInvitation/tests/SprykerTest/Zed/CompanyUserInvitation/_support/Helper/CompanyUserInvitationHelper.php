<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUserInvitation\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyUserInvitationBuilder;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyUserInvitationHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    public function haveCompanyUserInvitation(array $seedData = []): CompanyUserInvitationTransfer
    {
        $companyUserInvitationTransfer = (new CompanyUserInvitationBuilder($seedData))->build();
        $companyUserInvitationTransfer->setIdCompanyUserInvitation(null);

        $companyUserInvitationTransfer = $this->getCompanyUserInvitationFacade()
            ->createCompanyUserInvitation($companyUserInvitationTransfer)
            ->getCompanyUserInvitation();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($companyUserInvitationTransfer) {
            $this->getCompanyUserInvitationFacade()
                ->deleteCompanyUserInvitation($companyUserInvitationTransfer);
        });

        return $companyUserInvitationTransfer;
    }

    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Business\CompanyUserInvitationFacadeInterface
     */
    protected function getCompanyUserInvitationFacade()
    {
        return $this->getLocator()->companyUserInvitation()->facade();
    }
}
