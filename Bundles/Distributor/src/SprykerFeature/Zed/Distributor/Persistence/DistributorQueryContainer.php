<?php

namespace SprykerFeature\Zed\Distributor\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\DistributorPersistence;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use Orm\Zed\Distributor\Persistence\Map\SpyDistributorItemTypeTableMap;
use Orm\Zed\Distributor\Persistence\Map\SpyDistributorReceiverTableMap;
use Orm\Zed\Distributor\Persistence\SpyDistributorItemQuery;
use Orm\Zed\Distributor\Persistence\SpyDistributorItemTypeQuery;
use SprykerEngine\Zed\Propel\Business\Formatter\PropelArraySetFormatter;
use Orm\Zed\Distributor\Persistence\SpyDistributorReceiverQuery;

/**
 * @method DistributorPersistence getFactory()
 */
class DistributorQueryContainer extends AbstractQueryContainer implements
    DistributorQueryContainerInterface
{

    /**
     * @return ModelCriteria
     */
    public function queryItemTypes()
    {
        $query = SpyDistributorItemTypeQuery::create()
            ->addSelectColumn(SpyDistributorItemTypeTableMap::COL_TYPE_KEY)
            ->setDistinct()
            ->setFormatter(new PropelArraySetFormatter())
        ;

        return $query;
    }

    /**
     * @param string $typeKey
     *
     * @return $this|SpyDistributorItemTypeQuery
     */
    public function queryTypeByKey($typeKey)
    {
        $query = SpyDistributorItemTypeQuery::create()
            ->filterByTypeKey($typeKey)
        ;

        return $query;
    }

    /**
     * @param string $typeKey
     * @param string $timestamp
     *
     * @throws PropelException
     *
     * @return SpyDistributorItemQuery
     */
    public function queryTouchedItemsByTypeKey($typeKey, $timestamp)
    {
        return SpyDistributorItemQuery::create()
            ->filterByTouched(['min' => $timestamp])
            ->useSpyDistributorItemTypeQuery()
            ->filterByTypeKey($typeKey)
            ->endUse()
        ;
    }

    /**
     * @return $this|ModelCriteria
     */
    public function queryReceivers()
    {
        $query = SpyDistributorReceiverQuery::create()
            ->addSelectColumn(SpyDistributorReceiverTableMap::COL_RECEIVER_KEY)
            ->setDistinct()
            ->setFormatter(new PropelArraySetFormatter())
        ;

        return $query;
    }

    /**
     * @param string $itemType
     * @param string $idItem
     *
     * @return SpyDistributorItemQuery
     */
    public function queryItemByTypeAndId($itemType, $idItem)
    {
        $foreignKeyColumn = $this->getForeignKeyColumnByType($itemType);

        $query = SpyDistributorItemQuery::create()
            ->addAnd($foreignKeyColumn, $idItem)
            ->useSpyDistributorItemTypeQuery()
            ->filterByTypeKey($itemType)
            ->endUse()
        ;

        return $query;
    }

    /**
     * @param $itemType
     *
     * @return string
     */
    protected function getForeignKeyColumnByType($itemType)
    {
        return 'fk_' . $itemType;
    }

}
