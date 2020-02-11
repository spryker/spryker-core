<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductValidity\Persistence;

use Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\ProductValidity\Persistence\ProductValidityPersistenceFactory getFactory()
 */
class ProductValidityQueryContainer extends AbstractQueryContainer implements ProductValidityQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery
     */
    public function queryProductValidity(): SpyProductValidityQuery
    {
        return $this->getFactory()
            ->createProductValidityQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery
     */
    public function queryProductsBecomingValid(): SpyProductValidityQuery
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
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery
     */
    public function queryProductsBecomingInvalid(): SpyProductValidityQuery
    {
        return $this
            ->getFactory()
            ->createProductValidityQuery()
            ->filterByValidTo('now', Criteria::LESS_THAN);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductConcrete
     *
     * @return \Orm\Zed\ProductValidity\Persistence\SpyProductValidityQuery
     */
    public function queryProductValidityByIdProductConcrete(int $idProductConcrete): SpyProductValidityQuery
    {
        return $this->queryProductValidity()
            ->filterByFkProduct($idProductConcrete);
    }
}
