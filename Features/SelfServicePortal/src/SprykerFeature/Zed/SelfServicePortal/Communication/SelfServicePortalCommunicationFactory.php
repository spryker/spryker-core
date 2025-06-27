<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication;

use Generated\Shared\Transfer\FileAttachmentFileTableCriteriaTransfer;
use Generated\Shared\Transfer\FileAttachmentFileViewDetailTableCriteriaTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Orm\Zed\FileManager\Persistence\SpyFileInfoQuery;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnitQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySspAssetQuery;
use Spryker\Service\FileManager\FileManagerServiceInterface;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Zed\Company\Business\CompanyFacadeInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Mail\Business\MailFacadeInterface;
use Spryker\Zed\Merchant\Business\MerchantFacadeInterface;
use Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface;
use Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\SspAssetItemExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\SspAssetItemExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\OrderItemProductTypeExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\OrderItemProductTypeExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Saver\ServiceDateTimeEnabledSaver;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Saver\ServiceDateTimeEnabledSaverInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Expander\OrderItemSspAssetExpander;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Expander\OrderItemSspAssetExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Extractor\SalesOrderItemIdExtractor;
use SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Extractor\SalesOrderItemIdExtractorInterface;
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
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\SspAssetAttachmentForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\UnlinkFileForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\UploadFileForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\ViewFileDetailTableFilterForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter\FileSizeFormatter;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter\FileSizeFormatterInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter\TimeZoneFormatter;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter\TimeZoneFormatterInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper\FileAttachmentMapper;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper\FileAttachmentMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper\FileUploadMapper;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper\FileUploadMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Reader\FileReader;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Reader\FileReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\ReferenceGenerator\FileReferenceGenerator;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\ReferenceGenerator\FileReferenceGeneratorInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Saver\FileSaver;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Saver\FileSaverInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\AttachedSspAssetFileTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\FileTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\ViewFileDetailTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs\FileAttachmentTabs;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\DataProvider\SspInquiryFilterFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\DataProvider\SspInquiryFilterFormDataProviderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\DataProvider\TriggerEventFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\DataProvider\TriggerEventFormDataProviderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\SspInquiryFilterForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\TriggerEventForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Table\OrderSspInquiryTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Table\SspInquiryTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Expander\ProductAbstractTypeExpander;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Expander\ProductAbstractTypeExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Expander\ProductOfferTableActionExpander;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Expander\ProductOfferTableActionExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\CreateOfferForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataProvider\CreateOfferFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataProvider\EditOfferFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataProvider\ItemSchedulerFormDataProvider;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataProvider\ServiceDateTimeEnabledProductConcreteFormDataProvider;
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
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\Expander\ServiceDateTimeEnabledProductConcreteFormExpander;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\Expander\ServiceDateTimeEnabledProductConcreteFormExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\Expander\ShipmentTypeProductConcreteFormExpander;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\Expander\ShipmentTypeProductConcreteFormExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ItemSchedulerForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ProductAbstractTypeForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ServiceDateTimeEnabledProductConcreteForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\ShipmentTypeProductConcreteForm;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Mapper\ProductAbstractTypeProductFormMapper;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Mapper\ProductAbstractTypeProductFormMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Mapper\ServiceDateTimeProductFormMapper;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Mapper\ServiceDateTimeProductFormMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Mapper\ShipmentTypeProductFormMapper;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Mapper\ShipmentTypeProductFormMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader\ProductReader;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader\ProductReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader\SalesOrderItemReader;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader\SalesOrderItemReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader\ShipmentTypeReader;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader\ShipmentTypeReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Saver\SalesOrderItemProductTypesSaver;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Saver\SalesOrderItemProductTypesSaverInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Table\ProductConcreteTable;
use SprykerFeature\Zed\SelfServicePortal\Communication\Service\Table\ServiceTable;
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
    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataProvider\ShipmentTypeProductConcreteFormDataProvider
     */
    public function createShipmentTypeProductConcreteFormDataProvider(): ShipmentTypeProductConcreteFormDataProvider
    {
        return new ShipmentTypeProductConcreteFormDataProvider($this->getShipmentTypeFacade());
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createShipmentTypeProductConcreteForm(): FormTypeInterface
    {
        return new ShipmentTypeProductConcreteForm();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataProvider\ServiceDateTimeEnabledProductConcreteFormDataProvider
     */
    public function createServiceDateTimeEnabledProductConcreteFormDataProvider(): ServiceDateTimeEnabledProductConcreteFormDataProvider
    {
        return new ServiceDateTimeEnabledProductConcreteFormDataProvider();
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createServiceDateTimeEnabledProductConcreteForm(): FormTypeInterface
    {
        return new ServiceDateTimeEnabledProductConcreteForm();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createCreateOfferForm(ProductConcreteTransfer $productConcreteTransfer): FormInterface
    {
        return $this->getFormFactory()->create(
            CreateOfferForm::class,
            $this->createCreateOfferFormDataProvider()->getData($productConcreteTransfer),
            $this->createCreateOfferFormDataProvider()->getOptions(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataProvider\CreateOfferFormDataProvider
     */
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

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader\ProductReaderInterface
     */
    public function createProductReader(): ProductReaderInterface
    {
        return new ProductReader(
            $this->getProductFacade(),
            $this->getLocaleFacade(),
        );
    }

    /**
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    public function createProductAbstractTypeForm(): FormTypeInterface
    {
        return new ProductAbstractTypeForm();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Mapper\ShipmentTypeProductFormMapperInterface
     */
    public function createShipmentTypeProductFormMapper(): ShipmentTypeProductFormMapperInterface
    {
        return new ShipmentTypeProductFormMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Mapper\ServiceDateTimeProductFormMapperInterface
     */
    public function createServiceDateTimeProductFormMapper(): ServiceDateTimeProductFormMapperInterface
    {
        return new ServiceDateTimeProductFormMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader\ShipmentTypeReaderInterface
     */
    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader($this->getShipmentTypeFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Table\ProductConcreteTable
     */
    public function createProductConcreteTable(): ProductConcreteTable
    {
        return new ProductConcreteTable(
            $this->getProductQuery(),
            $this->getProductImageQuery(),
            $this->getLocaleFacade(),
        );
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

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\StoresDataTransformer
     */
    public function createStoresDataTransformer(): StoresDataTransformer
    {
        return new StoresDataTransformer();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\ShipmentTypesDataTransformer
     */
    public function createShipmentTypesDataTransformer(): ShipmentTypesDataTransformer
    {
        return new ShipmentTypesDataTransformer();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\ServicePointServicesDataTransformer
     */
    public function createServicePointServicesDataTransformer(): ServicePointServicesDataTransformer
    {
        return new ServicePointServicesDataTransformer();
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createStockCreateOfferFormEventSubscriber(): EventSubscriberInterface
    {
        return new StockCreateOfferFormEventSubscriber();
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
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

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Table\ServiceTable
     */
    public function createServiceTable(): ServiceTable
    {
        return new ServiceTable(
            $this->getSalesOrderItemPropelQuery(),
            $this->getDateTimeService(),
            $this->getConfig(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createItemSchedulerForm(ItemTransfer $itemTransfer): FormInterface
    {
        $itemSchedulerFormDataProvider = $this->createItemSchedulerFormDataProvider();

        return $this->getFormFactory()->create(
            ItemSchedulerForm::class,
            $itemSchedulerFormDataProvider->getData($itemTransfer),
            $itemSchedulerFormDataProvider->getOptions(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataProvider\ItemSchedulerFormDataProvider
     */
    public function createItemSchedulerFormDataProvider(): ItemSchedulerFormDataProvider
    {
        return new ItemSchedulerFormDataProvider();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Reader\SalesOrderItemReaderInterface
     */
    public function createSalesOrderItemReader(): SalesOrderItemReaderInterface
    {
        return new SalesOrderItemReader($this->getSalesFacade());
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Expander\ProductAbstractTypeExpanderInterface
     */
    public function createProductAbstractTypeExpander(): ProductAbstractTypeExpanderInterface
    {
        return new ProductAbstractTypeExpander(
            $this->getRepository(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Saver\SalesOrderItemProductTypesSaverInterface
     */
    public function createSalesOrderItemProductTypesSaver(): SalesOrderItemProductTypesSaverInterface
    {
        return new SalesOrderItemProductTypesSaver(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Saver\ServiceDateTimeEnabledSaverInterface
     */
    public function createServiceDateTimeEnabledSaver(): ServiceDateTimeEnabledSaverInterface
    {
        return new ServiceDateTimeEnabledSaver(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\OrderItemProductTypeExpanderInterface
     */
    public function createOrderItemProductTypeExpander(): OrderItemProductTypeExpanderInterface
    {
        return new OrderItemProductTypeExpander(
            $this->getRepository(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs\FileAttachmentTabs
     */
    public function createFileAttachmentTabs(): FileAttachmentTabs
    {
        return new FileAttachmentTabs();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\DataProvider\AssetAttachmentFormDataProvider
     */
    public function createAssetAttachmentFormDataProvider(): AssetAttachmentFormDataProvider
    {
        return new AssetAttachmentFormDataProvider($this->getFacade());
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\AttachedSspAssetFileTable
     */
    public function createAttachedSspAssetFileTable(SspAssetTransfer $sspAssetTransfer): AttachedSspAssetFileTable
    {
        return new AttachedSspAssetFileTable(
            $sspAssetTransfer,
            $this->getFilePropelQuery(),
            $this->getUtilDateTimeService(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileTableCriteriaTransfer $fileAttachmentFileTableCriteriaTransfer
     *
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\FileTable
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
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Table\ViewFileDetailTable
     */
    public function createViewFileDetailTable(
        int $idFile,
        FileAttachmentFileViewDetailTableCriteriaTransfer $fileAttachmentFileViewDetailTableCriteriaTransfer
    ): ViewFileDetailTable {
        return new ViewFileDetailTable(
            $this->getFilePropelQuery(),
            $idFile,
            $this->getDateTimeService(),
            $this->createTimeZoneFormatter(),
            $fileAttachmentFileViewDetailTableCriteriaTransfer,
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter\FileSizeFormatterInterface
     */
    public function createFileSizeFormatter(): FileSizeFormatterInterface
    {
        return new FileSizeFormatter();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper\FileUploadMapperInterface
     */
    public function createFileUploadMapper(): FileUploadMapperInterface
    {
        return new FileUploadMapper($this->getConfig());
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Saver\FileSaverInterface
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
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\ReferenceGenerator\FileReferenceGeneratorInterface
     */
    public function createFileReferenceGenerator(): FileReferenceGeneratorInterface
    {
        return new FileReferenceGenerator(
            $this->getSequenceNumberFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Reader\FileReaderInterface
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
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\DataProvider\FileTableFilterFormDataProvider
     */
    public function createFileTableFilterFormDataProvider(): FileTableFilterFormDataProvider
    {
        return new FileTableFilterFormDataProvider(
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\DataProvider\ViewFileDetailTableFilterFormDataProvider
     */
    public function createViewFileDetailTableFilterFormDataProvider(): ViewFileDetailTableFilterFormDataProvider
    {
        return new ViewFileDetailTableFilterFormDataProvider(
            $this->getConfig(),
            $this->getTranslatorFacade(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\FileAttachmentFileViewDetailTableCriteriaTransfer $fileAttachmentFileViewDetailTableCriteriaTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createViewFileDetailTableFilterForm(
        FileAttachmentFileViewDetailTableCriteriaTransfer $fileAttachmentFileViewDetailTableCriteriaTransfer
    ): FormInterface {
        $dataProvider = $this->createViewFileDetailTableFilterFormDataProvider();

        return $this->getFormFactory()->create(
            ViewFileDetailTableFilterForm::class,
            $fileAttachmentFileViewDetailTableCriteriaTransfer,
            $dataProvider->getOptions(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Form\DataProvider\FileAttachFormDataProvider
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
     * @param array<mixed>|null $data
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createSspAssetAttachmentForm(?array $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()
            ->create(SspAssetAttachmentForm::class, $data, $options);
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Mapper\FileAttachmentMapperInterface
     */
    public function createFileAttachmentMapper(): FileAttachmentMapperInterface
    {
        return new FileAttachmentMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\DataProvider\TriggerEventFormDataProviderInterface
     */
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

    /**
     * @param \Generated\Shared\Transfer\SspInquiryConditionsTransfer $sspInquiryConditionsTransfer
     *
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Table\SspInquiryTable
     */
    public function createSspInquiryTable(SspInquiryConditionsTransfer $sspInquiryConditionsTransfer): SspInquiryTable
    {
        return new SspInquiryTable(
            $this->getSspInquiryQuery(),
            $this->getConfig(),
            $this->getUtilDateTimeService(),
            $sspInquiryConditionsTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SspInquiryConditionsTransfer $sspInquiryConditionsTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getSspInquiryFilterForm(SspInquiryConditionsTransfer $sspInquiryConditionsTransfer): FormInterface
    {
        return $this->getFormFactory()->create(SspInquiryFilterForm::class, $sspInquiryConditionsTransfer, $this->createSspInquiryFilterFormDataProvider()->getOptions());
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Form\DataProvider\SspInquiryFilterFormDataProviderInterface
     */
    public function createSspInquiryFilterFormDataProvider(): SspInquiryFilterFormDataProviderInterface
    {
        return new SspInquiryFilterFormDataProvider(
            $this->getConfig(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Inquiry\Table\OrderSspInquiryTable
     */
    public function createOrderSspInquiryTable(OrderTransfer $orderTransfer): OrderSspInquiryTable
    {
        return new OrderSspInquiryTable(
            $this->getSspInquiryQuery(),
            $this->getConfig(),
            $orderTransfer,
        );
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspInquiryQuery
     */
    public function getSspInquiryQuery(): SpySspInquiryQuery
    {
        return SpySspInquiryQuery::create();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Formatter\TimeZoneFormatterInterface
     */
    public function createTimeZoneFormatter(): TimeZoneFormatterInterface
    {
        return new TimeZoneFormatter($this->getConfig());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createEditOfferForm(ProductOfferTransfer $productOfferTransfer): FormInterface
    {
        return $this->getFormFactory()->create(
            EditOfferForm::class,
            $this->createEditOfferFormDataProvider()->getData($productOfferTransfer),
            $this->createEditOfferFormDataProvider()->getOptions($productOfferTransfer),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataProvider\EditOfferFormDataProvider
     */
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

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\ValidFromDataTransformer
     */
    public function createValidFromDataTransformer(): ValidFromDataTransformer
    {
        return new ValidFromDataTransformer();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\DataTransformer\ValidToDataTransformer
     */
    public function createValidToDataTransformer(): ValidToDataTransformer
    {
        return new ValidToDataTransformer();
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function createStockEditOfferFormEventSubscriber(): EventSubscriberInterface
    {
        return new StockEditOfferFormEventSubscriber();
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
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

    /**
     * @return \Spryker\Zed\Mail\Business\MailFacadeInterface
     */
    public function getMailFacade(): MailFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface
     */
    public function getStateMachineFacade(): StateMachineFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_STATE_MACHINE);
    }

    /**
     * @return \Spryker\Service\FileManager\FileManagerServiceInterface
     */
    public function getFileManagerService(): FileManagerServiceInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_FILE_MANAGER);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getProductQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_PRODUCT);
    }

    /**
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageQuery
     */
    public function getProductImageQuery(): SpyProductImageQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacade
     */
    public function getLocaleFacade(): LocaleFacade
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    public function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface
     */
    public function getServicePointFacade(): ServicePointFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SERVICE_POINT);
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_SALES_ORDER_ITEM);
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface
     */
    public function getShipmentTypeFacade(): ShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SHIPMENT_TYPE);
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_PRODUCT_OFFER);
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    public function getProductFacade(): ProductFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\MerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    public function getOmsFacade(): OmsFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface
     */
    public function getMerchantStockFacade(): MerchantStockFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_MERCHANT_STOCK);
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    public function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): UtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface
     */
    public function getFileManagerFacade(): FileManagerFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_FILE_MANAGER);
    }

    /**
     * @return \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface
     */
    public function getSequenceNumberFacade(): SequenceNumberFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileInfoQuery
     */
    public function getFileInfoPropelQuery(): SpyFileInfoQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_FILE_INFO);
    }

    /**
     * @return \Orm\Zed\FileManager\Persistence\SpyFileQuery
     */
    public function getFilePropelQuery(): SpyFileQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_FILE);
    }

    /**
     * @return \Spryker\Zed\Translator\Business\TranslatorFacadeInterface
     */
    public function getTranslatorFacade(): TranslatorFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_TRANSLATOR);
    }

    /**
     * @return \Spryker\Zed\Company\Business\CompanyFacadeInterface
     */
    public function getCompanyFacade(): CompanyFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_COMPANY);
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): CompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface
     */
    public function getCompanyBusinessUnitFacade(): CompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    public function getDateTimeService(): UtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    public function getCustomerFacade(): CustomerFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_CUSTOMER);
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

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\DataProvider\SspAssetFormDataProvider
     */
    public function createSspAssetFormDataProvider(): SspAssetFormDataProvider
    {
        return new SspAssetFormDataProvider(
            $this->getFacade(),
            $this->getConfig(),
            $this->getCompanyBusinessUnitFacade(),
            $this->getCompanyFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Mapper\SspAssetFormDataToTransferMapperInterface
     */
    public function createSspAssetFormDataToTransferMapper(): SspAssetFormDataToTransferMapperInterface
    {
        return new SspAssetFormDataToTransferMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Tabs\SspAssetTabs
     */
    public function createSspAssetTabs(): SspAssetTabs
    {
        return new SspAssetTabs();
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Table\AssignedBusinessUnitTable
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
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Table\SspInquiryTable
     */
    public function createAssetSspInquiryTable(SspAssetTransfer $sspAssetTransfer): AssetSspInquiryTable
    {
        return new AssetSspInquiryTable(
            $sspAssetTransfer,
            $this->getSspInquirySspAssetQuery(),
            $this->getUtilDateTimeService(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetConditionsTransfer $sspAssetConditionsTransfer
     *
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Table\SspAssetTable
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
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnitQuery
     */
    public function getSspAssetToCompanyBusinessUnitQuery(): SpySspAssetToCompanyBusinessUnitQuery
    {
        return SpySspAssetToCompanyBusinessUnitQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySspAssetQuery
     */
    public function getSspInquirySspAssetQuery(): SpySspInquirySspAssetQuery
    {
        return SpySspInquirySspAssetQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery
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
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Form\DataProvider\SspAssetFilterFormDataProvider
     */
    public function createSspAssetFilterFormDataProvider(): SspAssetFilterFormDataProvider
    {
        return new SspAssetFilterFormDataProvider(
            $this->getConfig(),
        );
    }

    /**
     * @param string $assetReference
     *
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Table\SspServiceTable
     */
    public function createAssetSspServiceTable(string $assetReference): SspServiceTable
    {
        return new SspServiceTable(
            $assetReference,
            $this->getSalesOrderItemQuery(),
            $this->getUtilDateTimeService(),
        );
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_SALES_ORDER_ITEM);
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\SspAssetItemExpanderInterface
     */
    public function createSspAssetItemExpander(): SspAssetItemExpanderInterface
    {
        return new SspAssetItemExpander(
            $this->getFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Saver\SalesOrderItemSspAssetSaverInterface
     */
    public function createSalesOrderItemSspAssetSaver(): SalesOrderItemSspAssetSaverInterface
    {
        return new SalesOrderItemSspAssetSaver(
            $this->getEntityManager(),
            $this->getFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Extractor\SalesOrderItemIdExtractorInterface
     */
    public function createSalesOrderItemIdExtractor(): SalesOrderItemIdExtractorInterface
    {
        return new SalesOrderItemIdExtractor();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Expander\OrderItemSspAssetExpanderInterface
     */
    public function createOrderItemSspAssetExpander(): OrderItemSspAssetExpanderInterface
    {
        return new OrderItemSspAssetExpander(
            $this->getRepository(),
            $this->createSalesOrderItemIdExtractor(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Mapper\ProductAbstractTypeProductFormMapperInterface
     */
    public function createProductAbstractTypeProductFormMapper(): ProductAbstractTypeProductFormMapperInterface
    {
        return new ProductAbstractTypeProductFormMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\Expander\ServiceDateTimeEnabledProductConcreteFormExpanderInterface
     */
    public function createServiceDateTimeEnabledProductConcreteFormExpander(): ServiceDateTimeEnabledProductConcreteFormExpanderInterface
    {
        return new ServiceDateTimeEnabledProductConcreteFormExpander(
            $this->createServiceDateTimeEnabledProductConcreteFormDataProvider(),
            $this->createServiceDateTimeEnabledProductConcreteForm(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form\Expander\ShipmentTypeProductConcreteFormExpanderInterface
     */
    public function createShipmentTypeProductConcreteFormExpander(): ShipmentTypeProductConcreteFormExpanderInterface
    {
        return new ShipmentTypeProductConcreteFormExpander(
            $this->createShipmentTypeProductConcreteFormDataProvider(),
            $this->createShipmentTypeProductConcreteForm(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Communication\Service\Expander\ProductOfferTableActionExpanderInterface
     */
    public function createProductOfferTableActionExpander(): ProductOfferTableActionExpanderInterface
    {
        return new ProductOfferTableActionExpander();
    }
}
