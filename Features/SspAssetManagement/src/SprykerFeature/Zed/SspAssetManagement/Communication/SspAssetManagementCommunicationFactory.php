<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Communication;

use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Orm\Zed\SspAssetManagement\Persistence\SpySspAssetQuery;
use Orm\Zed\SspAssetManagement\Persistence\SpySspAssetToCompanyBusinessUnitQuery;
use Orm\Zed\SspInquiryManagement\Persistence\SpySspInquirySspAssetQuery;
use Spryker\Service\FileManager\FileManagerServiceInterface;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Zed\Company\Business\CompanyFacadeInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use SprykerFeature\Zed\SspAssetManagement\Communication\Form\DataProvider\SspAssetFilterFormDataProvider;
use SprykerFeature\Zed\SspAssetManagement\Communication\Form\DataProvider\SspAssetFilterFormDataProviderInterface;
use SprykerFeature\Zed\SspAssetManagement\Communication\Form\DataProvider\SspAssetFormDataProvider;
use SprykerFeature\Zed\SspAssetManagement\Communication\Form\DataProvider\SspAssetFormDataProviderInterface;
use SprykerFeature\Zed\SspAssetManagement\Communication\Form\SspAssetFilterForm;
use SprykerFeature\Zed\SspAssetManagement\Communication\Form\SspAssetForm;
use SprykerFeature\Zed\SspAssetManagement\Communication\Mapper\SspAssetFormDataToTransferMapper;
use SprykerFeature\Zed\SspAssetManagement\Communication\Mapper\SspAssetFormDataToTransferMapperInterface;
use SprykerFeature\Zed\SspAssetManagement\Communication\Table\AssignedBusinessUnitTable;
use SprykerFeature\Zed\SspAssetManagement\Communication\Table\SspAssetTable;
use SprykerFeature\Zed\SspAssetManagement\Communication\Table\SspInquiryTable;
use SprykerFeature\Zed\SspAssetManagement\Communication\Tabs\SspAssetTabs;
use SprykerFeature\Zed\SspAssetManagement\SspAssetManagementDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerFeature\Zed\SspAssetManagement\SspAssetManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementEntityManagerInterface getEntityManager()
 * @method \SprykerFeature\Zed\SspAssetManagement\Persistence\SspAssetManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspAssetManagement\Business\SspAssetManagementFacadeInterface getFacade()
 */
class SspAssetManagementCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer|null $sspAssetTransfer
     * @param array<string, mixed> $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createSspAssetForm(?SspAssetTransfer $sspAssetTransfer, array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(SspAssetForm::class, $sspAssetTransfer, $formOptions);
    }

    /**
     * @return \SprykerFeature\Zed\SspAssetManagement\Communication\Form\DataProvider\SspAssetFormDataProviderInterface
     */
    public function createSspAssetFormDataProvider(): SspAssetFormDataProviderInterface
    {
        return new SspAssetFormDataProvider(
            $this->getFacade(),
            $this->getConfig(),
            $this->getCompanyBusinessUnitFacade(),
            $this->getCompanyFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspAssetManagement\Communication\Mapper\SspAssetFormDataToTransferMapperInterface
     */
    public function createSspAssetFormDataToTransferMapper(): SspAssetFormDataToTransferMapperInterface
    {
        return new SspAssetFormDataToTransferMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SspAssetManagement\Communication\Tabs\SspAssetTabs
     */
    public function createSspAssetTabs(): SspAssetTabs
    {
        return new SspAssetTabs();
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \SprykerFeature\Zed\SspAssetManagement\Communication\Table\AssignedBusinessUnitTable
     */
    public function createAssignedBusinessUnitTable(SspAssetTransfer $sspAssetTransfer): AssignedBusinessUnitTable
    {
        return new AssignedBusinessUnitTable(
            $sspAssetTransfer,
            $this->getSspAssetToCompanyBusinessUnitQuery(),
            $this->getUtilDateTimeService(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \SprykerFeature\Zed\SspAssetManagement\Communication\Table\SspInquiryTable
     */
    public function createSspInquiryTable(SspAssetTransfer $sspAssetTransfer): SspInquiryTable
    {
        return new SspInquiryTable(
            $sspAssetTransfer,
            $this->getSspInquirySspAssetQuery(),
            $this->getUtilDateTimeService(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetConditionsTransfer $sspAssetConditionsTransfer
     *
     * @return \SprykerFeature\Zed\SspAssetManagement\Communication\Table\SspAssetTable
     */
    public function createSspAssetTable(SspAssetConditionsTransfer $sspAssetConditionsTransfer): SspAssetTable
    {
        return new SspAssetTable(
            $this->getSspAssetQuery(),
            $this->getUtilDateTimeService(),
            $sspAssetConditionsTransfer,
            $this->createSspAssetFormDataProvider(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Orm\Zed\SspAssetManagement\Persistence\SpySspAssetToCompanyBusinessUnitQuery
     */
    public function getSspAssetToCompanyBusinessUnitQuery(): SpySspAssetToCompanyBusinessUnitQuery
    {
        return SpySspAssetToCompanyBusinessUnitQuery::create();
    }

    /**
     * @return \Orm\Zed\SspInquiryManagement\Persistence\SpySspInquirySspAssetQuery
     */
    public function getSspInquirySspAssetQuery(): SpySspInquirySspAssetQuery
    {
        return SpySspInquirySspAssetQuery::create();
    }

    /**
     * @return \Orm\Zed\SspAssetManagement\Persistence\SpySspAssetQuery
     */
    public function getSspAssetQuery(): SpySspAssetQuery
    {
        return SpySspAssetQuery::create();
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetConditionsTransfer $sspAssetConditionsTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSspAssetFilterForm(SspAssetConditionsTransfer $sspAssetConditionsTransfer): FormInterface
    {
        return $this->getFormFactory()->create(SspAssetFilterForm::class, $sspAssetConditionsTransfer, $this->createSspAssetFilterFormDataProvider()->getOptions());
    }

    /**
     * @return \SprykerFeature\Zed\SspAssetManagement\Communication\Form\DataProvider\SspAssetFilterFormDataProviderInterface
     */
    public function createSspAssetFilterFormDataProvider(): SspAssetFilterFormDataProviderInterface
    {
        return new SspAssetFilterFormDataProvider(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Service\FileManager\FileManagerServiceInterface
     */
    public function getFileManagerService(): FileManagerServiceInterface
    {
        return $this->getProvidedDependency(SspAssetManagementDependencyProvider::SERVICE_FILE_MANAGER);
    }

    /**
     * @return \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): UtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SspAssetManagementDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade(): CompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(SspAssetManagementDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Spryker\Zed\Company\Business\CompanyFacadeInterface
     */
    public function getCompanyFacade(): CompanyFacadeInterface
    {
        return $this->getProvidedDependency(SspAssetManagementDependencyProvider::FACADE_COMPANY);
    }
}
