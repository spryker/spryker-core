<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApi\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface ProductApiQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryFind();

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return null|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryGet($idProductAbstract);

    /**
     * @api
     *
     * @param int $idProductAbstract
     *
     * @return null|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryRemove($idProductAbstract);
}
