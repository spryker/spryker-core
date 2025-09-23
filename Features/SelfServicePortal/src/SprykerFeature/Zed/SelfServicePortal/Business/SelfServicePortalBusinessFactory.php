<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentTypeQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductToProductClassQuery;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\Comment\Business\CommentFacadeInterface;
use Spryker\Zed\Company\Business\CompanyFacadeInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\DataImport\Business\DataImportFactoryTrait;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\EventBehavior\Business\EventBehaviorFacadeInterface;
use Spryker\Zed\FileManager\Business\FileManagerFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Mail\Business\MailFacadeInterface;
use Spryker\Zed\Messenger\Business\MessengerFacadeInterface;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface;
use Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface;
use Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface;
use Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface;
use Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface;
use Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\DashboardDataExpander\SspAssetDashboardDataExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\DashboardDataExpander\SspAssetSspAssetDashboardDataExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\Step\AssignedBusinessUnitKeysToIdStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\Step\BusinessUnitKeyToIdCompanyBusinessUnitStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\Step\ExternalImageUrlValidationStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\Step\SspAssetBusinessUnitAssignmentStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\Step\SspAssetPublishEventWriterStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\DataImport\Step\SspAssetWriterStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\AssetFileExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\AssetFileExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\OrderItemSspAssetExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\OrderItemSspAssetExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\SspAssetItemExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\SspAssetItemExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Extractor\SalesOrderItemIdExtractor;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Extractor\SalesOrderItemIdExtractorInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Mapper\SspAssetSearchMapper;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Mapper\SspAssetSearchMapperInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Permission\SspAssetCustomerPermissionExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Permission\SspAssetCustomerPermissionExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetReader;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetSearchReader;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetSearchReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Storage\SspAssetStorageWriter;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Storage\SspAssetStorageWriterInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Validator\SspAssetValidator;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Validator\SspAssetValidatorInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer\FileSspAssetWriter;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer\FileSspAssetWriterInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer\SspAssetSearchWriter;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer\SspAssetSearchWriterInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer\SspAssetWriter;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer\SspAssetWriterInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Creator\FileAttachmentCreator;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Creator\FileAttachmentCreatorInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\DashboardDataExpander\FileDashboardDataExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\DashboardDataExpander\FileDashboardDataExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Deleter\FileAttachmentDeleter;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Deleter\FileAttachmentDeleterInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Permission\FileAttachmentCriteriaPermissionExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Permission\FileAttachmentPermissionChecker;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Permission\FileAttachmentPermissionCheckerInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Permission\FileAttachmentPermissionExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Reader\CompanyFileReader;
use SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Reader\CompanyFileReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Dashboard\Reader\DashboardReader;
use SprykerFeature\Zed\SelfServicePortal\Business\Dashboard\Reader\DashboardReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Expander\ProductClassProductAbstractMapExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Expander\ProductClassProductAbstractMapExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Approver\SspInquiryApprovalHandler;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Approver\SspInquiryApprovalHandlerInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Approver\SspInquiryRejectionHandler;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Approver\SspInquiryRejectionHandlerInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\DashboardDataExpander\InquiryDashboardDataExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\DashboardDataExpander\InquiryDashboardDataExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\DataImport\Step\CompanyUserKeyToIdCompanyUserStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\DataImport\Step\SspInquiryStateMachineWriterStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\DataImport\Step\SspInquiryWriterStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\DataImport\Step\StoreCodeToStoreIdStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\CommentsSspInquiryExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\CompanyUserSspInquiryExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\FileSspInquiryExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\ManualEventsSspInquiryExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SalesOrderSspInquiryExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspAssetSspInquiryExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquiryCriteriaExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquiryCriteriaExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquiryExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquirySspAssetExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquirySspAssetExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\StatusHistorySspInquiryExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PostCreate\FileSspInquiryPostCreateHook;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PostCreate\OrderSspInquiryPostCreateHook;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PostCreate\SspAssetSspInquiryPostCreateHook;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PostCreate\SspInquiryPostCreateHookInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PostCreate\StateMachineSspInquiryPostCreateHook;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PreCreate\FileSspInquiryPreCreateHook;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PreCreate\OrderSspInquiryPreCreateHook;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PreCreate\SspAssetSspInquiryPreCreateHook;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PreCreate\SspInquiryPreCreateHookInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PreCreate\StoreSspInquiryPreCreateHook;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Reader\SspInquiryReader;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Reader\SspInquiryReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Validator\SspInquiryValidator;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Validator\SspInquiryValidatorInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Writer\SspInquiryStateWriter;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Writer\SspInquiryStateWriterInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Writer\SspInquiryWriter;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Writer\SspInquiryWriterInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Canceler\OrderItemCanceler;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Canceler\OrderItemCancelerInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DashboardDataExpander\ServiceDashboardDataExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DashboardDataExpander\ServiceDashboardDataExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\Step\ProductClassKeyToIdProductClassStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\Step\ProductClassWriterStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\Step\ProductShipmentTypeWriterStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\Step\ProductSkuToIdProductStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\Step\ProductToProductClassSkuToIdProductStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\Step\ProductToProductClassWriterStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\DataImport\Step\ShipmentTypeKeyToIdShipmentTypeStep;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\OrderItemCancellableExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\OrderItemCancellableExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\OrderItemProductClassExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\OrderItemProductClassExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\OrderItemScheduleExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\OrderItemScheduleExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductClassExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductClassExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductClassProductConcreteStorageExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductClassProductConcreteStorageExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductConcreteClassExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductConcreteClassExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductConcreteShipmentTypeExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductConcreteShipmentTypeExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ServicePointItemExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ServicePointItemExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ShipmentTypeItemExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ShipmentTypeItemExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ShipmentTypeProductConcreteStorageExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ShipmentTypeProductConcreteStorageExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\SspAssetExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\SspAssetExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Filter\QuoteItemFilter;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Filter\QuoteItemFilterInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Grouper\ProductClassGrouper;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Grouper\ProductClassGrouperInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Grouper\ShipmentTypeGrouper;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Grouper\ShipmentTypeGrouperInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Indexer\ProductClassIndexer;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Indexer\ProductClassIndexerInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Permission\SspServiceCustomerPermissionExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Permission\SspServiceCustomerPermissionExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ProductClassReader;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ProductClassReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ProductShipmentTypeReader;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ProductShipmentTypeReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ServicePointReader;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ServicePointReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ServiceReader;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ServiceReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ShipmentTypeReader;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ShipmentTypeReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Resolver\PaymentMethodResolver;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Resolver\PaymentMethodResolverInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Saver\ProductClassSaver;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Saver\ProductClassSaverInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Saver\ProductShipmentTypeSaver;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Saver\ProductShipmentTypeSaverInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Updater\OrderItemScheduleUpdater;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Updater\OrderItemScheduleUpdaterInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Utility\SkuExtractor;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Utility\SkuExtractorInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\ServicePointSearch\ServicePointSearchCoordinatesExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\ServicePointSearch\ServicePointSearchCoordinatesExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\DataImport\Step\SspModelAssetWriterStep;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\DataImport\Step\SspModelProductListWriterStep;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\DataImport\Step\SspModelWriterStep;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Deleter\SspModelDeleter;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Deleter\SspModelDeleterInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Reader\SspModelReader;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Reader\SspModelReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Storage\SspModelStorageWriter;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Storage\SspModelStorageWriterInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Validator\SspModelValidator;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Validator\SspModelValidatorInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Writer\FileSspModelWriter;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Writer\FileSspModelWriterInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Writer\SspModelWriter;
use SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Writer\SspModelWriterInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalDependencyProvider;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface getEntityManager()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class SelfServicePortalBusinessFactory extends AbstractBusinessFactory
{
    use DataImportFactoryTrait;

    public function createCompanyFileReader(): CompanyFileReaderInterface
    {
        return new CompanyFileReader(
            $this->getRepository(),
            $this->createFileAttachmentPermissionChecker(),
            $this->createFileAttachmentPermissionExpander(),
        );
    }

    public function createFileAttachmentPermissionChecker(): FileAttachmentPermissionCheckerInterface
    {
        return new FileAttachmentPermissionChecker();
    }

    public function createFileAttachmentCreator(): FileAttachmentCreatorInterface
    {
        return new FileAttachmentCreator(
            $this->getEntityManager(),
        );
    }

    public function createFileAttachmentDeleter(): FileAttachmentDeleterInterface
    {
        return new FileAttachmentDeleter(
            $this->createCompanyFileReader(),
            $this->getEntityManager(),
        );
    }

    public function createFileDashboardDataExpander(): FileDashboardDataExpanderInterface
    {
        return new FileDashboardDataExpander($this->createCompanyFileReader(), $this->getConfig());
    }

    public function createAssetFileExpander(): AssetFileExpanderInterface
    {
        return new AssetFileExpander(
            $this->getRepository(),
        );
    }

    public function createProductShipmentTypeSaver(): ProductShipmentTypeSaverInterface
    {
        return new ProductShipmentTypeSaver(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getEventFacade(),
        );
    }

    public function createProductConcreteShipmentTypeExpander(): ProductConcreteShipmentTypeExpanderInterface
    {
        return new ProductConcreteShipmentTypeExpander($this->createProductShipmentTypeReader());
    }

    public function createOrderItemScheduleExpander(): OrderItemScheduleExpanderInterface
    {
        return new OrderItemScheduleExpander();
    }

    public function createProductShipmentTypeReader(): ProductShipmentTypeReaderInterface
    {
        return new ProductShipmentTypeReader(
            $this->getRepository(),
            $this->getShipmentTypeFacade(),
            $this->createShipmentTypeGrouper(),
        );
    }

    public function createShipmentTypeGrouper(): ShipmentTypeGrouperInterface
    {
        return new ShipmentTypeGrouper();
    }

    public function createShipmentTypeProductConcreteStorageExpander(): ShipmentTypeProductConcreteStorageExpanderInterface
    {
        return new ShipmentTypeProductConcreteStorageExpander(
            $this->createProductShipmentTypeReader(),
            $this->getRepository(),
        );
    }

    public function createPaymentMethodResolver(): PaymentMethodResolverInterface
    {
        return new PaymentMethodResolver(
            $this->getConfig(),
        );
    }

    public function createOrderItemScheduleUpdater(): OrderItemScheduleUpdaterInterface
    {
        return new OrderItemScheduleUpdater(
            $this->getSalesFacade(),
            $this->createPaymentMethodResolver(),
        );
    }

    public function createServiceReader(): ServiceReaderInterface
    {
        return new ServiceReader(
            $this->getRepository(),
            $this->createSspServiceCustomerPermissionExpander(),
        );
    }

    public function createSspServiceCustomerPermissionExpander(): SspServiceCustomerPermissionExpanderInterface
    {
        return new SspServiceCustomerPermissionExpander($this->getCompanyBusinessUnitFacade(), $this->getCompanyFacade());
    }

    public function createFileAttachmentPermissionExpander(): FileAttachmentPermissionExpanderInterface
    {
        return new FileAttachmentCriteriaPermissionExpander();
    }

    public function getProductShipmentTypeDataImporter(): DataImporterInterface
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getProductShipmentTypeDataImporterConfiguration(),
        );

        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBroker $dataSetStepBroker */
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createProductSkuToIdProductStep());
        $dataSetStepBroker->addStep($this->createShipmentTypeKeyToIdShipmentTypeStep());
        $dataSetStepBroker->addStep($this->createProductShipmentTypeWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    public function createProductSkuToIdProductStep(): DataImportStepInterface
    {
        return new ProductSkuToIdProductStep(
            $this->getProductQuery(),
        );
    }

    public function createProductToProductClassSkuToIdProductStep(): DataImportStepInterface
    {
        return new ProductToProductClassSkuToIdProductStep(
            $this->getProductQuery(),
        );
    }

    public function createShipmentTypeKeyToIdShipmentTypeStep(): DataImportStepInterface
    {
        return new ShipmentTypeKeyToIdShipmentTypeStep(
            $this->getShipmentTypeQuery(),
        );
    }

    public function createProductShipmentTypeWriterStep(): DataImportStepInterface
    {
        return new ProductShipmentTypeWriterStep(
            $this->getProductShipmentTypeQuery(),
        );
    }

    public function createShipmentTypeItemExpander(): ShipmentTypeItemExpanderInterface
    {
        return new ShipmentTypeItemExpander(
            $this->createShipmentTypeReader(),
            $this->getRepository(),
            $this->getProductOfferShipmentTypeFacade(),
        );
    }

    public function createServicePointItemExpander(): ServicePointItemExpanderInterface
    {
        return new ServicePointItemExpander(
            $this->createServicePointReader(),
        );
    }

    public function createServicePointSearchCoordinatesExpander(): ServicePointSearchCoordinatesExpanderInterface
    {
        return new ServicePointSearchCoordinatesExpander();
    }

    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader(
            $this->getShipmentTypeFacade(),
            $this->getConfig(),
        );
    }

    public function createServicePointReader(): ServicePointReaderInterface
    {
        return new ServicePointReader(
            $this->getServicePointFacade(),
        );
    }

    public function getProductClassDataImporter(): DataImporterInterface
    {
        $config = $this->getConfig()->getProductClassDataImporterConfiguration();

        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig($config);

        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBroker $dataSetStepBroker */
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createProductClassWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    public function getProductToProductClassDataImporter(): DataImporterInterface
    {
        $config = $this->getConfig()->getProductToProductClassDataImporterConfiguration();

        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig($config);

        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBroker $dataSetStepBroker */
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createProductToProductClassSkuToIdProductStep());
        $dataSetStepBroker->addStep($this->createProductClassKeyToIdProductClassStep());
        $dataSetStepBroker->addStep($this->createProductToProductClassWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    public function createProductClassWriterStep(): DataImportStepInterface
    {
        return new ProductClassWriterStep(
            $this->getProductClassQuery(),
        );
    }

    public function createProductClassKeyToIdProductClassStep(): DataImportStepInterface
    {
        return new ProductClassKeyToIdProductClassStep(
            $this->getProductClassQuery(),
        );
    }

    public function createProductToProductClassWriterStep(): DataImportStepInterface
    {
        return new ProductToProductClassWriterStep(
            $this->getProductToProductClassQuery(),
            $this->getProductQuery(),
            $this->getProductClassQuery(),
        );
    }

    public function createOrderItemCanceler(): OrderItemCancelerInterface
    {
        return new OrderItemCanceler(
            $this->getOmsFacade(),
        );
    }

    public function getProductClassQuery(): SpyProductClassQuery
    {
        return SpyProductClassQuery::create();
    }

    public function getProductToProductClassQuery(): SpyProductToProductClassQuery
    {
        return SpyProductToProductClassQuery::create();
    }

    public function createQuoteItemFilter(): QuoteItemFilterInterface
    {
        return new QuoteItemFilter(
            $this->getConfig(),
            $this->getMessengerFacade(),
        );
    }

    public function createDashboardReader(): DashboardReaderInterface
    {
        return new DashboardReader($this->getDashboardDataExpanderPlugins());
    }

    public function createSspInquiryWriter(): SspInquiryWriterInterface
    {
        return new SspInquiryWriter(
            $this->getEntityManager(),
            $this->getSequenceNumberFacade(),
            $this->getStateMachineFacade(),
            $this->getConfig(),
            $this->createSspInquiryValidator(),
            $this->getSspInquiryPreCreateHooks(),
            $this->getSspInquiryPostCreateHooks(),
        );
    }

    public function createSspInquiryStateWriter(): SspInquiryStateWriterInterface
    {
        return new SspInquiryStateWriter(
            $this->createSspInquiryReader(),
            $this->getStateMachineFacade(),
            $this->getConfig(),
        );
    }

    public function createSspInquiryApprovalHandler(): SspInquiryApprovalHandlerInterface
    {
        return new SspInquiryApprovalHandler(
            $this->createSspInquiryReader(),
            $this->getMailFacade(),
            $this->getCustomerFacade(),
            $this->getConfig(),
        );
    }

    public function createSspInquiryRejectionHandler(): SspInquiryRejectionHandlerInterface
    {
        return new SspInquiryRejectionHandler(
            $this->createSspInquiryReader(),
            $this->getMailFacade(),
            $this->getCustomerFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return array<\SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquiryExpanderInterface>
     */
    public function getSspInquiryExpanders(): array
    {
        return [
            $this->createManualEventsSspInquiryExpander(),
            $this->createFileSspInquiryExpander(),
            $this->createSalesOrderSspInquiryExpander(),
            $this->createCompanyUserSspInquiryExpander(),
            $this->createStatusHistorySspInquiryExpander(),
            $this->createCommentsSspInquiryExpander(),
            $this->createSsAssetSspInquiryExpander(),
        ];
    }

    public function createManualEventsSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new ManualEventsSspInquiryExpander(
            $this->getConfig(),
            $this->getStateMachineFacade(),
        );
    }

    public function createStatusHistorySspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new StatusHistorySspInquiryExpander(
            $this->getConfig(),
            $this->getStateMachineFacade(),
        );
    }

    public function createFileSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new FileSspInquiryExpander(
            $this->getFileManagerFacade(),
            $this->getRepository(),
        );
    }

    public function createCompanyUserSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new CompanyUserSspInquiryExpander(
            $this->getCompanyUserFacade(),
        );
    }

    public function createSalesOrderSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new SalesOrderSspInquiryExpander(
            $this->getRepository(),
            $this->getSalesFacade(),
        );
    }

    public function createSsAssetSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new SspAssetSspInquiryExpander(
            $this->getRepository(),
            $this->createSspAssetReader(),
        );
    }

    public function createCommentsSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new CommentsSspInquiryExpander(
            $this->getCommentFacade(),
        );
    }

    public function createSspInquiryReader(): SspInquiryReaderInterface
    {
        return new SspInquiryReader(
            $this->getRepository(),
            $this->getSspInquiryExpanders(),
            $this->createSspInquiryConditionExpander(),
        );
    }

    public function createSspInquiryValidator(): SspInquiryValidatorInterface
    {
        return new SspInquiryValidator($this->getConfig());
    }

    /**
     * @return array<\SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PreCreate\SspInquiryPreCreateHookInterface>
     */
    public function getSspInquiryPreCreateHooks(): array
    {
        return [
            $this->createFileSspInquiryPreCreateHook(),
            $this->createOrderSspInquiryPreCreateHook(),
            $this->createStoreSspInquiryPreCreateHook(),
            $this->createSspAssetSspInquiryPreCreateHook(),
        ];
    }

    /**
     * @return array<\SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PostCreate\SspInquiryPostCreateHookInterface>
     */
    public function getSspInquiryPostCreateHooks(): array
    {
        return [
            $this->createOrderSspInquiryPostCreateHook(),
            $this->createFileSspInquiryPostCreateHook(),
            $this->createStateMachineSspInquiryPostCreateHook(),
            $this->createSspAssetSspInquiryPostCreateHook(),
        ];
    }

    public function createFileSspInquiryPreCreateHook(): SspInquiryPreCreateHookInterface
    {
        return new FileSspInquiryPreCreateHook(
            $this->getFileManagerFacade(),
            $this->getConfig(),
        );
    }

    public function createStoreSspInquiryPreCreateHook(): SspInquiryPreCreateHookInterface
    {
        return new StoreSspInquiryPreCreateHook(
            $this->getStoreFacade(),
        );
    }

    public function createOrderSspInquiryPreCreateHook(): SspInquiryPreCreateHookInterface
    {
        return new OrderSspInquiryPreCreateHook(
            $this->getSalesFacade(),
        );
    }

    public function createSspAssetSspInquiryPreCreateHook(): SspInquiryPreCreateHookInterface
    {
        return new SspAssetSspInquiryPreCreateHook(
            $this->createSspAssetReader(),
        );
    }

    public function createOrderSspInquiryPostCreateHook(): SspInquiryPostCreateHookInterface
    {
        return new OrderSspInquiryPostCreateHook(
            $this->getEntityManager(),
        );
    }

    public function createFileSspInquiryPostCreateHook(): SspInquiryPostCreateHookInterface
    {
        return new FileSspInquiryPostCreateHook(
            $this->getEntityManager(),
        );
    }

    public function createSspAssetSspInquiryPostCreateHook(): SspInquiryPostCreateHookInterface
    {
        return new SspAssetSspInquiryPostCreateHook(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PostCreate\StateMachineSspInquiryPostCreateHook
     */
    public function createStateMachineSspInquiryPostCreateHook(): SspInquiryPostCreateHookInterface
    {
        return new StateMachineSspInquiryPostCreateHook(
            $this->getConfig(),
            $this->getStateMachineFacade(),
        );
    }

    public function getSspInquiryDataImporter(): DataImporterInterface
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getSspInquiryDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        if ($dataSetStepBroker instanceof DataImportStepAwareInterface) {
            $dataSetStepBroker
                ->addStep($this->createCompanyUserKeyToIdCompanyUserStep())
                ->addStep($this->createStoreCodeToStoreIdStep())
                ->addStep($this->createSspInquiryWriterStep())
                ->addStep($this->createSspInquiryStateMachineWriterStep());
        }

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    public function createSspInquiryWriterStep(): DataImportStepInterface
    {
        return new SspInquiryWriterStep(
            $this->getConfig(),
            $this->getSequenceNumberFacade(),
        );
    }

    public function getSspAssetDataImporter(): DataImporterInterface
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getSspAssetDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        if ($dataSetStepBroker instanceof DataImportStepAwareInterface) {
            $dataSetStepBroker
                ->addStep($this->createBusinessUnitKeyToIdCompanyBusinessUnitStep())
                ->addStep($this->createAssignedBusinessUnitKeysToIdStep())
                ->addStep($this->createExternalImageUrlValidationStep())
                ->addStep($this->createSspAssetWriterStep())
                ->addStep($this->createSspAssetBusinessUnitAssignmentStep())
                ->addStep($this->createSspAssetPublishEventWriterStep());
        }

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    public function createSspAssetBusinessUnitAssignmentStep(): DataImportStepInterface
    {
        return new SspAssetBusinessUnitAssignmentStep();
    }

    public function createSspAssetPublishEventWriterStep(): DataImportStepInterface
    {
        return new SspAssetPublishEventWriterStep(
            $this->getEventFacade(),
        );
    }

    public function createSspAssetWriterStep(): DataImportStepInterface
    {
        return new SspAssetWriterStep(
            $this->getConfig(),
            $this->getSequenceNumberFacade(),
        );
    }

    public function createBusinessUnitKeyToIdCompanyBusinessUnitStep(): DataImportStepInterface
    {
        return new BusinessUnitKeyToIdCompanyBusinessUnitStep();
    }

    public function createAssignedBusinessUnitKeysToIdStep(): DataImportStepInterface
    {
        return new AssignedBusinessUnitKeysToIdStep();
    }

    public function createExternalImageUrlValidationStep(): DataImportStepInterface
    {
        return new ExternalImageUrlValidationStep();
    }

    public function createCompanyUserKeyToIdCompanyUserStep(): DataImportStepInterface
    {
        return new CompanyUserKeyToIdCompanyUserStep($this->getCompanyUserQuery());
    }

    public function createStoreCodeToStoreIdStep(): DataImportStepInterface
    {
        return new StoreCodeToStoreIdStep($this->getStoreFacade());
    }

    public function createSspInquiryStateMachineWriterStep(): DataImportStepInterface
    {
        return new SspInquiryStateMachineWriterStep(
            $this->getStateMachineFacade(),
            $this->getConfig(),
        );
    }

    public function createInquiryDashboardDataExpander(): InquiryDashboardDataExpanderInterface
    {
        return new InquiryDashboardDataExpander($this->createSspInquiryReader(), $this->getConfig());
    }

    public function createSspInquiryConditionExpander(): SspInquiryCriteriaExpanderInterface
    {
        return new SspInquiryCriteriaExpander();
    }

    public function createSspInquirySspAssetExpander(): SspInquirySspAssetExpanderInterface
    {
        return new SspInquirySspAssetExpander(
            $this->createSspInquiryReader(),
            $this->getConfig(),
        );
    }

    public function createSspAssetDashboardDataExpander(): SspAssetDashboardDataExpanderInterface
    {
        return new SspAssetSspAssetDashboardDataExpander($this->createSspAssetReader());
    }

    public function createServiceDashboardDataExpander(): ServiceDashboardDataExpanderInterface
    {
        return new ServiceDashboardDataExpander($this->createServiceReader(), $this->getConfig());
    }

    public function createSspAssetCustomerPermissionExpander(): SspAssetCustomerPermissionExpanderInterface
    {
        return new SspAssetCustomerPermissionExpander();
    }

    public function createSspAssetReader(): SspAssetReaderInterface
    {
        return new SspAssetReader(
            $this->getRepository(),
            $this->getFileManagerFacade(),
            $this->getSspAssetManagementExpanderPlugins(),
            $this->createSspAssetCustomerPermissionExpander(),
            $this->createSspAssetValidator(),
        );
    }

    public function createSspAssetWriter(): SspAssetWriterInterface
    {
        return new SspAssetWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createSspAssetValidator(),
            $this->getSequenceNumberFacade(),
            $this->getConfig(),
            $this->createFileSspAssetWriter(),
            $this->getCompanyBusinessUnitFacade(),
        );
    }

    public function createSspModelWriter(): SspModelWriterInterface
    {
        return new SspModelWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createSspModelValidator(),
            $this->getSequenceNumberFacade(),
            $this->getConfig(),
            $this->createFileSspModelWriter(),
        );
    }

    public function createSspModelValidator(): SspModelValidatorInterface
    {
        return new SspModelValidator($this->getConfig());
    }

    public function createFileSspModelWriter(): FileSspModelWriterInterface
    {
        return new FileSspModelWriter(
            $this->getFileManagerFacade(),
            $this->getConfig(),
        );
    }

    public function createFileSspAssetWriter(): FileSspAssetWriterInterface
    {
        return new FileSspAssetWriter(
            $this->getFileManagerFacade(),
            $this->getConfig(),
        );
    }

    public function createSspAssetExpander(): SspAssetExpanderInterface
    {
        return new SspAssetExpander($this->createServiceReader());
    }

    public function createSspAssetValidator(): SspAssetValidatorInterface
    {
        return new SspAssetValidator();
    }

    public function createOrderItemSspAssetExpander(): OrderItemSspAssetExpanderInterface
    {
        return new OrderItemSspAssetExpander(
            $this->createSspAssetReader(),
            $this->createSalesOrderItemIdExtractor(),
        );
    }

    public function createSalesOrderItemIdExtractor(): SalesOrderItemIdExtractorInterface
    {
        return new SalesOrderItemIdExtractor();
    }

    public function getStateMachineFacade(): StateMachineFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_STATE_MACHINE);
    }

    public function getSequenceNumberFacade(): SequenceNumberFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

    public function getFileManagerFacade(): FileManagerFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_FILE_MANAGER);
    }

    public function getCompanyUserFacade(): CompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_COMPANY_USER);
    }

    public function getCompanyUserQuery(): SpyCompanyUserQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_COMPANY_USER);
    }

    public function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_STORE);
    }

    public function getCommentFacade(): CommentFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_COMMENT);
    }

    /**
     * @return array<int, \SprykerFeature\Zed\SelfServicePortal\Dependency\Plugin\DashboardDataExpanderPluginInterface>
     */
    public function getDashboardDataExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PLUGINS_DASHBOARD_DATA_PROVIDER);
    }

    public function getEventFacade(): EventFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_EVENT);
    }

    public function getShipmentTypeFacade(): ShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SHIPMENT_TYPE);
    }

    public function getProductQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_PRODUCT);
    }

    public function getShipmentTypeQuery(): SpyShipmentTypeQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_SHIPMENT_TYPE);
    }

    public function getProductShipmentTypeQuery(): SpyProductShipmentTypeQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_PRODUCT_SHIPMENT_TYPE);
    }

    public function getProductOfferShipmentTypeFacade(): ProductOfferShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_PRODUCT_OFFER_SHIPMENT_TYPE);
    }

    public function getCompanyFacade(): CompanyFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_COMPANY);
    }

    public function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SALES);
    }

    public function getServicePointFacade(): ServicePointFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SERVICE_POINT);
    }

    public function getOmsFacade(): OmsFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_OMS);
    }

    public function getMessengerFacade(): MessengerFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_MESSENGER);
    }

    public function getCompanyBusinessUnitFacade(): CompanyBusinessUnitFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @return array<\SprykerFeature\Zed\SelfServicePortal\Dependency\Plugin\SspAssetManagementExpanderPluginInterface>
     */
    public function getSspAssetManagementExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PLUGINS_SSP_ASSET_MANAGEMENT_EXPANDER);
    }

    public function getProductPageSearchFacade(): ProductPageSearchFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_PRODUCT_PAGE_SEARCH);
    }

    public function getProductStorageFacade(): ProductStorageFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_PRODUCT_STORAGE);
    }

    public function createProductClassSaver(): ProductClassSaverInterface
    {
        return new ProductClassSaver(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getProductPageSearchFacade(),
            $this->getProductStorageFacade(),
        );
    }

    public function createProductClassProductAbstractMapExpander(): ProductClassProductAbstractMapExpanderInterface
    {
        return new ProductClassProductAbstractMapExpander();
    }

    public function createProductClassProductConcreteStorageExpander(): ProductClassProductConcreteStorageExpanderInterface
    {
        return new ProductClassProductConcreteStorageExpander(
            $this->createProductClassReader(),
        );
    }

    public function createOrderItemCancellableExpander(): OrderItemCancellableExpanderInterface
    {
        return new OrderItemCancellableExpander(
            $this->getOmsFacade(),
        );
    }

    public function getMailFacade(): MailFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_MAIL);
    }

    public function getCustomerFacade(): CustomerFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_CUSTOMER);
    }

    public function createProductClassGrouper(): ProductClassGrouperInterface
    {
        return new ProductClassGrouper();
    }

    public function createOrderItemProductClassExpander(): OrderItemProductClassExpanderInterface
    {
        return new OrderItemProductClassExpander(
            $this->createProductClassGrouper(),
            $this->getRepository(),
            $this->createProductClassIndexer(),
            $this->createSkuExtractor(),
            $this->createSalesOrderItemIdExtractor(),
        );
    }

    public function createSspAssetItemExpander(): SspAssetItemExpanderInterface
    {
        return new SspAssetItemExpander(
            $this->createSspAssetReader(),
        );
    }

    public function createProductClassIndexer(): ProductClassIndexerInterface
    {
        return new ProductClassIndexer();
    }

    public function createProductClassExpander(): ProductClassExpanderInterface
    {
        return new ProductClassExpander(
            $this->getRepository(),
            $this->createProductClassIndexer(),
            $this->createSkuExtractor(),
        );
    }

    public function createProductConcreteClassExpander(): ProductConcreteClassExpanderInterface
    {
        return new ProductConcreteClassExpander(
            $this->createProductClassReader(),
        );
    }

    public function createSkuExtractor(): SkuExtractorInterface
    {
        return new SkuExtractor();
    }

    public function createProductClassReader(): ProductClassReaderInterface
    {
        return new ProductClassReader(
            $this->getRepository(),
            $this->createProductClassIndexer(),
        );
    }

    public function createSspModelStorageWriter(): SspModelStorageWriterInterface
    {
        return new SspModelStorageWriter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getEventBehaviorFacade(),
        );
    }

    public function createSspAssetStorageWriter(): SspAssetStorageWriterInterface
    {
        return new SspAssetStorageWriter(
            $this->getRepository(),
            $this->getEntityManager(),
            $this->getEventBehaviorFacade(),
        );
    }

    public function getEventBehaviorFacade(): EventBehaviorFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_EVENT_BEHAVIOR);
    }

    public function getSspModelDataImporter(): DataImporterInterface
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getSspModelDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        if ($dataSetStepBroker instanceof DataImportStepAwareInterface) {
            $dataSetStepBroker->addStep($this->createSspModelWriterStep());
        }

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    public function createSspModelWriterStep(): DataImportStepInterface
    {
        return new SspModelWriterStep(
            $this->getConfig(),
            $this->getSequenceNumberFacade(),
            $this->getEventFacade(),
        );
    }

    public function getSspAssetModelDataImporter(): DataImporterInterface
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getSspModelAssetDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        if ($dataSetStepBroker instanceof DataImportStepAwareInterface) {
            $dataSetStepBroker->addStep($this->createSspAssetModelWriterStep());
        }

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    public function createSspAssetModelWriterStep(): DataImportStepInterface
    {
        return new SspModelAssetWriterStep(
            $this->getEventFacade(),
        );
    }

    public function getSspModelProductListDataImporter(): DataImporterInterface
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getSspModelProductListDataImporterConfiguration(),
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        if ($dataSetStepBroker instanceof DataImportStepAwareInterface) {
            $dataSetStepBroker->addStep($this->createSspModelProductListWriterStep());
        }

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    public function createSspModelProductListWriterStep(): DataImportStepInterface
    {
        return new SspModelProductListWriterStep();
    }

    public function createSspAssetSearchWriter(): SspAssetSearchWriterInterface
    {
        return new SspAssetSearchWriter(
            $this->createSspAssetReader(),
            $this->createSspModelReader(),
            $this->getEventBehaviorFacade(),
            $this->createSspAssetSearchMapper(),
            $this->getEntityManager(),
        );
    }

    public function createSspAssetSearchMapper(): SspAssetSearchMapperInterface
    {
        return new SspAssetSearchMapper(
            $this->getUtilEncodingService(),
            $this->getStoreFacade(),
        );
    }

    public function createSspAssetSearchReader(): SspAssetSearchReaderInterface
    {
        return new SspAssetSearchReader(
            $this->getRepository(),
            $this->createSspAssetSearchMapper(),
        );
    }

    public function createSspModelReader(): SspModelReaderInterface
    {
        return new SspModelReader(
            $this->getRepository(),
            $this->getFileManagerFacade(),
        );
    }

    public function createSspModelDeleter(): SspModelDeleterInterface
    {
        return new SspModelDeleter(
            $this->getEntityManager(),
            $this->getRepository(),
        );
    }

    public function getUtilEncodingService(): UtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
