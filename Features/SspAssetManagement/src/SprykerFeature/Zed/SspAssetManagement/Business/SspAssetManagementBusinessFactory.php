<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Business;

use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use SprykerFeature\Zed\SspAssetManagement\Business\DashboardDataProvider\DashboardDataProvider;
use SprykerFeature\Zed\SspAssetManagement\Business\DashboardDataProvider\DashboardDataProviderInterface;
use SprykerFeature\Zed\SspAssetManagement\Business\Permission\SspAssetCustomerPermissionExpander;
use SprykerFeature\Zed\SspAssetManagement\Business\Permission\SspAssetCustomerPermissionExpanderInterface;
use SprykerFeature\Zed\SspAssetManagement\Business\Reader\SspAssetReader;
use SprykerFeature\Zed\SspAssetManagement\Business\Reader\SspAssetReaderInterface;
use SprykerFeature\Zed\SspAssetManagement\Business\Validator\SspAssetValidator;
use SprykerFeature\Zed\SspAssetManagement\Business\Validator\SspAssetValidatorInterface;
use SprykerFeature\Zed\SspAssetManagement\Business\Writer\FileSspAssetWriter;
use SprykerFeature\Zed\SspAssetManagement\Business\Writer\FileSspAssetWriterInterface;
use SprykerFeature\Zed\SspAssetManagement\Business\Writer\SspAssetWriter;
use SprykerFeature\Zed\SspAssetManagement\Business\Writer\SspAssetWriterInterface;
use SprykerFeature\Zed\SspAssetManagement\SspAssetManagementDependencyProvider;

/**
 * @method \SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementEntityManagerInterface getEntityManager()
 * @method \SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspAssetManagement\SspAssetManagementConfig getConfig()
 */
class SspAssetManagementBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerFeature\Zed\SspAssetManagement\Business\Reader\SspAssetReaderInterface
     */
    public function createSspAssetReader(): SspAssetReaderInterface
    {
        return new SspAssetReader(
            $this->getRepository(),
            $this->getFileManagerFacade(),
            $this->getSspAssetManagementExpanderPlugins(),
            $this->createSspAssetCustomerPermissionExpander(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspAssetManagement\Business\Writer\SspAssetWriterInterface
     */
    public function createSspAssetWriter(): SspAssetWriterInterface
    {
        return new SspAssetWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createSspAssetValidator(),
            $this->getSequenceNumberFacade(),
            $this->getConfig(),
            $this->createFileSspAssetWriter(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspAssetManagement\Business\Writer\FileSspAssetWriterInterface
     */
    public function createFileSspAssetWriter(): FileSspAssetWriterInterface
    {
        return new FileSspAssetWriter(
            $this->getFileManagerFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspAssetManagement\Business\Validator\SspAssetValidatorInterface
     */
    public function createSspAssetValidator(): SspAssetValidatorInterface
    {
        return new SspAssetValidator();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade(): CompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(SspAssetManagementDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface
     */
    public function getSequenceNumberFacade(): SequenceNumberFacadeInterface
    {
        return $this->getProvidedDependency(SspAssetManagementDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface
     */
    public function getFileManagerFacade(): FileManagerFacadeInterface
    {
        return $this->getProvidedDependency(SspAssetManagementDependencyProvider::FACADE_FILE_MANAGER);
    }

    /**
     * @return array<\SprykerFeature\Zed\SspAssetManagement\Dependency\Plugin\SspAssetManagementExpanderPluginInterface>
     */
    public function getSspAssetManagementExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SspAssetManagementDependencyProvider::PLUGINS_SSP_ASSET_MANAGEMENT_EXPANDER);
    }

    /**
     * @return \SprykerFeature\Zed\SspAssetManagement\Business\DashboardDataProvider\DashboardDataProviderInterface
     */
    public function createDashboardDataProvider(): DashboardDataProviderInterface
    {
        return new DashboardDataProvider($this->createSspAssetReader(), $this->createSspAssetCustomerPermissionExpander());
    }

    /**
     * @return \SprykerFeature\Zed\SspAssetManagement\Business\Permission\SspAssetCustomerPermissionExpanderInterface
     */
    public function createSspAssetCustomerPermissionExpander(): SspAssetCustomerPermissionExpanderInterface
    {
        return new SspAssetCustomerPermissionExpander();
    }
}
