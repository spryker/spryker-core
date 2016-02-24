<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SequenceNumber\Persistence;

use Orm\Zed\SequenceNumber\Persistence\SpySequenceNumberQuery;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\SequenceNumber\Persistence\SequenceNumberPersistenceFactory getFactory()
 */
class SequenceNumberQueryContainer extends AbstractQueryContainer implements SequenceNumberQueryContainerInterface
{

    /**
     * @return \Orm\Zed\SequenceNumber\Persistence\SpySequenceNumberQuery
     */
    public function querySequenceNumber()
    {
        return $this->getFactory()->createSequenceNumberQuery();
    }

    /**
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
