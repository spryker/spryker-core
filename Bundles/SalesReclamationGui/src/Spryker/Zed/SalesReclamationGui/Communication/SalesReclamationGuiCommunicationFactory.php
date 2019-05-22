<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamationGui\Communication;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ReclamationTransfer;
use Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationQuery;
use Spryker\Zed\Gui\Communication\Form\OmsTriggerForm;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesReclamationGui\Communication\Form\DataProvider\OmsTriggerFormDataProvider;
use Spryker\Zed\SalesReclamationGui\Communication\ReclamationItem\ReclamationItemEventsFinder;
use Spryker\Zed\SalesReclamationGui\Communication\ReclamationItem\ReclamationItemEventsFinderInterface;
use Spryker\Zed\SalesReclamationGui\Communication\Table\ReclamationTable;
use Spryker\Zed\SalesReclamationGui\Dependency\Facade\SalesReclamationGuiToOmsFacadeInterface;
use Spryker\Zed\SalesReclamationGui\Dependency\Facade\SalesReclamationGuiToSalesFacadeInterface;
use Spryker\Zed\SalesReclamationGui\Dependency\Facade\SalesReclamationGuiToSalesReclamationFacadeInterface;
use Spryker\Zed\SalesReclamationGui\Dependency\Service\SalesReclamationGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\SalesReclamationGui\SalesReclamationGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

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
     * @return \Orm\Zed\SalesReclamation\Persistence\SpySalesReclamationQuery
     */
    public function getSalesReclamationPropelQuery(): SpySalesReclamationQuery
    {
        return $this->getProvidedDependency(SalesReclamationGuiDependencyProvider::PROPEL_QUERY_SALES_RECLAMATION);
    }

    /**
     * @return \Spryker\Zed\SalesReclamationGui\Communication\Table\ReclamationTable
     */
    public function createReclamationTable(): ReclamationTable
    {
        return new ReclamationTable(
            $this->getSalesReclamationPropelQuery(),
            $this->getDateTimeService()
        );
    }

    /**
     * @return \Spryker\Zed\SalesReclamationGui\Dependency\Service\SalesReclamationGuiToUtilDateTimeServiceInterface
     */
    public function getDateTimeService(): SalesReclamationGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(SalesReclamationGuiDependencyProvider::SERVICE_DATETIME);
    }

    /**
     * @return \Spryker\Zed\SalesReclamationGui\Dependency\Facade\SalesReclamationGuiToOmsFacadeInterface
     */
    public function getOmsFacade(): SalesReclamationGuiToOmsFacadeInterface
    {
        return $this->getProvidedDependency(SalesReclamationGuiDependencyProvider::FACADE_OMS);
    }

    /**
     * @return \Spryker\Zed\SalesReclamationGui\Communication\ReclamationItem\ReclamationItemEventsFinderInterface
     */
    public function createReclamationItemEventsFinder(): ReclamationItemEventsFinderInterface
    {
        return new ReclamationItemEventsFinder();
    }

    /**
     * @param \Generated\Shared\Transfer\ReclamationTransfer $reclamationTransfer
     * @param string $event
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getOrderOmsTriggerForm(ReclamationTransfer $reclamationTransfer, string $event): FormInterface
    {
        $options = $this->createOmsTriggerFormDataProvider()
            ->getOrderOmsTriggerFormOptions($reclamationTransfer, $event);

        return $this->getFormFactory()->create(OmsTriggerForm::class, null, $options);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $event
     * @param int $idReclamation
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getOrderItemOmsTriggerForm(ItemTransfer $itemTransfer, string $event, int $idReclamation): FormInterface
    {
        $options = $this->createOmsTriggerFormDataProvider()
            ->getOrderItemOmsTriggerFormOptions($itemTransfer, $event, $idReclamation);

        return $this->getFormFactory()->create(OmsTriggerForm::class, null, $options);
    }

    /**
     * @return \Spryker\Zed\SalesReclamationGui\Communication\Form\DataProvider\OmsTriggerFormDataProvider
     */
    public function createOmsTriggerFormDataProvider(): OmsTriggerFormDataProvider
    {
        return new OmsTriggerFormDataProvider();
    }
}
