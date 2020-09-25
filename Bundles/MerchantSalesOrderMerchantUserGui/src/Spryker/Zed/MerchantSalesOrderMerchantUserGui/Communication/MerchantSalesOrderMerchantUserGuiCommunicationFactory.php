<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication;

use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\DataProvider\EventItemTriggerFormDataProvider;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\DataProvider\EventTriggerFormDataProvider;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\DataProvider\MerchantShipmentGroupFormDataProvider;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\EventItemTriggerForm;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\EventTriggerForm;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\Shipment\MerchantShipmentGroupFormType;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Table\MyOrderTable;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToCustomerFacadeInterface;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToMerchantOmsFacadeInterface;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToMerchantSalesOrderFacadeInterface;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToMoneyFacadeInterface;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToShipmentFacadeInterface;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Service\MerchantSalesOrderMerchantUserGuiToShipmentServiceInterface;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Service\MerchantSalesOrderMerchantUserGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Service\MerchantSalesOrderMerchantUserGuiToUtilSanitizeInterface;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\MerchantSalesOrderMerchantUserGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Persistence\MerchantSalesOrderMerchantUserGuiQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\MerchantSalesOrderMerchantUserGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Business\MerchantSalesOrderMerchantUserGuiFacadeInterface getFacade()
 */
class MerchantSalesOrderMerchantUserGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Table\MyOrderTable
     */
    public function createMyOrderTable(): MyOrderTable
    {
        return new MyOrderTable(
            $this->getMerchantSalesOrderPropelQuery(),
            $this->getMoneyFacade(),
            $this->getUtilSanitizeService(),
            $this->getDateTimeService(),
            $this->getCustomerFacade(),
            $this->getMerchantUserFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\DataProvider\EventTriggerFormDataProvider
     */
    public function createEventTriggerFormDataProvider(): EventTriggerFormDataProvider
    {
        return new EventTriggerFormDataProvider();
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\DataProvider\EventItemTriggerFormDataProvider
     */
    public function createEventItemTriggerFormDataProvider(): EventItemTriggerFormDataProvider
    {
        return new EventItemTriggerFormDataProvider();
    }

    /**
     * @phpstan-param array<string, mixed> $options
     *
     * @phpstan-return \Symfony\Component\Form\FormInterface<mixed>
     *
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createEventTriggerForm(array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(EventTriggerForm::class, null, $options);
    }

    /**
     * @phpstan-param array<string, mixed> $options
     *
     * @phpstan-return \Symfony\Component\Form\FormInterface<mixed>
     *
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createEventItemTriggerForm(array $options = []): FormInterface
    {
        return $this->getFormFactory()->create(EventItemTriggerForm::class, null, $options);
    }

    /**
     * @phpstan-return array <string, string>
     *
     * @return array
     */
    public function getMerchantSalesOrderDetailExternalBlocksUrls(): array
    {
        return $this->getConfig()->getMerchantSalesOrderDetailExternalBlocksUrls();
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Communication\Form\DataProvider\MerchantShipmentGroupFormDataProvider
     */
    public function createMerchantShipmentGroupFormDataProvider(): MerchantShipmentGroupFormDataProvider
    {
        return new MerchantShipmentGroupFormDataProvider(
            $this->getCustomerFacade(),
            $this->getShipmentFacade()
        );
    }

    /**
     * @phpstan-param array<mixed> $formOptions
     *
     * @phpstan-return \Symfony\Component\Form\FormInterface<mixed>
     *
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     * @param array $formOptions
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createMerchantShipmentGroupForm(
        ShipmentGroupTransfer $shipmentGroupTransfer,
        array $formOptions = []
    ): FormInterface {
        return $this->getFormFactory()->create(MerchantShipmentGroupFormType::class, $shipmentGroupTransfer, $formOptions);
    }

    /**
     * @phpstan-return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery<mixed>
     *
     * @return \Orm\Zed\MerchantSalesOrder\Persistence\SpyMerchantSalesOrderQuery
     */
    public function getMerchantSalesOrderPropelQuery(): SpyMerchantSalesOrderQuery
    {
        return $this->getProvidedDependency(MerchantSalesOrderMerchantUserGuiDependencyProvider::PROPEL_QUERY_MERCHANT_SALES_ORDER);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): MerchantSalesOrderMerchantUserGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderMerchantUserGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Service\MerchantSalesOrderMerchantUserGuiToUtilSanitizeInterface
     */
    public function getUtilSanitizeService(): MerchantSalesOrderMerchantUserGuiToUtilSanitizeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderMerchantUserGuiDependencyProvider::SERVICE_UTIL_SANITIZE);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Service\MerchantSalesOrderMerchantUserGuiToUtilDateTimeServiceInterface
     */
    public function getDateTimeService(): MerchantSalesOrderMerchantUserGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderMerchantUserGuiDependencyProvider::SERVICE_DATE_TIME);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToCustomerFacadeInterface
     */
    public function getCustomerFacade(): MerchantSalesOrderMerchantUserGuiToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderMerchantUserGuiDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): MerchantSalesOrderMerchantUserGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderMerchantUserGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToMerchantSalesOrderFacadeInterface
     */
    public function getMerchantSalesOrderFacade(): MerchantSalesOrderMerchantUserGuiToMerchantSalesOrderFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderMerchantUserGuiDependencyProvider::FACADE_MERCHANT_SALES_ORDER);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Service\MerchantSalesOrderMerchantUserGuiToShipmentServiceInterface
     */
    public function getShipmentService(): MerchantSalesOrderMerchantUserGuiToShipmentServiceInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderMerchantUserGuiDependencyProvider::SERVICE_SHIPMENT);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToMerchantOmsFacadeInterface
     */
    public function getMerchantOmsFacade(): MerchantSalesOrderMerchantUserGuiToMerchantOmsFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderMerchantUserGuiDependencyProvider::FACADE_MERCHANT_OMS);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade\MerchantSalesOrderMerchantUserGuiToShipmentFacadeInterface
     */
    public function getShipmentFacade(): MerchantSalesOrderMerchantUserGuiToShipmentFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderMerchantUserGuiDependencyProvider::FACADE_SHIPMENT);
    }
}
