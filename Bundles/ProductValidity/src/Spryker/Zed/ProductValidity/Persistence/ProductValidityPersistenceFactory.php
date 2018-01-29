<?php

namespace Spryker\Zed\ProductValidity\Persistence;

use Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class ProductValidityPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery
     */
    public function createProductValidityQuery()
    {
        return SpyProductValidityQuery::create();
    }
}