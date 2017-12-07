<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementStoreConnector\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ProductManagementStoreConnector\Persistence\ProductManagementStoreConnectorPersistenceFactory getFactory()
 */
class ProductManagementStoreConnectorQueryContainer extends AbstractQueryContainer implements ProductManagementStoreConnectorQueryContainerInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductManagementStoreConnector\Persistence\SpyProductAbstractStoreQuery
     */
    public function queryProductAbstractStoreByFkProductAbstract($idProductAbstract)
    {
        return $this->getFactory()
            ->createProductAbstractStoreQuery()
            ->filterByFkProductAbstract($idProductAbstract);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param int[] $idStores
     *
     * @return \Orm\Zed\ProductManagementStoreConnector\Persistence\SpyProductAbstractStoreQuery
     */
    public function queryProductAbstractStoresByFkProductAbstractAndFkStores($idProductAbstract, $idStores)
    {
        return $this->getFactory()->createProductAbstractStoreQuery()
            ->filterByFkStore_In($idStores)
            ->filterByFkProductAbstract($idProductAbstract);
    }
}
