<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\DataProvider\EventItemTriggerFormDataProvider;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\DataProvider\EventTriggerFormDataProvider;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\DataProvider\ReturnCreateFormDataProvider;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\EventItemTriggerForm;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\EventTriggerForm;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\Handler\ReturnHandler;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\Handler\ReturnHandlerInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\ReturnCreateForm;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Reader\CustomerReader;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Reader\CustomerReaderInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Reader\MerchantOrderReader;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Reader\MerchantOrderReaderInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Table\MerchantReturnTable;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToCustomerFacadeInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToGlossaryFacadeInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantOmsFacadeInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantSalesOrderFacadeInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantUserFacadeInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToSalesFacadeInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Service\MerchantSalesReturnMerchantUserGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\MerchantSalesReturnMerchantUserGui\MerchantSalesReturnMerchantUserGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\MerchantSalesReturnMerchantUserGui\MerchantSalesReturnMerchantUserGuiConfig getConfig()
 */
class MerchantSalesReturnMerchantUserGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Table\MerchantReturnTable
     */
    public function createMerchantReturnTable(): MerchantReturnTable
    {
        return new MerchantReturnTable(
            $this->getDateTimeService(),
            $this->getConfig(),
            $this->getSalesReturnPropelQuery(),
            $this->getMerchantUserFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Reader\CustomerReaderInterface
     */
    public function createCustomerReader(): CustomerReaderInterface
    {
        return new CustomerReader(
            $this->getSalesFacade(),
            $this->getCustomerFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Reader\MerchantOrderReaderInterface
     */
    public function createMerchantOrderReader(): MerchantOrderReaderInterface
    {
        return new MerchantOrderReader(
            $this->getMerchantSalesOrderFacade(),
            $this->getMerchantOmsFacade()
        );
    }

    public function createReturnHandler(): ReturnHandlerInterface
    {
        return new ReturnHandler(
            $this->getSalesReturnFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\DataProvider\EventTriggerFormDataProvider
     */
    public function createEventTriggerFormDataProvider(): EventTriggerFormDataProvider
    {
        return new EventTriggerFormDataProvider();
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\DataProvider\EventItemTriggerFormDataProvider
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
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createReturnCreateForm(OrderTransfer $orderTransfer): FormInterface
    {
        $returnCreateFormDataProvider = $this->createReturnCreateFormDataProvider();

        return $this->getFormFactory()->create(
            ReturnCreateForm::class,
            $returnCreateFormDataProvider->getData($orderTransfer),
            $returnCreateFormDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Communication\Form\DataProvider\ReturnCreateFormDataProvider
     */
    public function createReturnCreateFormDataProvider(): ReturnCreateFormDataProvider
    {
        return new ReturnCreateFormDataProvider(
            $this->getSalesReturnFacade(),
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Service\MerchantSalesReturnMerchantUserGuiToUtilDateTimeServiceInterface
     */
    public function getDateTimeService(): MerchantSalesReturnMerchantUserGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(MerchantSalesReturnMerchantUserGuiDependencyProvider::SERVICE_DATE_TIME);
    }

    /**
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery
     */
    public function getSalesReturnPropelQuery(): SpySalesReturnQuery
    {
        return $this->getProvidedDependency(MerchantSalesReturnMerchantUserGuiDependencyProvider::PROPEL_QUERY_SALES_RETURN);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): MerchantSalesReturnMerchantUserGuiToMerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesReturnMerchantUserGuiDependencyProvider::FACADE_MERCHANT_USER);
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface
     */
    public function getSalesReturnFacade(): MerchantSalesReturnMerchantUserGuiToSalesReturnFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesReturnMerchantUserGuiDependencyProvider::FACADE_SALES_RETURN);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantSalesOrderFacadeInterface
     */
    public function getMerchantSalesOrderFacade(): MerchantSalesReturnMerchantUserGuiToMerchantSalesOrderFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesReturnMerchantUserGuiDependencyProvider::FACADE_MERCHANT_SALES_ORDER);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToSalesFacadeInterface
     */
    public function getSalesFacade(): MerchantSalesReturnMerchantUserGuiToSalesFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesReturnMerchantUserGuiDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToCustomerFacadeInterface
     */
    public function getCustomerFacade(): MerchantSalesReturnMerchantUserGuiToCustomerFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesReturnMerchantUserGuiDependencyProvider::FACADE_CUSTOMER);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToMerchantOmsFacadeInterface
     */
    public function getMerchantOmsFacade(): MerchantSalesReturnMerchantUserGuiToMerchantOmsFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesReturnMerchantUserGuiDependencyProvider::FACADE_MERCHANT_OMS);
    }

    /**
     * @return \Spryker\Zed\MerchantSalesReturnMerchantUserGui\Dependency\Facade\MerchantSalesReturnMerchantUserGuiToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): MerchantSalesReturnMerchantUserGuiToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesReturnMerchantUserGuiDependencyProvider::FACADE_GLOSSARY);
    }
}
