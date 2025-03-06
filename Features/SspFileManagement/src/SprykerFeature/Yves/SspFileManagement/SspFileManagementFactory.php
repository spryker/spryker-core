<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspFileManagement;

use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface;
use Spryker\Service\FileManager\FileManagerServiceInterface;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerFeature\Yves\SspFileManagement\Form\DataProvider\FileSearchFilterFormDataProvider;
use SprykerFeature\Yves\SspFileManagement\Form\FileSearchFilterForm;
use SprykerFeature\Yves\SspFileManagement\Form\Handler\FileSearchFilterFormHandler;
use SprykerFeature\Yves\SspFileManagement\Form\Handler\FileSearchFilterFormHandlerInterface;
use SprykerFeature\Yves\SspFileManagement\Formatter\TimeZoneFormatter;
use SprykerFeature\Yves\SspFileManagement\Formatter\TimeZoneFormatterInterface;
use SprykerFeature\Yves\SspFileManagement\Reader\CompanyUserReader;
use SprykerFeature\Yves\SspFileManagement\Reader\CompanyUserReaderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerFeature\Yves\SspFileManagement\SspFileManagementConfig getConfig()
 * @method \SprykerFeature\Client\SspFileManagement\SspFileManagementClientInterface getClient()
 */
class SspFileManagementFactory extends AbstractFactory
{
    /**
     * @return \SprykerFeature\Yves\SspFileManagement\Form\DataProvider\FileSearchFilterFormDataProvider
     */
    public function createFileSearchFilterFormDataProvider(): FileSearchFilterFormDataProvider
    {
        return new FileSearchFilterFormDataProvider($this->getConfig(), $this->getGlossaryStorageClient());
    }

    /**
     * @param string $localeName
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFileSearchFilterForm(string $localeName): FormInterface
    {
        return $this->getFormFactory()->create(
            FileSearchFilterForm::class,
            null,
            $this->createFileSearchFilterFormDataProvider()->getOptions($localeName),
        );
    }

    /**
     * @return \SprykerFeature\Yves\SspFileManagement\Reader\CompanyUserReaderInterface
     */
    public function createCompanyUserReader(): CompanyUserReaderInterface
    {
        return new CompanyUserReader($this->getCompanyUserClient());
    }

    /**
     * @return \SprykerFeature\Yves\SspFileManagement\Form\Handler\FileSearchFilterFormHandlerInterface
     */
    public function createFileSearchFilterHandler(): FileSearchFilterFormHandlerInterface
    {
        return new FileSearchFilterFormHandler(
            $this->createCompanyUserReader(),
            $this->getClient(),
            $this->createTimeZoneFormatter(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Client\CompanyUser\CompanyUserClientInterface
     */
    public function getCompanyUserClient(): CompanyUserClientInterface
    {
        return $this->getProvidedDependency(SspFileManagementDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \SprykerFeature\Yves\SspFileManagement\Formatter\TimeZoneFormatterInterface
     */
    public function createTimeZoneFormatter(): TimeZoneFormatterInterface
    {
        return new TimeZoneFormatter($this->getConfig());
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return \Spryker\Client\GlossaryStorage\GlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): GlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(SspFileManagementDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \Spryker\Service\FileManager\FileManagerServiceInterface
     */
    public function getFileManagerService(): FileManagerServiceInterface
    {
        return $this->getProvidedDependency(SspFileManagementDependencyProvider::SERVICE_FILE_MANAGER);
    }
}
