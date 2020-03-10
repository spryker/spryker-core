<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearchConfigStorage\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductSearchConfigStorage\Persistence\ProductSearchConfigStoragePersistenceFactory getFactory()
 */
class ProductSearchConfigStorageQueryContainer extends AbstractQueryContainer implements ProductSearchConfigStorageQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductSearchConfigStorage\Persistence\SpyProductSearchConfigStorageQuery
     */
    public function queryProductSearchConfigStorage()
    {
        return $this->getFactory()
            ->createSpyProductSearchConfigStorageQuery();
    }
}
