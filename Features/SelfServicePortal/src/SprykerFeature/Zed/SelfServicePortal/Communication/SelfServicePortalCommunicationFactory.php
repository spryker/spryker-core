<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication;

use Generated\Shared\Transfer\FileAttachmentTableCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentTransfer;
use Generated\Shared\Transfer\FileAttachmentViewDetailTableCriteriaTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\FileManager\Persistence\SpyFileInfoQuery;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnitQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySspAssetQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspModelQuery;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Zed\Company\Business\CompanyFacadeInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Merchant\Business\MerchantFacadeInterface;
use Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface;
use Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;
use Spryker\Zed\User\Business\UserFacadeInterface;
use SprykerFeature\Service\SelfServicePortal\SelfServicePortalServiceInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\DataProvider\SspAssetFilterFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\DataProvider\SspAssetFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\SspAssetFilterForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\SspAssetForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Mapper\SspAssetFormDataToTransferMapper;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Mapper\SspAssetFormDataToTransferMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Saver\SalesOrderItemSspAssetSaver;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Saver\SalesOrderItemSspAssetSaverInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Table\AssignedBusinessUnitTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Table\SspAssetTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Table\SspInquiryTable as AssetSspInquiryTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Table\SspServiceTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Tabs\SspAssetTabs;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\AttachFileForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\DataProvider\AssetAttachmentFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\DataProvider\FileAttachFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\DataProvider\FileTableFilterFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\DataProvider\ViewFileDetailTableFilterFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\DeleteFileForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\FileTableFilterForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\UnlinkFileForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\UploadFileForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\ViewFileDetailTableFilterForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter\FileSizeFormatter;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter\FileSizeFormatterInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter\TimeZoneFormatter;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter\TimeZoneFormatterInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\FormDataNormalizer;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\FormDataNormalizerInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer\AssetAssignmentImporter;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer\AssetAssignmentImporterInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer\BusinessUnitAssignmentImporter;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer\BusinessUnitAssignmentImporterInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer\CompanyAssignmentImporter;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer\CompanyAssignmentImporterInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer\CompanyUserAssignmentImporter;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Importer\CompanyUserAssignmentImporterInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper\FileAttachmentMapper;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper\FileAttachmentMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper\FileUploadMapper;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper\FileUploadMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser\AssetAssignmentFileParser;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser\AssetAssignmentFileParserInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser\BusinessUnitAssignmentFileParser;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser\BusinessUnitAssignmentFileParserInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser\CompanyAssignmentFileParser;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser\CompanyAssignmentFileParserInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser\CompanyUserAssignmentFileParser;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Parser\CompanyUserAssignmentFileParserInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\AttachmentProcessor;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\AttachmentProcessorInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\CsvImportProcessor;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\CsvImportProcessorInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\FormDataProcessor;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\FormDataProcessorInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\Strategy\AssetCsvImportStrategy;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\Strategy\BusinessUnitCsvImportStrategy;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\Strategy\CompanyCsvImportStrategy;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\Strategy\CompanyUserCsvImportStrategy;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Reader\FileReader;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Reader\FileReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\ReferenceGenerator\FileReferenceGenerator;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\ReferenceGenerator\FileReferenceGeneratorInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Saver\FileSaver;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Saver\FileSaverInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\AssignedBusinessUnitAttachmentTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\AssignedCompanyAttachmentTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\AssignedCompanyUserAttachmentTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\AssignedSspAssetAttachmentTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\AttachedSspAssetFileTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\AvailableBusinessUnitAttachmentTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\AvailableCompanyAttachmentTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\AvailableCompanyUserAttachmentTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\AvailableSspAssetAttachmentTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\FileTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\ViewFileDetailTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs\AssetAttachmentTabs;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs\AttachedAssetsTabs;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs\AttachedBusinessUnitsTabs;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs\AttachedCompaniesTabs;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs\AttachedCompanyUsersTabs;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs\AttachmentScopeTabs;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs\BusinessUnitAttachmentTabs;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs\CompanyAttachmentTabs;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs\CompanyUserAttachmentTabs;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs\FileAttachmentTabs;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Validator\FileSecurityValidator;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Validator\FileSecurityValidatorInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\DataProvider\SspInquiryFilterFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\DataProvider\SspInquiryFilterFormDataProviderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\DataProvider\TriggerEventFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\DataProvider\TriggerEventFormDataProviderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\SspInquiryFilterForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\TriggerEventForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Table\OrderSspInquiryTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Table\SspInquiryTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Expander\ProductOfferTableActionExpander;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Expander\ProductOfferTableActionExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\CreateOfferForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataProvider\CreateOfferFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataProvider\EditOfferFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataProvider\ItemSchedulerFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataProvider\ShipmentTypeProductConcreteFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\ServicePointServicesDataTransformer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\ShipmentTypesDataTransformer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\StoresDataTransformer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\ValidFromDataTransformer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\ValidToDataTransformer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\EditOfferForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\EventListener\MerchantCreateOfferFormEventSubscriber;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\EventListener\ServicePointEditOfferFormEventSubscriber;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\EventListener\StockCreateOfferFormEventSubscriber;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\EventListener\StockEditOfferFormEventSubscriber;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\Expander\ShipmentTypeProductConcreteFormExpander;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\Expander\ShipmentTypeProductConcreteFormExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ItemSchedulerForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ProductClassForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ShipmentTypeProductConcreteForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Mapper\ShipmentTypeProductFormMapper;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Mapper\ShipmentTypeProductFormMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader\ProductReader;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader\ProductReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader\SalesOrderItemReader;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader\SalesOrderItemReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader\ShipmentTypeReader;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader\ShipmentTypeReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Saver\SalesOrderItemProductClassesSaver;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Saver\SalesOrderItemProductClassesSaverInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Table\ProductConcreteTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Table\ServiceTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Form\DataProvider\SspModelFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Form\DeleteSspModelForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Form\SspModelForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Mapper\SspModelFormDataToTransferMapper;
use SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Mapper\SspModelFormDataToTransferMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Provider\ModelImageUrlProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Table\SspModelTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\Strategy\AssetAttachmentScopeStrategy;
use SprykerFeature\Zed\SelfServicePortal\Communication\Strategy\AttachmentScopeStrategyInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Strategy\AttachmentScopeStrategyResolver;
use SprykerFeature\Zed\SelfServicePortal\Communication\Strategy\AttachmentScopeStrategyResolverInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Strategy\BusinessUnitAttachmentScopeStrategy;
use SprykerFeature\Zed\SelfServicePortal\Communication\Strategy\CompanyAttachmentScopeStrategy;
use SprykerFeature\Zed\SelfServicePortal\Communication\Strategy\CompanyUserAttachmentScopeStrategy;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalDependencyProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface getEntityManager()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class SelfServicePortalCommunicationFactory extends AbstractCommunicationFactory
{
    public function createShipmentTypeProductConcreteFormDataProvider(): ShipmentTypeProductConcreteFormDataProvider
    {
        return new ShipmentTypeProductConcreteFormDataProvider($this->getShipmentTypeFacade());
    }

    public function createShipmentTypeProductConcreteForm(): FormTypeInterface
    {
        return new ShipmentTypeProductConcreteForm();
    }

    public function createCreateOfferForm(ProductConcreteTransfer $productConcreteTransfer): FormInterface
    {
        return $this->getFormFactory()->create(
            CreateOfferForm::class,
            $this->createCreateOfferFormDataProvider()->getData($productConcreteTransfer),
            $this->createCreateOfferFormDataProvider()->getOptions(),
        );
    }

    public function createCreateOfferFormDataProvider(): CreateOfferFormDataProvider
    {
        return new CreateOfferFormDataProvider(
            $this->getStoreFacade(),
            $this->getShipmentTypeFacade(),
            $this->getServicePointFacade(),
            $this->getCreateProductOfferFormModelTransformers(),
            $this->getCreateProductOfferFormEventSubscribers(),
        );
    }

    public function createProductReader(): ProductReaderInterface
    {
        return new ProductReader(
            $this->getProductFacade(),
            $this->getLocaleFacade(),
        );
    }

    public function createShipmentTypeProductFormMapper(): ShipmentTypeProductFormMapperInterface
    {
        return new ShipmentTypeProductFormMapper();
    }

    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader($this->getShipmentTypeFacade(), $this->getConfig());
    }

    public function createProductConcreteTable(): ProductConcreteTable
    {
        return new ProductConcreteTable(
            $this->getProductQuery(),
            $this->getProductImageQuery(),
            $this->getLocaleFacade(),
        );
    }

    public function getRepository(): SelfServicePortalRepositoryInterface
    {
        /**
         * @var \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $repository
         */
        $repository = parent::getRepository();

        return $repository;
    }

    /**
     * @return array<string, \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\DataTransformerInterface<mixed, mixed>>
     */
    public function getCreateProductOfferFormModelTransformers(): array
    {
        return [
            CreateOfferForm::FIELD_STORES => $this->createStoresDataTransformer(),
            CreateOfferForm::FIELD_SHIPMENT_TYPES => $this->createShipmentTypesDataTransformer(),
            CreateOfferForm::FIELD_SERVICE_POINT_SERVICES => $this->createServicePointServicesDataTransformer(),
        ];
    }

    public function createStoresDataTransformer(): StoresDataTransformer
    {
        return new StoresDataTransformer();
    }

    public function createShipmentTypesDataTransformer(): ShipmentTypesDataTransformer
    {
        return new ShipmentTypesDataTransformer();
    }

    public function createServicePointServicesDataTransformer(): ServicePointServicesDataTransformer
    {
        return new ServicePointServicesDataTransformer();
    }

    public function createStockCreateOfferFormEventSubscriber(): EventSubscriberInterface
    {
        return new StockCreateOfferFormEventSubscriber();
    }

    public function createMerchantCreateOfferFormEventSubscriber(): EventSubscriberInterface
    {
        return new MerchantCreateOfferFormEventSubscriber(
            $this->getMerchantFacade(),
            $this->getMerchantStockFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return list<\Symfony\Component\EventDispatcher\EventSubscriberInterface>
     */
    public function getCreateProductOfferFormEventSubscribers(): array
    {
        return [
            $this->createStockCreateOfferFormEventSubscriber(),
            $this->createMerchantCreateOfferFormEventSubscriber(),
        ];
    }

    public function createServiceTable(): ServiceTable
    {
        return new ServiceTable(
            $this->getSalesOrderItemPropelQuery(),
            $this->getUtilDateTimeService(),
            $this->getConfig(),
        );
    }

    public function createItemSchedulerForm(ItemTransfer $itemTransfer): FormInterface
    {
        $itemSchedulerFormDataProvider = $this->createItemSchedulerFormDataProvider();

        return $this->getFormFactory()->create(
            ItemSchedulerForm::class,
            $itemSchedulerFormDataProvider->getData($itemTransfer),
            $itemSchedulerFormDataProvider->getOptions(),
        );
    }

    public function createItemSchedulerFormDataProvider(): ItemSchedulerFormDataProvider
    {
        return new ItemSchedulerFormDataProvider();
    }

    public function createSalesOrderItemReader(): SalesOrderItemReaderInterface
    {
        return new SalesOrderItemReader($this->getSalesFacade());
    }

    public function createSalesOrderItemProductClassesSaver(): SalesOrderItemProductClassesSaverInterface
    {
        return new SalesOrderItemProductClassesSaver(
            $this->getEntityManager(),
        );
    }

    public function createShipmentTypeProductConcreteFormExpander(): ShipmentTypeProductConcreteFormExpanderInterface
    {
        return new ShipmentTypeProductConcreteFormExpander(
            $this->createShipmentTypeProductConcreteFormDataProvider(),
            $this->createShipmentTypeProductConcreteForm(),
        );
    }

    public function createFileAttachmentTabs(): FileAttachmentTabs
    {
        return new FileAttachmentTabs();
    }

    public function createAttachmentScopeTabs(): AttachmentScopeTabs
    {
        return new AttachmentScopeTabs();
    }

    public function createAssetAttachmentTabs(): AssetAttachmentTabs
    {
        return new AssetAttachmentTabs();
    }

    public function createAssetAttachmentFormDataProvider(): AssetAttachmentFormDataProvider
    {
        return new AssetAttachmentFormDataProvider($this->getFacade());
    }

    public function createAttachedSspAssetFileTable(SspAssetTransfer $sspAssetTransfer): AttachedSspAssetFileTable
    {
        return new AttachedSspAssetFileTable(
            $sspAssetTransfer,
            $this->getFilePropelQuery(),
            $this->getUtilDateTimeService(),
        );
    }

    public function createAttachedAssetsTabs(): AttachedAssetsTabs
    {
        return new AttachedAssetsTabs();
    }

    public function createBusinessUnitAttachmentTabs(): BusinessUnitAttachmentTabs
    {
        return new BusinessUnitAttachmentTabs();
    }

    public function createAttachedBusinessUnitsTabs(): AttachedBusinessUnitsTabs
    {
        return new AttachedBusinessUnitsTabs();
    }

    public function createCompanyUserAttachmentTabs(): CompanyUserAttachmentTabs
    {
        return new CompanyUserAttachmentTabs();
    }

    public function createAttachedCompanyUsersTabs(): AttachedCompanyUsersTabs
    {
        return new AttachedCompanyUsersTabs();
    }

    public function createAvailableCompanyUserAttachmentTable(int $idFile): AvailableCompanyUserAttachmentTable
    {
        return new AvailableCompanyUserAttachmentTable(
            $idFile,
            $this->getCompanyUserQuery(),
        );
    }

    public function createAssignedCompanyUserAttachmentTable(int $idFile): AssignedCompanyUserAttachmentTable
    {
        return new AssignedCompanyUserAttachmentTable(
            $idFile,
            $this->getCompanyUserQuery(),
        );
    }

    public function createAvailableSspAssetAttachmentTable(
        int $idFile
    ): AvailableSspAssetAttachmentTable {
        return new AvailableSspAssetAttachmentTable(
            $this->getSspAssetQuery(),
            $idFile,
        );
    }

    public function createAssignedSspAssetAttachmentTable(
        int $idFile
    ): AssignedSspAssetAttachmentTable {
        return new AssignedSspAssetAttachmentTable(
            $this->getSspAssetQuery(),
            $idFile,
        );
    }

    public function createFileTable(
        FileAttachmentTableCriteriaTransfer $fileAttachmentTableCriteriaTransfer
    ): FileTable {
        return new FileTable(
            $this->getFilePropelQuery(),
            $this->createFileSizeFormatter(),
            $this->getUtilDateTimeService(),
            $this->createTimeZoneFormatter(),
            $fileAttachmentTableCriteriaTransfer,
        );
    }

    public function createViewFileDetailTable(
        int $idFile,
        FileAttachmentViewDetailTableCriteriaTransfer $fileAttachmentViewDetailTableCriteriaTransfer
    ): ViewFileDetailTable {
        return new ViewFileDetailTable(
            $this->getFilePropelQuery(),
            $idFile,
            $this->getUtilDateTimeService(),
            $this->createTimeZoneFormatter(),
            $fileAttachmentViewDetailTableCriteriaTransfer,
        );
    }

    public function createFileSizeFormatter(): FileSizeFormatterInterface
    {
        return new FileSizeFormatter();
    }

    public function createFileUploadMapper(): FileUploadMapperInterface
    {
        return new FileUploadMapper($this->getConfig());
    }

    public function createFileSaver(): FileSaverInterface
    {
        return new FileSaver(
            $this->createFileUploadMapper(),
            $this->getFileManagerFacade(),
            $this->createFileReferenceGenerator(),
        );
    }

    public function createFileReferenceGenerator(): FileReferenceGeneratorInterface
    {
        return new FileReferenceGenerator(
            $this->getSequenceNumberFacade(),
            $this->getConfig(),
        );
    }

    public function createFileReader(): FileReaderInterface
    {
        return new FileReader($this->getFileManagerFacade());
    }

    public function createUploadFileForm(): FormInterface
    {
        return $this->getFormFactory()->create(UploadFileForm::class);
    }

    public function createUnlinkFileForm(): FormInterface
    {
        return $this->getFormFactory()->create(UnlinkFileForm::class);
    }

    public function createDeleteFileForm(): FormInterface
    {
        return $this->getFormFactory()->create(DeleteFileForm::class);
    }

    public function createFileTableFilterForm(
        FileAttachmentTableCriteriaTransfer $fileAttachmentTableCriteriaTransfer
    ): FormInterface {
        $dataProvider = $this->createFileTableFilterFormDataProvider();

        return $this->getFormFactory()->create(
            FileTableFilterForm::class,
            $fileAttachmentTableCriteriaTransfer,
            $dataProvider->getOptions(),
        );
    }

    public function createFileTableFilterFormDataProvider(): FileTableFilterFormDataProvider
    {
        return new FileTableFilterFormDataProvider(
            $this->getConfig(),
        );
    }

    public function createViewFileDetailTableFilterFormDataProvider(): ViewFileDetailTableFilterFormDataProvider
    {
        return new ViewFileDetailTableFilterFormDataProvider(
            $this->getConfig(),
            $this->getTranslatorFacade(),
        );
    }

    public function createViewFileDetailTableFilterForm(
        FileAttachmentViewDetailTableCriteriaTransfer $fileAttachmentViewDetailTableCriteriaTransfer
    ): FormInterface {
        $dataProvider = $this->createViewFileDetailTableFilterFormDataProvider();

        return $this->getFormFactory()->create(
            ViewFileDetailTableFilterForm::class,
            $fileAttachmentViewDetailTableCriteriaTransfer,
            $dataProvider->getOptions(),
        );
    }

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
     * @param array<string, mixed> $formData
     * @param \Generated\Shared\Transfer\FileAttachmentTransfer $fileAttachmentTransfer
     * @param int $idFile
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createAttachFileForm(array $formData, FileAttachmentTransfer $fileAttachmentTransfer, int $idFile): FormInterface
    {
        $formDataProvider = $this->createFileAttachFormDataProvider();

        return $this->getFormFactory()->create(
            AttachFileForm::class,
            $formDataProvider->getData($formData),
            $formDataProvider->getOptions($fileAttachmentTransfer, $idFile),
        );
    }

    public function createFileAttachmentMapper(): FileAttachmentMapperInterface
    {
        return new FileAttachmentMapper();
    }

    public function createAssetAssignmentFileParser(): AssetAssignmentFileParserInterface
    {
        return new AssetAssignmentFileParser();
    }

    public function createAssetAssignmentImporter(): AssetAssignmentImporterInterface
    {
        return new AssetAssignmentImporter(
            $this->getRepository(),
        );
    }

    public function createBusinessUnitAssignmentFileParser(): BusinessUnitAssignmentFileParserInterface
    {
        return new BusinessUnitAssignmentFileParser();
    }

    public function createBusinessUnitAssignmentImporter(): BusinessUnitAssignmentImporterInterface
    {
        return new BusinessUnitAssignmentImporter(
            $this->getRepository(),
        );
    }

    public function createCompanyUserAssignmentFileParser(): CompanyUserAssignmentFileParserInterface
    {
        return new CompanyUserAssignmentFileParser();
    }

    public function createCompanyUserAssignmentImporter(): CompanyUserAssignmentImporterInterface
    {
        return new CompanyUserAssignmentImporter(
            $this->getRepository(),
        );
    }

    public function createAvailableBusinessUnitAttachmentTable(int $idFile): AvailableBusinessUnitAttachmentTable
    {
        return new AvailableBusinessUnitAttachmentTable($this->getCompanyBusinessUnitQuery(), $idFile);
    }

    public function createAssignedBusinessUnitAttachmentTable(int $idFile): AssignedBusinessUnitAttachmentTable
    {
        return new AssignedBusinessUnitAttachmentTable($this->getCompanyBusinessUnitQuery(), $idFile);
    }

    public function createTriggerEventFormDataProvider(): TriggerEventFormDataProviderInterface
    {
        return new TriggerEventFormDataProvider(
            $this->getFacade(),
            $this->getStateMachineFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @param array<mixed> $data
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getTriggerEventForm(array $data, array $options): FormInterface
    {
        return $this->getFormFactory()->create(TriggerEventForm::class, $data, $options);
    }

    public function createSspInquiryTable(SspInquiryConditionsTransfer $sspInquiryConditionsTransfer): SspInquiryTable
    {
        return new SspInquiryTable(
            $this->getSspInquiryQuery(),
            $this->getConfig(),
            $this->getUtilDateTimeService(),
            $sspInquiryConditionsTransfer,
        );
    }

    public function getSspInquiryFilterForm(SspInquiryConditionsTransfer $sspInquiryConditionsTransfer): FormInterface
    {
        return $this->getFormFactory()->create(SspInquiryFilterForm::class, $sspInquiryConditionsTransfer, $this->createSspInquiryFilterFormDataProvider()->getOptions());
    }

    public function createSspInquiryFilterFormDataProvider(): SspInquiryFilterFormDataProviderInterface
    {
        return new SspInquiryFilterFormDataProvider(
            $this->getConfig(),
        );
    }

    public function createOrderSspInquiryTable(OrderTransfer $orderTransfer): OrderSspInquiryTable
    {
        return new OrderSspInquiryTable(
            $this->getSspInquiryQuery(),
            $this->getConfig(),
            $orderTransfer,
        );
    }

    public function createTimeZoneFormatter(): TimeZoneFormatterInterface
    {
        return new TimeZoneFormatter($this->getConfig());
    }

    public function createEditOfferForm(ProductOfferTransfer $productOfferTransfer): FormInterface
    {
        return $this->getFormFactory()->create(
            EditOfferForm::class,
            $this->createEditOfferFormDataProvider()->getData($productOfferTransfer),
            $this->createEditOfferFormDataProvider()->getOptions($productOfferTransfer),
        );
    }

    public function createEditOfferFormDataProvider(): EditOfferFormDataProvider
    {
        return new EditOfferFormDataProvider(
            $this->getStoreFacade(),
            $this->getShipmentTypeFacade(),
            $this->getServicePointFacade(),
            $this->getEditProductOfferFormModelTransformers(),
            $this->getEditProductOfferFormEventSubscribers(),
        );
    }

    /**
     * @return array<string, \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\DataTransformerInterface<mixed, mixed>>
     */
    public function getEditProductOfferFormModelTransformers(): array
    {
        return [
            CreateOfferForm::FIELD_STORES => $this->createStoresDataTransformer(),
            CreateOfferForm::FIELD_SHIPMENT_TYPES => $this->createShipmentTypesDataTransformer(),
            CreateOfferForm::FIELD_VALID_FROM => $this->createValidFromDataTransformer(),
            CreateOfferForm::FIELD_VALID_TO => $this->createValidToDataTransformer(),
        ];
    }

    public function createValidFromDataTransformer(): ValidFromDataTransformer
    {
        return new ValidFromDataTransformer();
    }

    public function createValidToDataTransformer(): ValidToDataTransformer
    {
        return new ValidToDataTransformer();
    }

    public function createStockEditOfferFormEventSubscriber(): EventSubscriberInterface
    {
        return new StockEditOfferFormEventSubscriber();
    }

    public function createServicePointEditOfferFormEventSubscriber(): EventSubscriberInterface
    {
        return new ServicePointEditOfferFormEventSubscriber();
    }

    /**
     * @return list<\Symfony\Component\EventDispatcher\EventSubscriberInterface>
     */
    public function getEditProductOfferFormEventSubscribers(): array
    {
        return [
            $this->createStockEditOfferFormEventSubscriber(),
            $this->createServicePointEditOfferFormEventSubscriber(),
        ];
    }

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

    public function createSspAssetFormDataProvider(): SspAssetFormDataProvider
    {
        return new SspAssetFormDataProvider(
            $this->getFacade(),
            $this->getConfig(),
            $this->getCompanyBusinessUnitFacade(),
            $this->getCompanyFacade(),
        );
    }

    public function createSspAssetFormDataToTransferMapper(): SspAssetFormDataToTransferMapperInterface
    {
        return new SspAssetFormDataToTransferMapper();
    }

    /**
     * @param \Generated\Shared\Transfer\SspModelTransfer|null $sspModelTransfer
     * @param array<string, mixed> $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createSspModelForm(?SspModelTransfer $sspModelTransfer = null, array $formOptions = []): FormInterface
    {
        return $this->getFormFactory()->create(SspModelForm::class, $sspModelTransfer, $formOptions);
    }

    public function createSspModelFormDataToTransferMapper(): SspModelFormDataToTransferMapperInterface
    {
        return new SspModelFormDataToTransferMapper();
    }

    public function createSspAssetTabs(): SspAssetTabs
    {
        return new SspAssetTabs();
    }

    public function createAssignedBusinessUnitTable(SspAssetTransfer $sspAssetTransfer): AssignedBusinessUnitTable
    {
        return new AssignedBusinessUnitTable(
            $sspAssetTransfer,
            $this->getSspAssetToCompanyBusinessUnitQuery(),
            $this->getUtilDateTimeService(),
        );
    }

    public function createAssetSspInquiryTable(SspAssetTransfer $sspAssetTransfer): AssetSspInquiryTable
    {
        return new AssetSspInquiryTable(
            $sspAssetTransfer,
            $this->getSspInquirySspAssetQuery(),
            $this->getUtilDateTimeService(),
        );
    }

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

    public function getSspAssetFilterForm(SspAssetConditionsTransfer $sspAssetConditionsTransfer): FormInterface
    {
        return $this->getFormFactory()->create(SspAssetFilterForm::class, $sspAssetConditionsTransfer, $this->createSspAssetFilterFormDataProvider()->getOptions());
    }

    public function createSspAssetFilterFormDataProvider(): SspAssetFilterFormDataProvider
    {
        return new SspAssetFilterFormDataProvider(
            $this->getConfig(),
        );
    }

    public function createAssetSspServiceTable(string $assetReference): SspServiceTable
    {
        return new SspServiceTable(
            $assetReference,
            $this->getSalesOrderItemQuery(),
            $this->getUtilDateTimeService(),
        );
    }

    public function getSalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_SALES_ORDER_ITEM);
    }

    public function createSalesOrderItemSspAssetSaver(): SalesOrderItemSspAssetSaverInterface
    {
        return new SalesOrderItemSspAssetSaver(
            $this->getEntityManager(),
            $this->getFacade(),
        );
    }

    public function createProductClassForm(): ProductClassForm
    {
        return new ProductClassForm();
    }

    public function createProductOfferTableActionExpander(): ProductOfferTableActionExpanderInterface
    {
        return new ProductOfferTableActionExpander();
    }

    public function getSelfServicePortalService(): SelfServicePortalServiceInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_SELF_SERVICE_PORTAL);
    }

    public function createSspModelTable(): SspModelTable
    {
        return new SspModelTable(
            $this->getSspModelQuery(),
            $this->getUtilDateTimeService(),
            $this->createModelImageUrlProvider(),
        );
    }

    public function getSspModelQuery(): SpySspModelQuery
    {
        return SpySspModelQuery::create();
    }

    public function createDeleteSspModelForm(): FormInterface
    {
        return $this->getFormFactory()->create(DeleteSspModelForm::class);
    }

    public function createModelImageUrlProvider(): ModelImageUrlProvider
    {
        return new ModelImageUrlProvider();
    }

    public function createSspModelFormDataProvider(): SspModelFormDataProvider
    {
        return new SspModelFormDataProvider(
            $this->createModelImageUrlProvider(),
        );
    }

    public function createAttachmentScopeStrategyResolver(): AttachmentScopeStrategyResolverInterface
    {
        return new AttachmentScopeStrategyResolver($this->getAttachmentScopeStrategies());
    }

    /**
     * @return array<\SprykerFeature\Zed\SelfServicePortal\Communication\Strategy\AttachmentScopeStrategyInterface>
     */
    protected function getAttachmentScopeStrategies(): array
    {
        return [
            $this->createAssetAttachmentScopeStrategy(),
            $this->createBusinessUnitAttachmentScopeStrategy(),
            $this->createCompanyUserAttachmentScopeStrategy(),
            $this->createCompanyAttachmentScopeStrategy(),
        ];
    }

    protected function createAssetAttachmentScopeStrategy(): AttachmentScopeStrategyInterface
    {
        return new AssetAttachmentScopeStrategy($this->createFormDataNormalizer());
    }

    protected function createBusinessUnitAttachmentScopeStrategy(): AttachmentScopeStrategyInterface
    {
        return new BusinessUnitAttachmentScopeStrategy($this->createFormDataNormalizer());
    }

    protected function createCompanyUserAttachmentScopeStrategy(): AttachmentScopeStrategyInterface
    {
        return new CompanyUserAttachmentScopeStrategy($this->createFormDataNormalizer());
    }

    protected function createCompanyAttachmentScopeStrategy(): AttachmentScopeStrategyInterface
    {
        return new CompanyAttachmentScopeStrategy($this->createFormDataNormalizer());
    }

    public function createCompanyAssignmentFileParser(): CompanyAssignmentFileParserInterface
    {
        return new CompanyAssignmentFileParser();
    }

    public function createCompanyAssignmentImporter(): CompanyAssignmentImporterInterface
    {
        return new CompanyAssignmentImporter(
            $this->getRepository(),
        );
    }

    public function createAvailableCompanyAttachmentTable(int $idFile): AvailableCompanyAttachmentTable
    {
        return new AvailableCompanyAttachmentTable(
            $idFile,
            $this->getCompanyQuery(),
        );
    }

    public function createAssignedCompanyAttachmentTable(int $idFile): AssignedCompanyAttachmentTable
    {
        return new AssignedCompanyAttachmentTable(
            $idFile,
            $this->getCompanyQuery(),
        );
    }

    public function createCompanyAttachmentTabs(): CompanyAttachmentTabs
    {
        return new CompanyAttachmentTabs();
    }

    public function createAttachedCompaniesTabs(): AttachedCompaniesTabs
    {
        return new AttachedCompaniesTabs();
    }

    public function createCsvImportProcessor(): CsvImportProcessorInterface
    {
        return new CsvImportProcessor($this->getCsvImportStrategies());
    }

    /**
     * @return array<\SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Processor\Strategy\CsvImportStrategyInterface>
     */
    protected function getCsvImportStrategies(): array
    {
        return [
            $this->createAssetCsvImportStrategy(),
            $this->createBusinessUnitCsvImportStrategy(),
            $this->createCompanyUserCsvImportStrategy(),
            $this->createCompanyCsvImportStrategy(),
        ];
    }

    public function createAssetCsvImportStrategy(): AssetCsvImportStrategy
    {
        return new AssetCsvImportStrategy(
            $this->createAssetAssignmentFileParser(),
            $this->createAssetAssignmentImporter(),
        );
    }

    public function createBusinessUnitCsvImportStrategy(): BusinessUnitCsvImportStrategy
    {
        return new BusinessUnitCsvImportStrategy(
            $this->createBusinessUnitAssignmentFileParser(),
            $this->createBusinessUnitAssignmentImporter(),
        );
    }

    public function createCompanyUserCsvImportStrategy(): CompanyUserCsvImportStrategy
    {
        return new CompanyUserCsvImportStrategy(
            $this->createCompanyUserAssignmentFileParser(),
            $this->createCompanyUserAssignmentImporter(),
        );
    }

    public function createCompanyCsvImportStrategy(): CompanyCsvImportStrategy
    {
        return new CompanyCsvImportStrategy(
            $this->createCompanyAssignmentFileParser(),
            $this->createCompanyAssignmentImporter(),
        );
    }

    public function createFormDataProcessor(): FormDataProcessorInterface
    {
        return new FormDataProcessor(
            $this->createFormDataNormalizer(),
        );
    }

    public function createFormDataNormalizer(): FormDataNormalizerInterface
    {
        return new FormDataNormalizer();
    }

    public function createAttachmentProcessor(): AttachmentProcessorInterface
    {
        return new AttachmentProcessor(
            $this->getFacade(),
            $this->getRepository(),
            $this->createFormDataNormalizer(),
            $this->createFileAttachmentMapper(),
        );
    }

    public function createFileSecurityValidator(): FileSecurityValidatorInterface
    {
        return new FileSecurityValidator(
            $this->getConfig(),
        );
    }

    public function getSspAssetToCompanyBusinessUnitQuery(): SpySspAssetToCompanyBusinessUnitQuery
    {
        return SpySspAssetToCompanyBusinessUnitQuery::create();
    }

    public function getSspInquirySspAssetQuery(): SpySspInquirySspAssetQuery
    {
        return SpySspInquirySspAssetQuery::create();
    }

    public function getSspAssetQuery(): SpySspAssetQuery
    {
        return SpySspAssetQuery::create();
    }

    public function getSspInquiryQuery(): SpySspInquiryQuery
    {
        return SpySspInquiryQuery::create();
    }

    /**
     * @return array<\Spryker\Zed\StateMachine\Dependency\Plugin\CommandPluginInterface>
     */
    public function getStateMachineCommandPlugins(): array
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PLUGINS_STATE_MACHINE_COMMAND);
    }

    /**
     * @return array<\Spryker\Zed\StateMachine\Dependency\Plugin\ConditionPluginInterface>
     */
    public function getStateMachineConditionPlugins(): array
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PLUGINS_STATE_MACHINE_CONDITION);
    }

    public function getStateMachineFacade(): StateMachineFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_STATE_MACHINE);
    }

    public function getProductQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_PRODUCT);
    }

    public function getProductImageQuery(): SpyProductImageQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_PRODUCT_IMAGE);
    }

    public function getLocaleFacade(): LocaleFacade
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_LOCALE);
    }

    public function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_STORE);
    }

    public function getServicePointFacade(): ServicePointFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SERVICE_POINT);
    }

    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_SALES_ORDER_ITEM);
    }

    public function getShipmentTypeFacade(): ShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SHIPMENT_TYPE);
    }

    public function getProductOfferFacade(): ProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_PRODUCT_OFFER);
    }

    public function getProductFacade(): ProductFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_PRODUCT);
    }

    public function getMerchantFacade(): MerchantFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_MERCHANT);
    }

    public function getMerchantStockFacade(): MerchantStockFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_MERCHANT_STOCK);
    }

    public function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SALES);
    }

    public function getUtilDateTimeService(): UtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    public function getFileManagerFacade(): FileManagerFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_FILE_MANAGER);
    }

    public function getSequenceNumberFacade(): SequenceNumberFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

    public function getFileInfoPropelQuery(): SpyFileInfoQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_FILE_INFO);
    }

    public function getFilePropelQuery(): SpyFileQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_FILE);
    }

    public function getTranslatorFacade(): TranslatorFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_TRANSLATOR);
    }

    public function getCompanyFacade(): CompanyFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_COMPANY);
    }

    public function getCompanyUserFacade(): CompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_COMPANY_USER);
    }

    public function getCompanyBusinessUnitFacade(): CompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    public function getUserFacade(): UserFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_USER);
    }

    public function getCompanyUserQuery(): SpyCompanyUserQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_COMPANY_USER);
    }

    public function getCompanyBusinessUnitQuery(): SpyCompanyBusinessUnitQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_COMPANY_BUSINESS_UNIT);
    }

    public function getCompanyQuery(): SpyCompanyQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_COMPANY);
    }
}
