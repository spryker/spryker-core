<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspAssetManagement;

use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Service\FileManager\FileManagerServiceInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerFeature\Client\SspAssetManagement\SspAssetManagementClientInterface;
use SprykerFeature\Yves\SspAssetManagement\Form\DataProvider\SspAssetFormDataProvider;
use SprykerFeature\Yves\SspAssetManagement\Form\DataProvider\SspAssetFormDataProviderInterface;
use SprykerFeature\Yves\SspAssetManagement\Form\DataProvider\SspAssetSearchFormDataProvider;
use SprykerFeature\Yves\SspAssetManagement\Form\SspAssetBusinessUnitRelationsForm;
use SprykerFeature\Yves\SspAssetManagement\Form\SspAssetForm;
use SprykerFeature\Yves\SspAssetManagement\Form\SspAssetSearchForm;
use SprykerFeature\Yves\SspAssetManagement\Handler\SspAssetSearchFormHandler;
use SprykerFeature\Yves\SspAssetManagement\Handler\SspAssetSearchFormHandlerInterface;
use SprykerFeature\Yves\SspAssetManagement\Mapper\SspAssetFormDataToTransferMapper;
use SprykerFeature\Yves\SspAssetManagement\Mapper\SspAssetFormDataToTransferMapperInterface;
use SprykerFeature\Yves\SspAssetManagement\Permission\SspAssetCustomerPermissionChecker;
use SprykerFeature\Yves\SspAssetManagement\Permission\SspAssetCustomerPermissionCheckerInterface;
use SprykerFeature\Yves\SspAssetManagement\Reader\SspAssetReader;
use SprykerFeature\Yves\SspAssetManagement\Reader\SspAssetReaderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerFeature\Client\SspAssetManagement\SspAssetManagementClientInterface getClient()
 * @method \SprykerFeature\Yves\SspAssetManagement\SspAssetManagementConfig getConfig()
 */
class SspAssetManagementFactory extends AbstractFactory
{
    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer|null $sspAssetTransfer
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAssetForm(?SspAssetTransfer $sspAssetTransfer = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(SspAssetForm::class, $sspAssetTransfer, $options);
    }

    /**
     * @param array<mixed> $formData
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createSspAssetBusinessUnitRelationsForm(array $formData = []): FormInterface
    {
        return $this->getFormFactory()->create(SspAssetBusinessUnitRelationsForm::class, $formData);
    }

    /**
     * @return \SprykerFeature\Yves\SspAssetManagement\Form\DataProvider\SspAssetFormDataProviderInterface
     */
    public function createSspAssetFormDataProvider(): SspAssetFormDataProviderInterface
    {
        return new SspAssetFormDataProvider($this->getSspAssetManagementClient(), $this->getConfig());
    }

    /**
     * @return \SprykerFeature\Yves\SspAssetManagement\Mapper\SspAssetFormDataToTransferMapperInterface
     */
    public function createSspAssetFormDataToTransferMapper(): SspAssetFormDataToTransferMapperInterface
    {
        return new SspAssetFormDataToTransferMapper();
    }

    /**
     * @return \SprykerFeature\Yves\SspAssetManagement\Permission\SspAssetCustomerPermissionCheckerInterface
     */
    public function createSspAssetCustomerPermissionChecker(): SspAssetCustomerPermissionCheckerInterface
    {
        return new SspAssetCustomerPermissionChecker();
    }

    /**
     * @return \Spryker\Client\CompanyUser\CompanyUserClientInterface
     */
    public function getCompanyUserClient(): CompanyUserClientInterface
    {
        return $this->getProvidedDependency(SspAssetManagementDependencyProvider::CLIENT_COMPANY_USER);
    }

    /**
     * @return \Symfony\Component\Form\FormFactoryInterface
     */
    public function getFormFactory(): FormFactoryInterface
    {
        return $this->getProvidedDependency(SspAssetManagementDependencyProvider::FORM_FACTORY);
    }

    /**
     * @return \SprykerFeature\Client\SspAssetManagement\SspAssetManagementClientInterface
     */
    public function getSspAssetManagementClient(): SspAssetManagementClientInterface
    {
        return $this->getProvidedDependency(SspAssetManagementDependencyProvider::CLIENT_SSP_ASSET_MANAGEMENT);
    }

    /**
     * @return \Spryker\Service\FileManager\FileManagerServiceInterface
     */
    public function getFileManagerService(): FileManagerServiceInterface
    {
        return $this->getProvidedDependency(SspAssetManagementDependencyProvider::SERVICE_FILE_MANAGER);
    }

    /**
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createSspAssetSearchForm(array $options): FormInterface
    {
        return $this->getFormFactory()->create(SspAssetSearchForm::class, [], $options);
    }

    /**
     * @return \SprykerFeature\Yves\SspAssetManagement\Form\DataProvider\SspAssetSearchFormDataProvider
     */
    public function createSspAssetSearchFormDataProvider(): SspAssetSearchFormDataProvider
    {
        return new SspAssetSearchFormDataProvider();
    }

    /**
     * @return \SprykerFeature\Yves\SspAssetManagement\Handler\SspAssetSearchFormHandlerInterface
     */
    public function createSspAssetSearchFormHandler(): SspAssetSearchFormHandlerInterface
    {
        return new SspAssetSearchFormHandler();
    }

    /**
     * @return \SprykerFeature\Yves\SspAssetManagement\Reader\SspAssetReaderInterface
     */
    public function createSspAssetReader(): SspAssetReaderInterface
    {
        return new SspAssetReader(
            $this->getSspAssetManagementClient(),
            $this->getConfig(),
        );
    }
}
