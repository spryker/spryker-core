<?php

namespace Spryker\Zed\ProductValidity\Persistence;


use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductValidity\Persistence\ProductValidityPersistenceFactory getFactory()
 */
class ProductValidityQueryContainer extends AbstractQueryContainer implements ProductValidityQueryContainerInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery
     */
    public function queryProductValidity()
    {
        return $this->getFactory()
            ->createProductValidityQuery();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery
     */
    public function queryProductsBecomingValid()
    {
        return $this
            ->getFactory()
            ->createProductValidityQuery()
            ->filterByValidFrom('now', Criteria::LESS_EQUAL)
            ->filterByValidTo(null, Criteria::ISNULL)
            ->_or()
            ->filterByValidTo('now', Criteria::GREATER_EQUAL);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery
     */
    public function queryProductsBecomingInvalid()
    {
        return $this
            ->getFactory()
            ->createProductValidityQuery()
            ->filterByValidTo('now', Criteria::LESS_THAN);
    }
}