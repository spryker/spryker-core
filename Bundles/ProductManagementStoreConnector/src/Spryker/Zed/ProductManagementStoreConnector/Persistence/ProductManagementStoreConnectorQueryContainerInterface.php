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
     * Specification:
     * - Selects spy_product_abstract_store entities with the provided product abstract id.
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\ProductManagementStoreConnector\Persistence\SpyProductAbstractStoreQuery
     */
    public function queryProductAbstractStoreByFkProductAbstract($idProductAbstract);

    /**
     * Specification:
     * - Selects spy_product_abstract_store entities.
     * - Selected entities have to match the provided product abstract id.
     * - Selected entities have to match also any of the provided store ids.
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param int[] $idStores
     *
     * @return \Orm\Zed\ProductManagementStoreConnector\Persistence\SpyProductAbstractStoreQuery
     */
    public function queryProductAbstractStoresByFkProductAbstractAndFkStores($idProductAbstract, $idStores);
}
