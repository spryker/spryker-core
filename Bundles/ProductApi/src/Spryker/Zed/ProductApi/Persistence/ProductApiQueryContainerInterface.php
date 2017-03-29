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
    public function queryProductAbstract();

    /**
     * @api
     *
     * @param array $fields
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryFind(array $fields = []);

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param array $fields
     *
     * @return null|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryProductAbstractById($idProductAbstract, array $fields = []);

}
