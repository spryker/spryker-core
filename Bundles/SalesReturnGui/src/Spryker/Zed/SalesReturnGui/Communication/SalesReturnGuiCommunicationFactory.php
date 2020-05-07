<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturnGui\Communication;

use Generated\Shared\Transfer\OrderTransfer;
use Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesReturnGui\Communication\Form\DataProvider\ReturnCreateFormDataProvider;
use Spryker\Zed\SalesReturnGui\Communication\Form\Handler\ReturnHandler;
use Spryker\Zed\SalesReturnGui\Communication\Form\Handler\ReturnHandlerInterface;
use Spryker\Zed\SalesReturnGui\Communication\Form\ReturnCreateForm;
use Spryker\Zed\SalesReturnGui\Communication\Table\OrderReturnTable;
use Spryker\Zed\SalesReturnGui\Communication\Table\ReturnTable;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToGlossaryFacadeInterface;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToMoneyFacadeInterface;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesFacadeInterface;
use Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface;
use Spryker\Zed\SalesReturnGui\Dependency\Service\SalesReturnGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\SalesReturnGui\SalesReturnGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

class SalesReturnGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getCreateReturnForm(OrderTransfer $orderTransfer): FormInterface
    {
        $returnCreateFormDataProvider = $this->createReturnCreateFormDataProvider();

        return $this->getFormFactory()->create(
            ReturnCreateForm::class,
            $returnCreateFormDataProvider->getData($orderTransfer),
            $returnCreateFormDataProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Communication\Form\DataProvider\ReturnCreateFormDataProvider
     */
    public function createReturnCreateFormDataProvider(): ReturnCreateFormDataProvider
    {
        return new ReturnCreateFormDataProvider(
            $this->getSalesReturnFacade(),
            $this->getGlossaryFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Communication\Table\ReturnTable
     */
    public function createReturnTable(): ReturnTable
    {
        return new ReturnTable(
            $this->getUtilDateTimeService(),
            $this->getSalesReturnPropelQuery()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Spryker\Zed\SalesReturnGui\Communication\Table\OrderReturnTable
     */
    public function createOrderReturnTable(OrderTransfer $orderTransfer): OrderReturnTable
    {
        return new OrderReturnTable(
            $orderTransfer,
            $this->getMoneyFacade(),
            $this->getSalesReturnPropelQuery()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Communication\Form\Handler\ReturnHandlerInterface
     */
    public function createReturnHandler(): ReturnHandlerInterface
    {
        return new ReturnHandler(
            $this->getSalesReturnFacade()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToMoneyFacadeInterface
     */
    public function getMoneyFacade(): SalesReturnGuiToMoneyFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnGuiDependencyProvider::FACADE_MONEY);
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesReturnGuiToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnGuiDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToGlossaryFacadeInterface
     */
    public function getGlossaryFacade(): SalesReturnGuiToGlossaryFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnGuiDependencyProvider::FACADE_GLOSSARY);
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Dependency\Facade\SalesReturnGuiToSalesReturnFacadeInterface
     */
    public function getSalesReturnFacade(): SalesReturnGuiToSalesReturnFacadeInterface
    {
        return $this->getProvidedDependency(SalesReturnGuiDependencyProvider::FACADE_SALES_RETURN);
    }

    /**
     * @return \Spryker\Zed\SalesReturnGui\Dependency\Service\SalesReturnGuiToUtilDateTimeServiceInterface
     */
    public function getUtilDateTimeService(): SalesReturnGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SalesReturnGuiDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    /**
     * @return \Orm\Zed\SalesReturn\Persistence\SpySalesReturnQuery
     */
    public function getSalesReturnPropelQuery(): SpySalesReturnQuery
    {
        return $this->getProvidedDependency(SalesReturnGuiDependencyProvider::PROPEL_QUERY_SALES_RETURN);
    }
}
