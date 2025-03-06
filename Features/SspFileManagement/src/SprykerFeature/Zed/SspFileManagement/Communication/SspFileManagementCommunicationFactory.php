<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Communication;

use Generated\Shared\Transfer\FileAttachmentFileTableCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentFileViewDetailTableCriteriaTransfer;
use Orm\Zed\FileManager\Persistence\SpyFileInfoQuery;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Zed\Company\Business\CompanyFacadeInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\AttachFileForm;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\DataProvider\FileAttachFormDataProvider;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\DataProvider\FileTableFilterFormDataProvider;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\DataProvider\FileViewDetailTableFilterFormDataProvider;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\DeleteFileForm;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\FileTableFilterForm;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\FileViewDetailTableFilterForm;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\UnlinkFileForm;
use SprykerFeature\Zed\SspFileManagement\Communication\Form\UploadFileForm;
use SprykerFeature\Zed\SspFileManagement\Communication\Formatter\FileSizeFormatter;
use SprykerFeature\Zed\SspFileManagement\Communication\Formatter\FileSizeFormatterInterface;
use SprykerFeature\Zed\SspFileManagement\Communication\Formatter\TimeZoneFormatter;
use SprykerFeature\Zed\SspFileManagement\Communication\Formatter\TimeZoneFormatterInterface;
use SprykerFeature\Zed\SspFileManagement\Communication\Mapper\FileAttachmentMapper;
use SprykerFeature\Zed\SspFileManagement\Communication\Mapper\FileAttachmentMapperInterface;
use SprykerFeature\Zed\SspFileManagement\Communication\Mapper\FileUploadMapper;
use SprykerFeature\Zed\SspFileManagement\Communication\Mapper\FileUploadMapperInterface;
use SprykerFeature\Zed\SspFileManagement\Communication\Reader\FileReader;
use SprykerFeature\Zed\SspFileManagement\Communication\Reader\FileReaderInterface;
use SprykerFeature\Zed\SspFileManagement\Communication\ReferenceGenerator\FileReferenceGenerator;
use SprykerFeature\Zed\SspFileManagement\Communication\ReferenceGenerator\FileReferenceGeneratorInterface;
use SprykerFeature\Zed\SspFileManagement\Communication\Saver\FileSaver;
use SprykerFeature\Zed\SspFileManagement\Communication\Saver\FileSaverInterface;
use SprykerFeature\Zed\SspFileManagement\Communication\Table\FileTable;
use SprykerFeature\Zed\SspFileManagement\Communication\Table\FileViewDetailTable;
use SprykerFeature\Zed\SspFileManagement\SspFileManagementDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementQueryContainer getQueryContainer()
 * @method \SprykerFeature\Zed\SspFileManagement\SspFileManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspFileManagement\Business\SspFileManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementEntityManagerInterface getEntityManager()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface getRepository()
 */
class SspFileManagementCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileTableCriteriaTransfer $fileAttachmentFileTableCriteriaTransfer
     *
     * @return \SprykerFeature\Zed\SspFileManagement\Communication\Table\FileTable
     */
    public function createFileTable(
        FileAttachmentFileTableCriteriaTransfer $fileAttachmentFileTableCriteriaTransfer
    ): FileTable {
        return new FileTable(
            $this->getFilePropelQuery(),
            $this->createFileSizeFormatter(),
            $this->getDateTimeService(),
            $this->createTimeZoneFormatter(),
            $fileAttachmentFileTableCriteriaTransfer,
        );
    }

    /**
     * @param int $idFile
     * @param \Generated\Shared\Transfer\FileAttachmentFileViewDetailTableCriteriaTransfer $fileAttachmentFileViewDetailTableCriteriaTransfer
     *
     * @return \SprykerFeature\Zed\SspFileManagement\Communication\Table\FileViewDetailTable
     */
    public function createFileViewDetailTable(
        int $idFile,
        FileAttachmentFileViewDetailTableCriteriaTransfer $fileAttachmentFileViewDetailTableCriteriaTransfer
    ): FileViewDetailTable {
        return new FileViewDetailTable(
            $this->getFilePropelQuery(),
            $idFile,
            $this->getDateTimeService(),
            $this->createTimeZoneFormatter(),
            $fileAttachmentFileViewDetailTableCriteriaTransfer,
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Communication\Formatter\FileSizeFormatterInterface
     */
    public function createFileSizeFormatter(): FileSizeFormatterInterface
    {
        return new FileSizeFormatter();
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Communication\Mapper\FileUploadMapperInterface
     */
    public function createFileUploadMapper(): FileUploadMapperInterface
    {
        return new FileUploadMapper($this->getConfig());
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Communication\Saver\FileSaverInterface
     */
    public function createFileSaver(): FileSaverInterface
    {
        return new FileSaver(
            $this->createFileUploadMapper(),
            $this->getFileManagerFacade(),
            $this->createFileReferenceGenerator(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Communication\ReferenceGenerator\FileReferenceGeneratorInterface
     */
    public function createFileReferenceGenerator(): FileReferenceGeneratorInterface
    {
        return new FileReferenceGenerator(
            $this->getSequenceNumberFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Communication\Reader\FileReaderInterface
     */
    public function createFileReader(): FileReaderInterface
    {
        return new FileReader($this->getFileManagerFacade());
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createUploadFileForm(): FormInterface
    {
        return $this->getFormFactory()->create(UploadFileForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createUnlinkFileForm(): FormInterface
    {
        return $this->getFormFactory()->create(UnlinkFileForm::class);
    }

    /**
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createDeleteFileForm(): FormInterface
    {
        return $this->getFormFactory()->create(DeleteFileForm::class);
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileTableCriteriaTransfer $fileAttachmentFileTableCriteriaTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFileTableFilterForm(
        FileAttachmentFileTableCriteriaTransfer $fileAttachmentFileTableCriteriaTransfer
    ): FormInterface {
        $dataProvider = $this->createFileTableFilterFormDataProvider();

        return $this->getFormFactory()->create(
            FileTableFilterForm::class,
            $fileAttachmentFileTableCriteriaTransfer,
            $dataProvider->getOptions(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Communication\Form\DataProvider\FileTableFilterFormDataProvider
     */
    public function createFileTableFilterFormDataProvider(): FileTableFilterFormDataProvider
    {
        return new FileTableFilterFormDataProvider(
            $this->getFileInfoPropelQuery(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Communication\Form\DataProvider\FileViewDetailTableFilterFormDataProvider
     */
    public function createFileViewDetailTableFilterFormDataProvider(): FileViewDetailTableFilterFormDataProvider
    {
        return new FileViewDetailTableFilterFormDataProvider(
            $this->getConfig(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileViewDetailTableCriteriaTransfer $fileAttachmentFileViewDetailTableCriteriaTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createFileViewDetailTableFilterForm(
        FileAttachmentFileViewDetailTableCriteriaTransfer $fileAttachmentFileViewDetailTableCriteriaTransfer
    ): FormInterface {
        $dataProvider = $this->createFileViewDetailTableFilterFormDataProvider();

        return $this->getFormFactory()->create(
            FileViewDetailTableFilterForm::class,
            $fileAttachmentFileViewDetailTableCriteriaTransfer,
            $dataProvider->getOptions(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Communication\Form\DataProvider\FileAttachFormDataProvider
     */
    public function createFileAttachFormDataProvider(): FileAttachFormDataProvider
    {
        return new FileAttachFormDataProvider(
            $this->getCompanyFacade(),
            $this->getCompanyUserFacade(),
            $this->getCompanyBusinessUnitFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @param array<mixed>|null $data
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAttachFileForm(?array $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()
            ->create(AttachFileForm::class, $data, $options);
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Communication\Mapper\FileAttachmentMapperInterface
     */
    public function createFileAttachmentMapper(): FileAttachmentMapperInterface
    {
        return new FileAttachmentMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Communication\Formatter\TimeZoneFormatterInterface
     */
    public function createTimeZoneFormatter(): TimeZoneFormatterInterface
    {
        return new TimeZoneFormatter($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface
     */
    public function getFileManagerFacade(): FileManagerFacadeInterface
    {
        return $this->getProvidedDependency(SspFileManagementDependencyProvider::FACADE_FILE_MANAGER);
    }

    /**
     * @return \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface
     */
    public function getSequenceNumberFacade(): SequenceNumberFacadeInterface
    {
        return $this->getProvidedDependency(SspFileManagementDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function getFilePropelQuery(): SpyFileQuery
    {
        return $this->getProvidedDependency(SspFileManagementDependencyProvider::PROPEL_QUERY_FILE);
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery
     */
    public function getFileInfoPropelQuery(): SpyFileInfoQuery
    {
        return $this->getProvidedDependency(SspFileManagementDependencyProvider::PROPEL_QUERY_FILE_INFO);
    }

    /**
     * @return \Spryker\Zed\Translator\Business\TranslatorFacadeInterface
     */
    public function getTranslatorFacade(): TranslatorFacadeInterface
    {
        return $this->getProvidedDependency(SspFileManagementDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\Company\Business\CompanyFacadeInterface
     */
    public function getCompanyFacade(): CompanyFacadeInterface
    {
        return $this->getProvidedDependency(SspFileManagementDependencyProvider::FACADE_COMPANY);
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): CompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(SspFileManagementDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade(): CompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(SspFileManagementDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    public function getDateTimeService(): UtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SspFileManagementDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }
}
