<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Communication;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ManualOrderEntryGui\Communication\Form\DataProvider\FormDataProviderInterface;
use Spryker\Zed\SalesReclamation\Communication\Form\ReclamationDataProvider;
use Spryker\Zed\SalesReclamation\Communication\Form\ReclamationType;
use Spryker\Zed\SalesReclamation\Communication\Table\ReclamationTable;
use Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface;
use Spryker\Zed\SalesReclamation\Dependency\Service\SalesReclamationToUtilDateTimeServiceInterface;
use Spryker\Zed\SalesReclamation\SalesReclamationDependencyProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\SalesReclamation\SalesReclamationConfig getConfig()
 */
class SalesReclamationCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesReclamationToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesReclamationDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\SalesReclamation\Communication\Table\ReclamationTable
     */
    public function createReclamationTable(): ReclamationTable
    {
        return new ReclamationTable(
            $this->getQueryContainer(),
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
     * @return \Spryker\Zed\SalesReclamation\Dependency\Service\SalesReclamationToUtilDateTimeServiceInterface
     */
    public function getDateTimeService(): SalesReclamationToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SalesReclamationDependencyProvider::SERVICE_DATETIME);
    }
}
