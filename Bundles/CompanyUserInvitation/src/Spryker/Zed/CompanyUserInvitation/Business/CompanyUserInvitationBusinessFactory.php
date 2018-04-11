<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business;

use Spryker\Zed\CompanyUserInvitation\Business\Model\Hydrator\InvitationHydrator;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Importer\InvitationImporter;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Reader\InvitationReader;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Validator\InvitationValidator;
use Spryker\Zed\CompanyUserInvitation\CompanyUserInvitationDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface getEntityManager()
 */
class CompanyUserInvitationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Business\Model\Importer\InvitationImporterInterface
     */
    public function createInvitationImporter()
    {
        return new InvitationImporter(
            $this->getEntityManager(),
            $this->createInvitationValidator(),
            $this->createInvitationHydrator()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Business\Model\Reader\InvitationReaderInterface
     */
    public function createInvitationReader()
    {
        return new InvitationReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Business\Model\Validator\InvitationValidatorInterface
     */
    protected function createInvitationValidator()
    {
        return new InvitationValidator(
            $this->getRepository(),
            $this->getCompanyUserFacade(),
            $this->getCompanyBusinessUnitFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Business\Model\Hydrator\InvitationHydratorInterface
     */
    protected function createInvitationHydrator()
    {
        return new InvitationHydrator(
            $this->getRepository(),
            $this->getCompanyUserFacade(),
            $this->getCompanyBusinessUnitFacade(),
            $this->getUtilTextService()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyUserFacadeInterface
     */
    protected function getCompanyUserFacade()
    {
        return $this->getProvidedDependency(CompanyUserInvitationDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyBusinessUnitFacadeInterface
     */
    protected function getCompanyBusinessUnitFacade()
    {
        return $this->getProvidedDependency(CompanyUserInvitationDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Dependency\Service\CompanyUserInvitationToUtilTextInterface
     */
    protected function getUtilTextService()
    {
        return $this->getProvidedDependency(CompanyUserInvitationDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
