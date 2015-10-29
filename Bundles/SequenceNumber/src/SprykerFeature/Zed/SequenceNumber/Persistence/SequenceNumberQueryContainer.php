<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SequenceNumber\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\SequenceNumberPersistence;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\SequenceNumber\Persistence\SpySequenceNumberQuery;

/**
 * @method SequenceNumberPersistence getFactory()
 */
class SequenceNumberQueryContainer extends AbstractQueryContainer implements SequenceNumberQueryContainerInterface
{

    /**
     * @return SpySequenceNumberQuery
     */
    public function querySequenceNumber()
    {
        return (new SpySequenceNumberQuery());
    }

    public function querySequenceNumbersByIdSalesOrder($idSalesOrder)
    {
        $query = SpySequenceNumberQuery::create();
        $query->filterByFkSalesOrder($idSalesOrder);

        return $query;
    }

    /**
     * @param int $idMethod
     *
     * @return SpySequenceNumberQuery
     */
    public function querySequenceNumberByIdSequenceNumber($idMethod)
    {
        $query = $this->querySequenceNumber();
        $query->filterByIdSequenceNumber($idMethod);

        return $query;
    }

}
