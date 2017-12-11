<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagementStoreConnector\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductManagementStoreConnectorQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductManagementStoreConnector\Persistence\SpyProductAbstractStoreQuery
     */
    public function queryProductAbstractStoreByFkProductAbstract($idProductAbstract);

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param int[] $idStores
     *
     * @return \Orm\Zed\ProductManagementStoreConnector\Persistence\SpyProductAbstractStoreQuery
     */
    public function queryProductAbstractStoresByFkProductAbstractAndFkStores($idProductAbstract, $idStores);
}
