<?php

namespace SprykerFeature\Zed\Distributor\Persistence;

use Generated\Zed\Ide\FactoryAutoCompletion\DistributorPersistence;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Distributor\Persistence\Propel\Map\SpyDistributorItemTypeTableMap;
use SprykerFeature\Zed\Distributor\Persistence\Propel\Map\SpyDistributorReceiverTableMap;
use SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItemQuery;
use SprykerFeature\Zed\Distributor\Persistence\Propel\SpyDistributorItemTypeQuery;
use SprykerFeature\Zed\Library\Propel\Formatter\PropelArraySetFormatter;

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
        $query = $this->getFactory()->createPropelSpyDistributorItemTypeQuery()
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
        $query = $this->getFactory()->createPropelSpyDistributorItemTypeQuery()
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
        return $this->getFactory()->createPropelSpyDistributorItemQuery()
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
        $query = $this->getFactory()->createPropelSpyDistributorReceiverQuery()
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

        $query = $this->getFactory()->createPropelSpyDistributorItemQuery()
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
