<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Persistence;

use Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\ProductValidity\Persistence\ProductValidityQueryContainerInterface getQueryContainer()
 */
class ProductValidityPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery
     */
    public function createProductValidityQuery(): SpyProductValidityQuery
    {
        return SpyProductValidityQuery::create();
    }
}
