<?php

/**
 * Copyright © 2018-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUserInvitation\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyUserInvitationBuilder;
use Generated\Shared\Transfer\CompanyUserInvitationCreateRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationDeleteRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationGetCollectionRequestTransfer;
use Generated\Shared\Transfer\CompanyUserInvitationTransfer;
use Spryker\Zed\CompanyUserInvitation\Business\CompanyUserInvitationFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\DependencyHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyUserInvitationHelper extends Module
{
    use DataCleanupHelperTrait;
    use DependencyHelperTrait;
    use LocatorHelperTrait;

    /**
     * @var array
     */
    public $dependencies = [];

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
            $this->setDependencies();

            $companyUserInvitationDeleteRequestTransfer = (new CompanyUserInvitationDeleteRequestTransfer())
                ->setIdCompanyUser($companyUserInvitationTransfer->getFkCompanyUser())
                ->setCompanyUserInvitation($companyUserInvitationTransfer);
            $this->getCompanyUserInvitationFacade()
                ->deleteCompanyUserInvitation($companyUserInvitationDeleteRequestTransfer);
        });

        return $companyUserInvitationTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyUserInvitationTransfer
     */
    public function createCompanyUserInvitationTransfer(array $seedData = []): CompanyUserInvitationTransfer
    {
        $companyUserInvitationTransfer = (new CompanyUserInvitationBuilder($seedData))->build();
        $companyUserInvitationTransfer->requireFkCompanyUser();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($companyUserInvitationTransfer) {
            $this->setDependencies();

            $criteriaFilter = (new CompanyUserInvitationCriteriaFilterTransfer())
                ->setFkCompanyUser($companyUserInvitationTransfer->getFkCompanyUser());

            $companyUserInvitationGetCollectionRequestTransfer = (new CompanyUserInvitationGetCollectionRequestTransfer())
                ->setIdCompanyUser($companyUserInvitationTransfer->getFkCompanyUser())
                ->setCriteriaFilter($criteriaFilter);

            $companyUserInvitations = $this->getCompanyUserInvitationFacade()->getCompanyUserInvitationCollection(
                $companyUserInvitationGetCollectionRequestTransfer
            )->getCompanyUserInvitations();

            foreach ($companyUserInvitations as $companyUserInvitation) {
                $companyUserInvitationDeleteRequestTransfer = (new CompanyUserInvitationDeleteRequestTransfer())
                    ->setIdCompanyUser($companyUserInvitation->getFkCompanyUser())
                    ->setCompanyUserInvitation($companyUserInvitation);
                $this->getCompanyUserInvitationFacade()->deleteCompanyUserInvitation($companyUserInvitationDeleteRequestTransfer);
            }
        });

        return $companyUserInvitationTransfer;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function addDependency($key, $value): void
    {
        $this->dependencies[$key] = $value;
        $this->setDependencies();
    }

    /**
     * @return void
     */
    protected function setDependencies(): void
    {
        foreach ($this->dependencies as $key => $value) {
            $this->setDependency($key, $value);
        }
    }

    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Business\CompanyUserInvitationFacadeInterface
     */
    protected function getCompanyUserInvitationFacade(): CompanyUserInvitationFacadeInterface
    {
        return $this->getLocator()->companyUserInvitation()->facade();
    }
}
