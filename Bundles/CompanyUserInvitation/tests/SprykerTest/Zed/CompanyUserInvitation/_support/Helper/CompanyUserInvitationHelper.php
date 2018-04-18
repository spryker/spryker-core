<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUserInvitation\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyUserInvitationBuilder;
use Generated\Shared\Transfer\CompanyUserInvitationCreateRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationDeleteRequestTransfer;
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
        $companyUserInvitationCreateRequestTransfer = (new CompanyUserInvitationCreateRequestTransfer())
            ->setIdCompanyUser($companyUserInvitationTransfer->getFkCompanyUser())
            ->setCompanyUserInvitation($companyUserInvitationTransfer);

        $companyUserInvitationTransfer = $this->getCompanyUserInvitationFacade()
            ->createCompanyUserInvitation($companyUserInvitationCreateRequestTransfer)
            ->getCompanyUserInvitation();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($companyUserInvitationTransfer) {
            $companyUserInvitationDeleteRequestTransfer = (new CompanyUserInvitationDeleteRequestTransfer())
                ->setIdCompanyUser($companyUserInvitationTransfer->getFkCompanyUser())
                ->setCompanyUserInvitation($companyUserInvitationTransfer);
            $this->getCompanyUserInvitationFacade()
                ->deleteCompanyUserInvitation($companyUserInvitationDeleteRequestTransfer);
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
