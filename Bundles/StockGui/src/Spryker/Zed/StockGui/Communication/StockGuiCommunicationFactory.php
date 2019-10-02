<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockGui\Communication;

use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\StockGui\Communication\Table\StockTable;
use Spryker\Zed\StockGui\Dependency\Facade\StockGuiToStockFacadeInterface;
use Spryker\Zed\StockGui\StockGuiDependencyProvider;

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
}
