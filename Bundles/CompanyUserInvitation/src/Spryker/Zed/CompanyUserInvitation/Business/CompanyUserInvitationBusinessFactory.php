<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation\Business;

use Spryker\Zed\CompanyUserInvitation\Business\Model\Deleter\InvitationDeleter;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Hydrator\InvitationHydrator;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Importer\InvitationImporter;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Installer\CompanyUserInvitationStatusInstaller;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Mailer\InvitationMailer;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Reader\InvitationReader;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Sender\InvitationSender;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Updater\InvitationUpdater;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Validator\InvitationValidator;
use Spryker\Zed\CompanyUserInvitation\Business\Model\Writer\InvitationWriter;
use Spryker\Zed\CompanyUserInvitation\CompanyUserInvitationDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyUserInvitation\Persistence\CompanyUserInvitationEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanyUserInvitation\CompanyUserInvitationConfig getConfig()
 */
class CompanyUserInvitationBusinessFactory extends AbstractBusinessFactory
{
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
     * @return \Spryker\Zed\CompanyUserInvitation\Business\Model\Importer\InvitationImporterInterface
     */
    public function createInvitationImporter()
    {
        return new InvitationImporter(
            $this->createInvitationWriter()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Business\Model\Writer\InvitationWriterInterface
     */
    public function createInvitationWriter()
    {
        return new InvitationWriter(
            $this->getEntityManager(),
            $this->createInvitationValidator(),
            $this->createInvitationHydrator()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Business\Model\Deleter\InvitationDeleterInterface
     */
    public function createInvitationDeleter()
    {
        return new InvitationDeleter(
            $this->getEntityManager(),
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
     * @return \Spryker\Zed\CompanyUserInvitation\Business\Model\Sender\InvitationSenderInterface
     */
    public function createInvitationSender()
    {
        return new InvitationSender(
            $this->createInvitationReader(),
            $this->createInvitationUpdater(),
            $this->createInvitationMailer()
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
     * @return \Spryker\Zed\CompanyUserInvitation\Business\Model\Mailer\InvitationMailerInterface
     */
    public function createInvitationMailer()
    {
        return new InvitationMailer(
            $this->getConfig(),
            $this->getMailFacade()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Business\Model\Installer\CompanyUserInvitationStatusInstallerInterface
     */
    public function createInstaller()
    {
        return new CompanyUserInvitationStatusInstaller(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getConfig()
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
     * @return \Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToMailFacadeInterface
     */
    protected function getMailFacade()
    {
        return $this->getProvidedDependency(CompanyUserInvitationDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\CompanyUserInvitation\Dependency\Service\CompanyUserInvitationToUtilTextInterface
     */
    protected function getUtilTextService()
    {
        return $this->getProvidedDependency(CompanyUserInvitationDependencyProvider::SERVICE_UTIL_TEXT);
    }
}
