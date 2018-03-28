<?php


namespace Spryker\Zed\Offer\Persistence;


use Orm\Zed\Offer\Persistence\SpyOfferQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

class OfferPersistenceFactory extends  AbstractPersistenceFactory
{
    /**
     * @return SpyOfferQuery
     */
    public function createPropelOfferQuery()
    {
        return SpyOfferQuery::create();
    }
}