<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SequenceNumber\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\SequenceNumber\Persistence\SequenceNumberPersistenceFactory getFactory()
 */
class SequenceNumberQueryContainer extends AbstractQueryContainer implements SequenceNumberQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\SequenceNumber\Persistence\SpySequenceNumberQuery
     */
    public function querySequenceNumber()
    {
        return $this->getFactory()->createSequenceNumberQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated This is not used anymore.
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\SequenceNumber\Persistence\SpySequenceNumberQuery
     */
    public function querySequenceNumbersByIdSalesOrder($idSalesOrder)
    {
        $query = $this->getFactory()->createSequenceNumberQuery();
        $query->filterByFkSalesOrder($idSalesOrder);

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idMethod
     *
     * @return \Orm\Zed\SequenceNumber\Persistence\SpySequenceNumberQuery
     */
    public function querySequenceNumberByIdSequenceNumber($idMethod)
    {
        $query = $this->querySequenceNumber();
        $query->filterByIdSequenceNumber($idMethod);

        return $query;
    }
}
