<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business;

use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductClassQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentTypeQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductToProductClassQuery;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Spryker\Zed\Comment\Business\CommentFacadeInterface;
use Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;
use Spryker\Zed\DataImport\Business\DataImportFactoryTrait;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\Event\Business\EventFacadeInterface;
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
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Deleter\SspAssetManagementFileDeleter;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Deleter\SspAssetManagementFileDeleterInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\AssetFileExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\AssetFileExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\OrderItemSspAssetExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\OrderItemSspAssetExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\SspAssetItemExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\SspAssetItemExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Extractor\SalesOrderItemIdExtractor;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Extractor\SalesOrderItemIdExtractorInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Permission\SspAssetCustomerPermissionExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Permission\SspAssetCustomerPermissionExpanderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetReader;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetReaderInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Validator\SspAssetValidator;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Validator\SspAssetValidatorInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer\FileSspAssetWriter;
use SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer\FileSspAssetWriterInterface;
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
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Writer\SspInquiryFileDeleter;
use SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Writer\SspInquiryFileDeleterInterface;
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
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductAbstractClassExpander;
use SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductAbstractClassExpanderInterface;
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
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalDependencyProvider;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface getEntityManager()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class SelfServicePortalBusinessFactory extends AbstractBusinessFactory
{
    use DataImportFactoryTrait;

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Reader\CompanyFileReaderInterface
     */
    public function createCompanyFileReader(): CompanyFileReaderInterface
    {
        return new CompanyFileReader(
            $this->getRepository(),
            $this->createFileAttachmentPermissionChecker(),
            $this->createFileAttachmentPermissionExpander(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Permission\FileAttachmentPermissionCheckerInterface
     */
    public function createFileAttachmentPermissionChecker(): FileAttachmentPermissionCheckerInterface
    {
        return new FileAttachmentPermissionChecker();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Creator\FileAttachmentCreatorInterface
     */
    public function createFileAttachmentCreator(): FileAttachmentCreatorInterface
    {
        return new FileAttachmentCreator(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Deleter\FileAttachmentDeleterInterface
     */
    public function createFileAttachmentDeleter(): FileAttachmentDeleterInterface
    {
        return new FileAttachmentDeleter(
            $this->createCompanyFileReader(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\DashboardDataExpander\FileDashboardDataExpanderInterface
     */
    public function createFileDashboardDataExpander(): FileDashboardDataExpanderInterface
    {
        return new FileDashboardDataExpander($this->createCompanyFileReader(), $this->getConfig());
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\AssetFileExpanderInterface
     */
    public function createAssetFileExpander(): AssetFileExpanderInterface
    {
        return new AssetFileExpander(
            $this->getRepository(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Saver\ProductShipmentTypeSaverInterface
     */
    public function createProductShipmentTypeSaver(): ProductShipmentTypeSaverInterface
    {
        return new ProductShipmentTypeSaver(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getEventFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductConcreteShipmentTypeExpanderInterface
     */
    public function createProductConcreteShipmentTypeExpander(): ProductConcreteShipmentTypeExpanderInterface
    {
        return new ProductConcreteShipmentTypeExpander($this->createProductShipmentTypeReader());
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\OrderItemScheduleExpanderInterface
     */
    public function createOrderItemScheduleExpander(): OrderItemScheduleExpanderInterface
    {
        return new OrderItemScheduleExpander();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ProductShipmentTypeReaderInterface
     */
    public function createProductShipmentTypeReader(): ProductShipmentTypeReaderInterface
    {
        return new ProductShipmentTypeReader(
            $this->getRepository(),
            $this->getShipmentTypeFacade(),
            $this->createShipmentTypeGrouper(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Grouper\ShipmentTypeGrouperInterface
     */
    public function createShipmentTypeGrouper(): ShipmentTypeGrouperInterface
    {
        return new ShipmentTypeGrouper();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ShipmentTypeProductConcreteStorageExpanderInterface
     */
    public function createShipmentTypeProductConcreteStorageExpander(): ShipmentTypeProductConcreteStorageExpanderInterface
    {
        return new ShipmentTypeProductConcreteStorageExpander(
            $this->createProductShipmentTypeReader(),
            $this->getRepository(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Resolver\PaymentMethodResolverInterface
     */
    public function createPaymentMethodResolver(): PaymentMethodResolverInterface
    {
        return new PaymentMethodResolver(
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Updater\OrderItemScheduleUpdaterInterface
     */
    public function createOrderItemScheduleUpdater(): OrderItemScheduleUpdaterInterface
    {
        return new OrderItemScheduleUpdater(
            $this->getSalesFacade(),
            $this->createPaymentMethodResolver(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ServiceReaderInterface
     */
    public function createServiceReader(): ServiceReaderInterface
    {
        return new ServiceReader(
            $this->getRepository(),
            $this->createSspServiceCustomerPermissionExpander(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Permission\SspServiceCustomerPermissionExpanderInterface
     */
    public function createSspServiceCustomerPermissionExpander(): SspServiceCustomerPermissionExpanderInterface
    {
        return new SspServiceCustomerPermissionExpander();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\CompanyFile\Permission\FileAttachmentPermissionExpanderInterface
     */
    public function createFileAttachmentPermissionExpander(): FileAttachmentPermissionExpanderInterface
    {
        return new FileAttachmentCriteriaPermissionExpander();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
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

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductSkuToIdProductStep(): DataImportStepInterface
    {
        return new ProductSkuToIdProductStep(
            $this->getProductQuery(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductToProductClassSkuToIdProductStep(): DataImportStepInterface
    {
        return new ProductToProductClassSkuToIdProductStep(
            $this->getProductQuery(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createShipmentTypeKeyToIdShipmentTypeStep(): DataImportStepInterface
    {
        return new ShipmentTypeKeyToIdShipmentTypeStep(
            $this->getShipmentTypeQuery(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductShipmentTypeWriterStep(): DataImportStepInterface
    {
        return new ProductShipmentTypeWriterStep(
            $this->getProductShipmentTypeQuery(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ShipmentTypeItemExpanderInterface
     */
    public function createShipmentTypeItemExpander(): ShipmentTypeItemExpanderInterface
    {
        return new ShipmentTypeItemExpander(
            $this->createShipmentTypeReader(),
            $this->getRepository(),
            $this->getProductOfferShipmentTypeFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ServicePointItemExpanderInterface
     */
    public function createServicePointItemExpander(): ServicePointItemExpanderInterface
    {
        return new ServicePointItemExpander(
            $this->createServicePointReader(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\ServicePointSearch\ServicePointSearchCoordinatesExpanderInterface
     */
    public function createServicePointSearchCoordinatesExpander(): ServicePointSearchCoordinatesExpanderInterface
    {
        return new ServicePointSearchCoordinatesExpander();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ShipmentTypeReaderInterface
     */
    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader(
            $this->getShipmentTypeFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ServicePointReaderInterface
     */
    public function createServicePointReader(): ServicePointReaderInterface
    {
        return new ServicePointReader(
            $this->getServicePointFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
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

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
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

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductClassWriterStep(): DataImportStepInterface
    {
        return new ProductClassWriterStep(
            $this->getProductClassQuery(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductClassKeyToIdProductClassStep(): DataImportStepInterface
    {
        return new ProductClassKeyToIdProductClassStep(
            $this->getProductClassQuery(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductToProductClassWriterStep(): DataImportStepInterface
    {
        return new ProductToProductClassWriterStep(
            $this->getProductToProductClassQuery(),
            $this->getProductQuery(),
            $this->getProductClassQuery(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Canceler\OrderItemCancelerInterface
     */
    public function createOrderItemCanceler(): OrderItemCancelerInterface
    {
        return new OrderItemCanceler(
            $this->getOmsFacade(),
        );
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyProductClassQuery
     */
    public function getProductClassQuery(): SpyProductClassQuery
    {
        return SpyProductClassQuery::create();
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyProductToProductClassQuery
     */
    public function getProductToProductClassQuery(): SpyProductToProductClassQuery
    {
        return SpyProductToProductClassQuery::create();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Filter\QuoteItemFilterInterface
     */
    public function createQuoteItemFilter(): QuoteItemFilterInterface
    {
        return new QuoteItemFilter(
            $this->getConfig(),
            $this->getMessengerFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Dashboard\Reader\DashboardReaderInterface
     */
    public function createDashboardReader(): DashboardReaderInterface
    {
        return new DashboardReader($this->getDashboardDataExpanderPlugins());
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Writer\SspInquiryWriterInterface
     */
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

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Writer\SspInquiryStateWriterInterface
     */
    public function createSspInquiryStateWriter(): SspInquiryStateWriterInterface
    {
        return new SspInquiryStateWriter(
            $this->createSspInquiryReader(),
            $this->getStateMachineFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Approver\SspInquiryApprovalHandlerInterface
     */
    public function createSspInquiryApprovalHandler(): SspInquiryApprovalHandlerInterface
    {
        return new SspInquiryApprovalHandler(
            $this->createSspInquiryReader(),
            $this->getMailFacade(),
            $this->getCustomerFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Approver\SspInquiryRejectionHandlerInterface
     */
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

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquiryExpanderInterface
     */
    public function createManualEventsSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new ManualEventsSspInquiryExpander(
            $this->getConfig(),
            $this->getStateMachineFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquiryExpanderInterface
     */
    public function createStatusHistorySspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new StatusHistorySspInquiryExpander(
            $this->getConfig(),
            $this->getStateMachineFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquiryExpanderInterface
     */
    public function createFileSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new FileSspInquiryExpander(
            $this->getFileManagerFacade(),
            $this->getRepository(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquiryExpanderInterface
     */
    public function createCompanyUserSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new CompanyUserSspInquiryExpander(
            $this->getCompanyUserFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquiryExpanderInterface
     */
    public function createSalesOrderSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new SalesOrderSspInquiryExpander(
            $this->getRepository(),
            $this->getSalesFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquiryExpanderInterface
     */
    public function createSsAssetSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new SspAssetSspInquiryExpander(
            $this->getRepository(),
            $this->createSspAssetReader(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquiryExpanderInterface
     */
    public function createCommentsSspInquiryExpander(): SspInquiryExpanderInterface
    {
        return new CommentsSspInquiryExpander(
            $this->getCommentFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Reader\SspInquiryReaderInterface
     */
    public function createSspInquiryReader(): SspInquiryReaderInterface
    {
        return new SspInquiryReader(
            $this->getRepository(),
            $this->getSspInquiryExpanders(),
            $this->createSspInquiryConditionExpander(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Writer\SspInquiryFileDeleterInterface
     */
    public function createSspInquiryFileDeleter(): SspInquiryFileDeleterInterface
    {
        return new SspInquiryFileDeleter(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Validator\SspInquiryValidatorInterface
     */
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

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PreCreate\SspInquiryPreCreateHookInterface
     */
    public function createFileSspInquiryPreCreateHook(): SspInquiryPreCreateHookInterface
    {
        return new FileSspInquiryPreCreateHook(
            $this->getFileManagerFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PreCreate\SspInquiryPreCreateHookInterface
     */
    public function createStoreSspInquiryPreCreateHook(): SspInquiryPreCreateHookInterface
    {
        return new StoreSspInquiryPreCreateHook(
            $this->getStoreFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PreCreate\SspInquiryPreCreateHookInterface
     */
    public function createOrderSspInquiryPreCreateHook(): SspInquiryPreCreateHookInterface
    {
        return new OrderSspInquiryPreCreateHook(
            $this->getSalesFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PreCreate\SspInquiryPreCreateHookInterface
     */
    public function createSspAssetSspInquiryPreCreateHook(): SspInquiryPreCreateHookInterface
    {
        return new SspAssetSspInquiryPreCreateHook(
            $this->createSspAssetReader(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PostCreate\SspInquiryPostCreateHookInterface
     */
    public function createOrderSspInquiryPostCreateHook(): SspInquiryPostCreateHookInterface
    {
        return new OrderSspInquiryPostCreateHook(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PostCreate\SspInquiryPostCreateHookInterface
     */
    public function createFileSspInquiryPostCreateHook(): SspInquiryPostCreateHookInterface
    {
        return new FileSspInquiryPostCreateHook(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Hooks\PostCreate\SspInquiryPostCreateHookInterface
     */
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

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
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

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createSspInquiryWriterStep(): DataImportStepInterface
    {
        return new SspInquiryWriterStep(
            $this->getConfig(),
            $this->getSequenceNumberFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createCompanyUserKeyToIdCompanyUserStep(): DataImportStepInterface
    {
        return new CompanyUserKeyToIdCompanyUserStep($this->getCompanyUserQuery());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createStoreCodeToStoreIdStep(): DataImportStepInterface
    {
        return new StoreCodeToStoreIdStep($this->getStoreFacade());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createSspInquiryStateMachineWriterStep(): DataImportStepInterface
    {
        return new SspInquiryStateMachineWriterStep(
            $this->getStateMachineFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\DashboardDataExpander\InquiryDashboardDataExpanderInterface
     */
    public function createInquiryDashboardDataExpander(): InquiryDashboardDataExpanderInterface
    {
        return new InquiryDashboardDataExpander($this->createSspInquiryReader(), $this->getConfig());
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquiryCriteriaExpanderInterface
     */
    public function createSspInquiryConditionExpander(): SspInquiryCriteriaExpanderInterface
    {
        return new SspInquiryCriteriaExpander();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Expander\SspInquirySspAssetExpanderInterface
     */
    public function createSspInquirySspAssetExpander(): SspInquirySspAssetExpanderInterface
    {
        return new SspInquirySspAssetExpander(
            $this->createSspInquiryReader(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Asset\DashboardDataExpander\SspAssetDashboardDataExpanderInterface
     */
    public function createSspAssetDashboardDataExpander(): SspAssetDashboardDataExpanderInterface
    {
        return new SspAssetSspAssetDashboardDataExpander($this->createSspAssetReader());
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\DashboardDataExpander\ServiceDashboardDataExpanderInterface
     */
    public function createServiceDashboardDataExpander(): ServiceDashboardDataExpanderInterface
    {
        return new ServiceDashboardDataExpander($this->createServiceReader(), $this->getConfig());
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Permission\SspAssetCustomerPermissionExpanderInterface
     */
    public function createSspAssetCustomerPermissionExpander(): SspAssetCustomerPermissionExpanderInterface
    {
        return new SspAssetCustomerPermissionExpander();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Reader\SspAssetReaderInterface
     */
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

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer\SspAssetWriterInterface
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
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Writer\FileSspAssetWriterInterface
     */
    public function createFileSspAssetWriter(): FileSspAssetWriterInterface
    {
        return new FileSspAssetWriter(
            $this->getFileManagerFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\SspAssetExpanderInterface
     */
    public function createSspAssetExpander(): SspAssetExpanderInterface
    {
        return new SspAssetExpander($this->createServiceReader());
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Validator\SspAssetValidatorInterface
     */
    public function createSspAssetValidator(): SspAssetValidatorInterface
    {
        return new SspAssetValidator();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\OrderItemSspAssetExpanderInterface
     */
    public function createOrderItemSspAssetExpander(): OrderItemSspAssetExpanderInterface
    {
        return new OrderItemSspAssetExpander(
            $this->createSspAssetReader(),
            $this->createSalesOrderItemIdExtractor(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Extractor\SalesOrderItemIdExtractorInterface
     */
    public function createSalesOrderItemIdExtractor(): SalesOrderItemIdExtractorInterface
    {
        return new SalesOrderItemIdExtractor();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface
     */
    public function getPersistenceEntityManager(): SelfServicePortalEntityManagerInterface
    {
        return $this->getEntityManager();
    }

    /**
     * @return \Spryker\Zed\StateMachine\Business\StateMachineFacadeInterface
     */
    public function getStateMachineFacade(): StateMachineFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_STATE_MACHINE);
    }

    /**
     * @return \Spryker\Zed\SequenceNumber\Business\SequenceNumberFacadeInterface
     */
    public function getSequenceNumberFacade(): SequenceNumberFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SEQUENCE_NUMBER);
    }

    /**
     * @return \Spryker\Zed\FileManager\Business\FileManagerFacadeInterface
     */
    public function getFileManagerFacade(): FileManagerFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_FILE_MANAGER);
    }

    /**
     * @return \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): CompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery
     */
    public function getCompanyUserQuery(): SpyCompanyUserQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    public function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Comment\Business\CommentFacadeInterface
     */
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

    /**
     * @return \Spryker\Zed\Event\Business\EventFacadeInterface
     */
    public function getEventFacade(): EventFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface
     */
    public function getShipmentTypeFacade(): ShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SHIPMENT_TYPE);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getProductQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_PRODUCT);
    }

    /**
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery
     */
    public function getShipmentTypeQuery(): SpyShipmentTypeQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_SHIPMENT_TYPE);
    }

    /**
     * @return \Orm\Zed\SelfServicePortal\Persistence\SpyProductShipmentTypeQuery
     */
    public function getProductShipmentTypeQuery(): SpyProductShipmentTypeQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_PRODUCT_SHIPMENT_TYPE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface
     */
    public function getProductOfferShipmentTypeFacade(): ProductOfferShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_PRODUCT_OFFER_SHIPMENT_TYPE);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getProductAbstractQuery(): SpyProductAbstractQuery
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PROPEL_QUERY_PRODUCT_ABSTRACT);
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    public function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface
     */
    public function getServicePointFacade(): ServicePointFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_SERVICE_POINT);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    public function getOmsFacade(): OmsFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\Messenger\Business\MessengerFacadeInterface
     */
    public function getMessengerFacade(): MessengerFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return array<\SprykerFeature\Zed\SelfServicePortal\Dependency\Plugin\SspAssetManagementExpanderPluginInterface>
     */
    public function getSspAssetManagementExpanderPlugins(): array
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::PLUGINS_SSP_ASSET_MANAGEMENT_EXPANDER);
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface
     */
    public function getProductPageSearchFacade(): ProductPageSearchFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_PRODUCT_PAGE_SEARCH);
    }

    /**
     * @return \Spryker\Zed\ProductStorage\Business\ProductStorageFacadeInterface
     */
    public function getProductStorageFacade(): ProductStorageFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Saver\ProductClassSaverInterface
     */
    public function createProductClassSaver(): ProductClassSaverInterface
    {
        return new ProductClassSaver(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->getProductPageSearchFacade(),
            $this->getProductStorageFacade(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Expander\ProductClassProductAbstractMapExpanderInterface
     */
    public function createProductClassProductAbstractMapExpander(): ProductClassProductAbstractMapExpanderInterface
    {
        return new ProductClassProductAbstractMapExpander();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductClassProductConcreteStorageExpanderInterface
     */
    public function createProductClassProductConcreteStorageExpander(): ProductClassProductConcreteStorageExpanderInterface
    {
        return new ProductClassProductConcreteStorageExpander(
            $this->createProductClassReader(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Deleter\SspAssetManagementFileDeleterInterface
     */
    public function createSspAssetManagementFileDeleter(): SspAssetManagementFileDeleterInterface
    {
        return new SspAssetManagementFileDeleter(
            $this->createSspAssetReader(),
            $this->createSspAssetWriter(),
            $this->getEntityManager(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\OrderItemCancellableExpanderInterface
     */
    public function createOrderItemCancellableExpander(): OrderItemCancellableExpanderInterface
    {
        return new OrderItemCancellableExpander(
            $this->getOmsFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\Mail\Business\MailFacadeInterface
     */
    public function getMailFacade(): MailFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_MAIL);
    }

    /**
     * @return \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    public function getCustomerFacade(): CustomerFacadeInterface
    {
        return $this->getProvidedDependency(SelfServicePortalDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Grouper\ProductClassGrouperInterface
     */
    public function createProductClassGrouper(): ProductClassGrouperInterface
    {
        return new ProductClassGrouper();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\OrderItemProductClassExpanderInterface
     */
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

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Asset\Expander\SspAssetItemExpanderInterface
     */
    public function createSspAssetItemExpander(): SspAssetItemExpanderInterface
    {
        return new SspAssetItemExpander(
            $this->createSspAssetReader(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Indexer\ProductClassIndexerInterface
     */
    public function createProductClassIndexer(): ProductClassIndexerInterface
    {
        return new ProductClassIndexer();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductClassExpanderInterface
     */
    public function createProductClassExpander(): ProductClassExpanderInterface
    {
        return new ProductClassExpander(
            $this->getRepository(),
            $this->createProductClassIndexer(),
            $this->createSkuExtractor(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductConcreteClassExpanderInterface
     */
    public function createProductConcreteClassExpander(): ProductConcreteClassExpanderInterface
    {
        return new ProductConcreteClassExpander(
            $this->createProductClassReader(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Expander\ProductAbstractClassExpanderInterface
     */
    public function createProductAbstractClassExpander(): ProductAbstractClassExpanderInterface
    {
        return new ProductAbstractClassExpander(
            $this->getRepository(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Utility\SkuExtractorInterface
     */
    public function createSkuExtractor(): SkuExtractorInterface
    {
        return new SkuExtractor();
    }

    /**
     * @return \SprykerFeature\Zed\SelfServicePortal\Business\Service\Reader\ProductClassReaderInterface
     */
    public function createProductClassReader(): ProductClassReaderInterface
    {
        return new ProductClassReader(
            $this->getRepository(),
            $this->createProductClassIndexer(),
        );
    }
}
