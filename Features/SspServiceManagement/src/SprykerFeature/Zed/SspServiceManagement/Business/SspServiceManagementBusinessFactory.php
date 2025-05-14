<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Business;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery;
use Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractToProductAbstractTypeQuery;
use Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractTypeQuery;
use Orm\Zed\SspServiceManagement\Persistence\SpyProductShipmentTypeQuery;
use Spryker\Zed\DataImport\Business\DataImportFactoryTrait;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Messenger\Business\MessengerFacadeInterface;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface;
use Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Canceler\OrderItemCanceler;
use SprykerFeature\Zed\SspServiceManagement\Business\Canceler\OrderItemCancelerInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\DataImport\Step\ProductAbstractSkuToIdProductAbstractStep;
use SprykerFeature\Zed\SspServiceManagement\Business\DataImport\Step\ProductAbstractToProductAbstractTypeWriterStep;
use SprykerFeature\Zed\SspServiceManagement\Business\DataImport\Step\ProductAbstractTypeKeyToIdProductAbstractTypeStep;
use SprykerFeature\Zed\SspServiceManagement\Business\DataImport\Step\ProductAbstractTypeWriterStep;
use SprykerFeature\Zed\SspServiceManagement\Business\DataImport\Step\ProductShipmentTypeWriterStep;
use SprykerFeature\Zed\SspServiceManagement\Business\DataImport\Step\ProductSkuToIdProductStep;
use SprykerFeature\Zed\SspServiceManagement\Business\DataImport\Step\ShipmentTypeKeyToIdShipmentTypeStep;
use SprykerFeature\Zed\SspServiceManagement\Business\Expander\OrderItemProductTypeExpander;
use SprykerFeature\Zed\SspServiceManagement\Business\Expander\OrderItemProductTypeExpanderInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Expander\OrderItemScheduleExpander;
use SprykerFeature\Zed\SspServiceManagement\Business\Expander\OrderItemScheduleExpanderInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Expander\ProductAbstractTypeExpander;
use SprykerFeature\Zed\SspServiceManagement\Business\Expander\ProductAbstractTypeExpanderInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Expander\ProductConcreteShipmentTypeExpander;
use SprykerFeature\Zed\SspServiceManagement\Business\Expander\ProductConcreteShipmentTypeExpanderInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Expander\ServicePointItemExpander;
use SprykerFeature\Zed\SspServiceManagement\Business\Expander\ServicePointItemExpanderInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Expander\ShipmentTypeItemExpander;
use SprykerFeature\Zed\SspServiceManagement\Business\Expander\ShipmentTypeItemExpanderInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Filter\QuoteItemFilter;
use SprykerFeature\Zed\SspServiceManagement\Business\Filter\QuoteItemFilterInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Grouper\ShipmentTypeGrouper;
use SprykerFeature\Zed\SspServiceManagement\Business\Grouper\ShipmentTypeGrouperInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Reader\ProductShipmentTypeReader;
use SprykerFeature\Zed\SspServiceManagement\Business\Reader\ProductShipmentTypeReaderInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Reader\ServicePointReader;
use SprykerFeature\Zed\SspServiceManagement\Business\Reader\ServicePointReaderInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Reader\ServiceReader;
use SprykerFeature\Zed\SspServiceManagement\Business\Reader\ServiceReaderInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Reader\ShipmentTypeReader;
use SprykerFeature\Zed\SspServiceManagement\Business\Reader\ShipmentTypeReaderInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Resolver\PaymentMethodResolver;
use SprykerFeature\Zed\SspServiceManagement\Business\Resolver\PaymentMethodResolverInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Saver\ProductAbstractTypeSaver;
use SprykerFeature\Zed\SspServiceManagement\Business\Saver\ProductAbstractTypeSaverInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Saver\ProductShipmentTypeSaver;
use SprykerFeature\Zed\SspServiceManagement\Business\Saver\ProductShipmentTypeSaverInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Saver\ServiceDateTimeEnabledSaver;
use SprykerFeature\Zed\SspServiceManagement\Business\Saver\ServiceDateTimeEnabledSaverInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Storage\Expander\ShipmentTypeProductConcreteStorageExpander;
use SprykerFeature\Zed\SspServiceManagement\Business\Storage\Expander\ShipmentTypeProductConcreteStorageExpanderInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Updater\OrderItemScheduleUpdater;
use SprykerFeature\Zed\SspServiceManagement\Business\Updater\OrderItemScheduleUpdaterInterface;
use SprykerFeature\Zed\SspServiceManagement\SspServiceManagementDependencyProvider;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementEntityManagerInterface getEntityManager()
 * @method \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface getRepository()
 */
class SspServiceManagementBusinessFactory extends AbstractBusinessFactory
{
    use DataImportFactoryTrait;

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Saver\ProductShipmentTypeSaverInterface
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
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Saver\ServiceDateTimeEnabledSaverInterface
     */
    public function createServiceDateTimeEnabledSaver(): ServiceDateTimeEnabledSaverInterface
    {
        return new ServiceDateTimeEnabledSaver(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Expander\OrderItemProductTypeExpanderInterface
     */
    public function createOrderItemProductTypeExpander(): OrderItemProductTypeExpanderInterface
    {
        return new OrderItemProductTypeExpander(
            $this->getRepository(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Expander\ProductConcreteShipmentTypeExpanderInterface
     */
    public function createProductConcreteShipmentTypeExpander(): ProductConcreteShipmentTypeExpanderInterface
    {
        return new ProductConcreteShipmentTypeExpander($this->createProductShipmentTypeReader());
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Expander\OrderItemScheduleExpanderInterface
     */
    public function createOrderItemScheduleExpander(): OrderItemScheduleExpanderInterface
    {
        return new OrderItemScheduleExpander();
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Reader\ProductShipmentTypeReaderInterface
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
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Grouper\ShipmentTypeGrouperInterface
     */
    public function createShipmentTypeGrouper(): ShipmentTypeGrouperInterface
    {
        return new ShipmentTypeGrouper();
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Storage\Expander\ShipmentTypeProductConcreteStorageExpanderInterface
     */
    public function createShipmentTypeProductConcreteStorageExpander(): ShipmentTypeProductConcreteStorageExpanderInterface
    {
        return new ShipmentTypeProductConcreteStorageExpander(
            $this->createProductShipmentTypeReader(),
            $this->getRepository(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Resolver\PaymentMethodResolverInterface
     */
    public function createPaymentMethodResolver(): PaymentMethodResolverInterface
    {
        return new PaymentMethodResolver(
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Updater\OrderItemScheduleUpdaterInterface
     */
    public function createOrderItemScheduleUpdater(): OrderItemScheduleUpdaterInterface
    {
        return new OrderItemScheduleUpdater(
            $this->getSalesFacade(),
            $this->createPaymentMethodResolver(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Saver\ProductAbstractTypeSaverInterface
     */
    public function createProductAbstractTypeSaver(): ProductAbstractTypeSaverInterface
    {
        return new ProductAbstractTypeSaver(
            $this->getEntityManager(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Expander\ProductAbstractTypeExpanderInterface
     */
    public function createProductAbstractTypeExpander(): ProductAbstractTypeExpanderInterface
    {
        return new ProductAbstractTypeExpander(
            $this->getRepository(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Reader\ServiceReaderInterface
     */
    public function createServiceReader(): ServiceReaderInterface
    {
        return new ServiceReader(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Event\Business\EventFacadeInterface
     */
    public function getEventFacade(): EventFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface
     */
    public function getShipmentTypeFacade(): ShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_SHIPMENT_TYPE);
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
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Expander\ShipmentTypeItemExpanderInterface
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
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Expander\ServicePointItemExpanderInterface
     */
    public function createServicePointItemExpander(): ServicePointItemExpanderInterface
    {
        return new ServicePointItemExpander(
            $this->createServicePointReader(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Reader\ShipmentTypeReaderInterface
     */
    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader(
            $this->getShipmentTypeFacade(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Reader\ServicePointReaderInterface
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
    public function getProductAbstractTypeDataImporter(): DataImporterInterface
    {
        $config = $this->getConfig()->getProductAbstractTypeDataImporterConfiguration();

        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig($config);

        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBroker $dataSetStepBroker */
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createProductAbstractTypeWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function getProductAbstractToProductAbstractTypeDataImporter(): DataImporterInterface
    {
        $config = $this->getConfig()->getProductAbstractToProductAbstractTypeDataImporterConfiguration();

        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig($config);

        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBroker $dataSetStepBroker */
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep($this->createProductAbstractSkuToIdProductAbstractStep());
        $dataSetStepBroker->addStep($this->createProductAbstractTypeKeyToIdProductAbstractTypeStep());
        $dataSetStepBroker->addStep($this->createProductAbstractToProductAbstractTypeWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductAbstractTypeWriterStep(): DataImportStepInterface
    {
        return new ProductAbstractTypeWriterStep(
            $this->getProductAbstractTypeQuery(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductAbstractSkuToIdProductAbstractStep(): DataImportStepInterface
    {
        return new ProductAbstractSkuToIdProductAbstractStep(
            $this->getProductAbstractQuery(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductAbstractTypeKeyToIdProductAbstractTypeStep(): DataImportStepInterface
    {
        return new ProductAbstractTypeKeyToIdProductAbstractTypeStep(
            $this->getProductAbstractTypeQuery(),
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductAbstractToProductAbstractTypeWriterStep(): DataImportStepInterface
    {
        return new ProductAbstractToProductAbstractTypeWriterStep(
            $this->getProductAbstractToProductAbstractTypeQuery(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Canceler\OrderItemCancelerInterface
     */
    public function createOrderItemCanceler(): OrderItemCancelerInterface
    {
        return new OrderItemCanceler(
            $this->getOmsFacade(),
        );
    }

    /**
     * @return \Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractTypeQuery
     */
    public function getProductAbstractTypeQuery(): SpyProductAbstractTypeQuery
    {
        return SpyProductAbstractTypeQuery::create();
    }

    /**
     * @return \Orm\Zed\SspServiceManagement\Persistence\SpyProductAbstractToProductAbstractTypeQuery
     */
    public function getProductAbstractToProductAbstractTypeQuery(): SpyProductAbstractToProductAbstractTypeQuery
    {
        return SpyProductAbstractToProductAbstractTypeQuery::create();
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Business\Filter\QuoteItemFilterInterface
     */
    public function createQuoteItemFilter(): QuoteItemFilterInterface
    {
        return new QuoteItemFilter(
            $this->getConfig(),
            $this->getMessengerFacade(),
        );
    }

     /**
      * @return \Orm\Zed\Product\Persistence\SpyProductQuery
      */
    public function getProductQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::PROPEL_QUERY_PRODUCT);
    }

    /**
     * @return \Orm\Zed\ShipmentType\Persistence\SpyShipmentTypeQuery
     */
    public function getShipmentTypeQuery(): SpyShipmentTypeQuery
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::PROPEL_QUERY_SHIPMENT_TYPE);
    }

    /**
     * @return \Orm\Zed\SspServiceManagement\Persistence\SpyProductShipmentTypeQuery
     */
    public function getProductShipmentTypeQuery(): SpyProductShipmentTypeQuery
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::PROPEL_QUERY_PRODUCT_SHIPMENT_TYPE);
    }

    /**
     * @return \Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface
     */
    public function getProductOfferShipmentTypeFacade(): ProductOfferShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_PRODUCT_OFFER_SHIPMENT_TYPE);
    }

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function getProductAbstractQuery(): SpyProductAbstractQuery
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::PROPEL_QUERY_PRODUCT_ABSTRACT);
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    public function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface
     */
    public function getServicePointFacade(): ServicePointFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_SERVICE_POINT);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    public function getOmsFacade(): OmsFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\Messenger\Business\MessengerFacadeInterface
     */
    public function getMessengerFacade(): MessengerFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_MESSENGER);
    }
}
