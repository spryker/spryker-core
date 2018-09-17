<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamationGui\Communication;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\FormDataProviderInterface;
use Spryker\Zed\SalesReclamationGui\Communication\Form\ReclamationDataProvider;
use Spryker\Zed\SalesReclamationGui\Communication\Form\ReclamationType;
use Spryker\Zed\SalesReclamationGui\Communication\Table\ReclamationTable;
use Spryker\Zed\SalesReclamationGui\Dependency\Facade\SalesReclamationGuiToSalesFacadeInterface;
use Spryker\Zed\SalesReclamationGui\Dependency\Facade\SalesReclamationGuiToSalesReclamationFacadeInterface;
use Spryker\Zed\SalesReclamationGui\Dependency\QueryContainer\SalesReclamationGuiToSalesReclamationQueryContainerInterface;
use Spryker\Zed\SalesReclamationGui\Dependency\Service\SalesReclamationGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\SalesReclamationGui\SalesReclamationGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesReclamationGui\SalesReclamationGuiConfig getConfig()
 */
class SalesReclamationGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SalesReclamationGui\Dependency\Facade\SalesReclamationGuiToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesReclamationGuiToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesReclamationGuiDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\SalesReclamationGui\Dependency\Facade\SalesReclamationGuiToSalesReclamationFacadeInterface
     */
    public function getSalesReclamationFacade(): SalesReclamationGuiToSalesReclamationFacadeInterface
    {
        return $this->getProvidedDependency(SalesReclamationGuiDependencyProvider::FACADE_SALES_RECLAMATION);
    }

    /**
     * @return \Spryker\Zed\SalesReclamationGui\Dependency\QueryContainer\SalesReclamationGuiToSalesReclamationQueryContainerInterface
     */
    public function getSalesReclamationQueryContainer(): SalesReclamationGuiToSalesReclamationQueryContainerInterface
    {
        return $this->getProvidedDependency(SalesReclamationGuiDependencyProvider::QUERY_CONTAINER_SALES_RECLAMATION);
    }

    /**
     * @return \Spryker\Zed\SalesReclamationGui\Communication\Table\ReclamationTable
     */
    public function createReclamationTable(): ReclamationTable
    {
        return new ReclamationTable(
            $this->getSalesReclamationQueryContainer(),
            $this->getDateTimeService()
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createReclamationForm(Request $request, QuoteTransfer $quoteTransfer): FormInterface
    {
        $dataProvider = $this->createReclamationDataProvider($request);

        return $this->getFormFactory()->create(
            ReclamationType::class,
            $dataProvider->getData($quoteTransfer),
            $dataProvider->getOptions($quoteTransfer)
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\FormDataProviderInterface
     */
    public function createReclamationDataProvider(Request $request): FormDataProviderInterface
    {
        return new ReclamationDataProvider(
            $request
        );
    }

    /**
     * @return \Spryker\Zed\SalesReclamationGui\Dependency\Service\SalesReclamationGuiToUtilDateTimeServiceInterface
     */
    public function getDateTimeService(): SalesReclamationGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SalesReclamationGuiDependencyProvider::SERVICE_DATETIME);
    }
}
