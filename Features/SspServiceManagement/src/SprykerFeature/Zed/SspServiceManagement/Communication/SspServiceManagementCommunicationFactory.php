<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Communication;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Locale\Business\LocaleFacade;
use Spryker\Zed\Merchant\Business\MerchantFacadeInterface;
use Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface;
use Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Expander\OrderItemProductTypeExpander;
use SprykerFeature\Zed\SspServiceManagement\Business\Expander\OrderItemProductTypeExpanderInterface;
use SprykerFeature\Zed\SspServiceManagement\Business\Saver\ServiceDateTimeEnabledSaver;
use SprykerFeature\Zed\SspServiceManagement\Business\Saver\ServiceDateTimeEnabledSaverInterface;
use SprykerFeature\Zed\SspServiceManagement\Communication\Expander\ProductAbstractTypeExpander;
use SprykerFeature\Zed\SspServiceManagement\Communication\Expander\ProductAbstractTypeExpanderInterface;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\CreateOfferForm;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataProvider\CreateOfferFormDataProvider;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataProvider\EditOfferFormDataProvider;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataProvider\ItemSchedulerFormDataProvider;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataProvider\ServiceDateTimeEnabledProductConcreteFormDataProvider;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataProvider\ShipmentTypeProductConcreteFormDataProvider;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataTransformer\ServicePointServicesDataTransformer;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataTransformer\ShipmentTypesDataTransformer;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataTransformer\StoresDataTransformer;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataTransformer\ValidFromDataTransformer;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataTransformer\ValidToDataTransformer;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\EditOfferForm;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\EventListener\MerchantCreateOfferFormEventSubscriber;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\EventListener\ServicePointEditOfferFormEventSubscriber;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\EventListener\StockCreateOfferFormEventSubscriber;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\EventListener\StockEditOfferFormEventSubscriber;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\ItemSchedulerForm;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\ProductAbstractTypeForm;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\ServiceDateTimeEnabledProductConcreteForm;
use SprykerFeature\Zed\SspServiceManagement\Communication\Form\ShipmentTypeProductConcreteForm;
use SprykerFeature\Zed\SspServiceManagement\Communication\Mapper\ServiceDateTimeProductFormMapper;
use SprykerFeature\Zed\SspServiceManagement\Communication\Mapper\ServiceDateTimeProductFormMapperInterface;
use SprykerFeature\Zed\SspServiceManagement\Communication\Mapper\ShipmentTypeProductFormMapper;
use SprykerFeature\Zed\SspServiceManagement\Communication\Mapper\ShipmentTypeProductFormMapperInterface;
use SprykerFeature\Zed\SspServiceManagement\Communication\Reader\ProductReader;
use SprykerFeature\Zed\SspServiceManagement\Communication\Reader\ProductReaderInterface;
use SprykerFeature\Zed\SspServiceManagement\Communication\Reader\SalesOrderItemReader;
use SprykerFeature\Zed\SspServiceManagement\Communication\Reader\SalesOrderItemReaderInterface;
use SprykerFeature\Zed\SspServiceManagement\Communication\Reader\ShipmentTypeReader;
use SprykerFeature\Zed\SspServiceManagement\Communication\Reader\ShipmentTypeReaderInterface;
use SprykerFeature\Zed\SspServiceManagement\Communication\Saver\SalesOrderItemProductTypesSaver;
use SprykerFeature\Zed\SspServiceManagement\Communication\Saver\SalesOrderItemProductTypesSaverInterface;
use SprykerFeature\Zed\SspServiceManagement\Communication\Table\ProductConcreteTable;
use SprykerFeature\Zed\SspServiceManagement\Communication\Table\ServiceTable;
use SprykerFeature\Zed\SspServiceManagement\SspServiceManagementDependencyProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementEntityManagerInterface getEntityManager()
 * @method \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface getRepository()
 */
class SspServiceManagementCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataProvider\ShipmentTypeProductConcreteFormDataProvider
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
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataProvider\ServiceDateTimeEnabledProductConcreteFormDataProvider
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
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataProvider\CreateOfferFormDataProvider
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
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataProvider\EditOfferFormDataProvider
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
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Reader\ProductReaderInterface
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
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Mapper\ShipmentTypeProductFormMapperInterface
     */
    public function createShipmentTypeProductFormMapper(): ShipmentTypeProductFormMapperInterface
    {
        return new ShipmentTypeProductFormMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Mapper\ServiceDateTimeProductFormMapperInterface
     */
    public function createServiceDateTimeProductFormMapper(): ServiceDateTimeProductFormMapperInterface
    {
        return new ServiceDateTimeProductFormMapper();
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Reader\ShipmentTypeReaderInterface
     */
    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader($this->getShipmentTypeFacade(), $this->getConfig());
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Table\ProductConcreteTable
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
     * @return array<string, \SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataTransformer\DataTransformerInterface<mixed, mixed>>
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
     * @return array<string, \SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataTransformer\DataTransformerInterface<mixed, mixed>>
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
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataTransformer\ValidFromDataTransformer
     */
    public function createValidFromDataTransformer(): ValidFromDataTransformer
    {
        return new ValidFromDataTransformer();
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataTransformer\ValidToDataTransformer
     */
    public function createValidToDataTransformer(): ValidToDataTransformer
    {
        return new ValidToDataTransformer();
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataTransformer\StoresDataTransformer
     */
    public function createStoresDataTransformer(): StoresDataTransformer
    {
        return new StoresDataTransformer();
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataTransformer\ShipmentTypesDataTransformer
     */
    public function createShipmentTypesDataTransformer(): ShipmentTypesDataTransformer
    {
        return new ShipmentTypesDataTransformer();
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataTransformer\ServicePointServicesDataTransformer
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
    public function getCreateProductOfferFormEventSubscribers(): array
    {
        return [
            $this->createStockCreateOfferFormEventSubscriber(),
            $this->createMerchantCreateOfferFormEventSubscriber(),
        ];
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
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Table\ServiceTable
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
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Form\DataProvider\ItemSchedulerFormDataProvider
     */
    public function createItemSchedulerFormDataProvider(): ItemSchedulerFormDataProvider
    {
        return new ItemSchedulerFormDataProvider();
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Reader\SalesOrderItemReaderInterface
     */
    public function createSalesOrderItemReader(): SalesOrderItemReaderInterface
    {
        return new SalesOrderItemReader($this->getSalesFacade());
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Expander\ProductAbstractTypeExpanderInterface
     */
    public function createProductAbstractTypeExpander(): ProductAbstractTypeExpanderInterface
    {
        return new ProductAbstractTypeExpander(
            $this->getRepository(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspServiceManagement\Communication\Saver\SalesOrderItemProductTypesSaverInterface
     */
    public function createSalesOrderItemProductTypesSaver(): SalesOrderItemProductTypesSaverInterface
    {
        return new SalesOrderItemProductTypesSaver(
            $this->getEntityManager(),
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
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    public function getProductQuery(): SpyProductQuery
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::PROPEL_QUERY_PRODUCT);
    }

    /**
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImageQuery
     */
    public function getProductImageQuery(): SpyProductImageQuery
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::PROPEL_QUERY_PRODUCT_IMAGE);
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacade
     */
    public function getLocaleFacade(): LocaleFacade
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_LOCALE);
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    public function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ServicePoint\Business\ServicePointFacadeInterface
     */
    public function getServicePointFacade(): ServicePointFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_SERVICE_POINT);
    }

    /**
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery
     */
    public function getSalesOrderItemPropelQuery(): SpySalesOrderItemQuery
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::PROPEL_QUERY_SALES_ORDER_ITEM);
    }

    /**
     * @return \Spryker\Zed\ShipmentType\Business\ShipmentTypeFacadeInterface
     */
    public function getShipmentTypeFacade(): ShipmentTypeFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_SHIPMENT_TYPE);
    }

    /**
     * @return \Spryker\Zed\ProductOffer\Business\ProductOfferFacadeInterface
     */
    public function getProductOfferFacade(): ProductOfferFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_PRODUCT_OFFER);
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    public function getProductFacade(): ProductFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\Merchant\Business\MerchantFacadeInterface
     */
    public function getMerchantFacade(): MerchantFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_MERCHANT);
    }

    /**
     * @return \Spryker\Zed\Oms\Business\OmsFacadeInterface
     */
    public function getOmsFacade(): OmsFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\MerchantStock\Business\MerchantStockFacadeInterface
     */
    public function getMerchantStockFacade(): MerchantStockFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_MERCHANT_STOCK);
    }

    /**
     * @return \Spryker\Zed\Sales\Business\SalesFacadeInterface
     */
    public function getSalesFacade(): SalesFacadeInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Service\UtilDateTime\UtilDateTimeServiceInterface
     */
    public function getDateTimeService(): UtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SspServiceManagementDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }
}
