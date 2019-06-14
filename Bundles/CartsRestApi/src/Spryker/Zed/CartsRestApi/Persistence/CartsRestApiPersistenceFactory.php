<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartsRestApi\Persistence;

use Orm\Zed\Quote\Persistence\SpyQuoteQuery;
use Spryker\Zed\CartsRestApi\CartsRestApiDependencyProvider;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\CartsRestApi\CartsRestApiConfig getConfig()
 * @method \Spryker\Zed\CartsRestApi\Persistence\CartsRestApiEntityManagerInterface getEntityManager()
 */
class CartsRestApiPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Quote\Persistence\SpyQuoteQuery
     */
    public function getQuoteQuery(): SpyQuoteQuery
    {
        return $this->getProvidedDependency(CartsRestApiDependencyProvider::PROPEL_QUERY_QUOTE);
    }
}
