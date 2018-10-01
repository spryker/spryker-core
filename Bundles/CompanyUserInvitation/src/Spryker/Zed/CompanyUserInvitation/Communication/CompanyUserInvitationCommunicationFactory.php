<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Communication;

use Spryker\Zed\CompanyUserInvitation\Business\Model\PostRegistration\CompanyUserCreator;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Updater\InvitationUpdater;
use Spryker\Zed\CompanyUserInvitation\CompanyUserInvitationDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Spryker\Zed\CompanyUserInvitation\CompanyUserInvitationConfig getConfig()
 * @method \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface getEntityManager()
 */
class CompanyUserInvitationCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Business\Model\PostRegistration\CompanyUserCreatorInterface
     */
    public function createCompanyUserCreator()
    {
        return new CompanyUserCreator(
            $this->getRepository(),
            $this->getCompanyUserFacade(),
            $this->createInvitationUpdater()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Business\Model\Updater\InvitationUpdaterInterface
     */
    public function createInvitationUpdater()
    {
        return new InvitationUpdater(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade()
    {
        return $this->getProvidedDependency(CompanyUserInvitationDependencyProvider::FACADE_COMPANY_USER);
    }
}
