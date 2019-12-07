<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockGui\Communication;

use Generated\Shared\Transfer\StockTransfer;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\Gui\Communication\Tabs\TabsInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\StockGui\Communication\Form\Constraint\StockNameUniqueConstraint;
use Spryker\Zed\StockGui\Communication\Form\StockForm;
use Spryker\Zed\StockGui\Communication\Table\StockTable;
use Spryker\Zed\StockGui\Communication\Tabs\StockTabs;
use Spryker\Zed\StockGui\Dependency\Facade\StockGuiToStockFacadeInterface;
use Spryker\Zed\StockGui\StockGuiDependencyProvider;
use Symfony\Component\Form\FormInterface;

class StockGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\StockGui\Communication\Table\StockTable
     */
    public function createStockTable(): StockTable
    {
        return new StockTable(
            $this->getStockPropelQuery(),
            $this->getStockFacade()
        );
    }

    /**
     * @return \Spryker\Zed\StockGui\Communication\Tabs\StockTabs
     */
    public function createStockTabs(): TabsInterface
    {
        return new StockTabs();
    }

    /**
     * @return \Spryker\Zed\StockGui\Communication\Form\Constraint\StockNameUniqueConstraint
     */
    public function createStockNameUniqueConstraint(): StockNameUniqueConstraint
    {
        return new StockNameUniqueConstraint([
            StockNameUniqueConstraint::OPTION_STOCK_FACADE => $this->getStockFacade(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer|null $stockTransfer
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getStockForm(?StockTransfer $stockTransfer = null): FormInterface
    {
        return $this->getFormFactory()->create(StockForm::class, $stockTransfer);
    }

    /**
     * @return \Orm\Zed\Stock\Persistence\SpyStockQuery
     */
    public function getStockPropelQuery(): SpyStockQuery
    {
        return $this->getProvidedDependency(StockGuiDependencyProvider::PROPEL_QUERY_STOCK);
    }

    /**
     * @return \Spryker\Zed\StockGui\Dependency\Facade\StockGuiToStockFacadeInterface
     */
    public function getStockFacade(): StockGuiToStockFacadeInterface
    {
        return $this->getProvidedDependency(StockGuiDependencyProvider::FACADE_STOCK);
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getStoreRelationFormTypePlugin(): FormTypeInterface
    {
        return $this->getProvidedDependency(StockGuiDependencyProvider::PLUGIN_STORE_RELATION_FORM_TYPE);
    }
}
