<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\StoreGui\Communication\Table\StoreTable;
use Spryker\Zed\StoreGui\StoreGuiDependencyProvider;

class StoreGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\StoreGui\Communication\Table\StoreTable
     */
    public function createStoreTable(): StoreTable
    {
        return new StoreTable($this->getStorePropelQuery());
    }

    /**
     * @return \Orm\Zed\Store\Persistence\SpyStoreQuery
     */
    public function getStorePropelQuery(): SpyStoreQuery
    {
        return $this->getProvidedDependency(StoreGuiDependencyProvider::PROPEL_QUERY_STORE);
    }
}
