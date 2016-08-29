<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Marker;

use Exception;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchAttributeArchiveTableMap;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchAttributeMapArchiveTableMap;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchAttributeMapTableMap;
use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchAttributeTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchInterface;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;
use Spryker\Zed\Propel\Business\Formatter\PropelArraySetFormatter;

// TODO: clean me up
class AttributeMarker implements AttributeMarkerInterface
{

    const NOT_SYNCED = false;

    /**
     * @var \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface
     */
    protected $productSearchQueryContainer;

    /**
     * @param \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchInterface $touchFacade
     * @param \Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface $productSearchQuery
     */
    public function __construct(ProductSearchToTouchInterface $touchFacade, ProductSearchQueryContainerInterface $productSearchQuery)
    {
        $this->touchFacade = $touchFacade;
        $this->productSearchQueryContainer = $productSearchQuery;
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function touchProductAbstractByAsynchronousAttributes()
    {
        $attributeNames = array_merge(
            $this->queryAttributeKeys($this->productSearchQueryContainer->queryProductSearchAttributeBySynced(static::NOT_SYNCED)),
            $this->queryArchivedAttributeKeys()
        );

        if (!$attributeNames) {
            return;
        }

        $this->productSearchQueryContainer
            ->getConnection()
            ->beginTransaction();

        try {
            $this->touchProductAbstractByAttributeNames($attributeNames);

            $syncedFieldName = SpyProductSearchAttributeTableMap::getTableMap()
                ->getColumn(SpyProductSearchAttributeTableMap::COL_SYNCED)
                ->getPhpName();

            $this->productSearchQueryContainer
                ->queryProductSearchAttributeBySynced(static::NOT_SYNCED)
                ->update([
                    $syncedFieldName => true,
                ]);

            $this->productSearchQueryContainer->queryProductSearchAttributeArchive()->deleteAll();

            $this->productSearchQueryContainer
                ->getConnection()
                ->commit();
        } catch (Exception $e) {
            $this->productSearchQueryContainer
                ->getConnection()
                ->rollBack();

            throw $e;
        }
    }

    /**
     * @throws \Exception
     *
     * @return void
     */
    public function touchProductAbstractByAsynchronousAttributeMap()
    {
        $attributeNames = array_merge(
            $this->queryAttributeKeys($this->productSearchQueryContainer->queryProductSearchAttributeMapBySynced(static::NOT_SYNCED)),
            $this->queryArchivedAttributeMapKeys()
        );

        if (!$attributeNames) {
            return;
        }

        $this->productSearchQueryContainer
            ->getConnection()
            ->beginTransaction();

        try {
            $this->touchProductAbstractByAttributeNames($attributeNames);

            $syncedFieldName = SpyProductSearchAttributeMapTableMap::getTableMap()
                ->getColumn(SpyProductSearchAttributeMapTableMap::COL_SYNCED)
                ->getPhpName();

            $this->productSearchQueryContainer
                ->queryProductSearchAttributeMapBySynced(static::NOT_SYNCED)
                ->update([
                    $syncedFieldName => true,
                ]);

            $this->productSearchQueryContainer->queryProductSearchAttributeMapArchive()->deleteAll();

            $this->productSearchQueryContainer
                ->getConnection()
                ->commit();
        } catch (Exception $e) {
            $this->productSearchQueryContainer
                ->getConnection()
                ->rollBack();

            throw $e;
        }
    }

    /**
     * @param array $attributeNames
     *
     * @return void
     */
    protected function touchProductAbstractByAttributeNames(array $attributeNames)
    {
        $productAbstractIds = $this->productSearchQueryContainer
            ->queryProductAbstractByAttributeName($attributeNames)
            ->select(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT)
            ->setFormatter(new PropelArraySetFormatter())
            ->find();

        $this->touchFacade->bulkTouchActive('product_abstract', $productAbstractIds);
    }

    /**
     * @param \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery|\Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery $query
     *
     * @return mixed
     */
    protected function queryAttributeKeys($query)
    {
        return $query
            ->joinSpyProductAttributeKey(SpyProductAttributeKeyTableMap::TABLE_NAME)
            ->select(SpyProductAttributeKeyTableMap::COL_KEY)
            ->setFormatter(new PropelArraySetFormatter())
            ->setDistinct()
            ->find();
    }

    /**
     * @return array
     */
    protected function queryArchivedAttributeMapKeys()
    {
        $join = new ModelJoin(
            SpyProductSearchAttributeMapArchiveTableMap::COL_FK_PRODUCT_ATTRIBUTE_KEY,
            SpyProductAttributeKeyTableMap::COL_ID_PRODUCT_ATTRIBUTE_KEY,
            Criteria::INNER_JOIN
        );
        $join->setTableMap(new SpyProductAttributeKeyTableMap());

        return $this->productSearchQueryContainer
            ->queryProductSearchAttributeMapArchive()
            ->addJoinObject($join, SpyProductAttributeKeyTableMap::TABLE_NAME)
            ->select(SpyProductAttributeKeyTableMap::COL_KEY)
            ->setFormatter(new PropelArraySetFormatter())
            ->setDistinct()
            ->find();
    }

    /**
     * @return array
     */
    protected function queryArchivedAttributeKeys()
    {
        $join = new ModelJoin(
            SpyProductSearchAttributeArchiveTableMap::COL_FK_PRODUCT_ATTRIBUTE_KEY,
            SpyProductAttributeKeyTableMap::COL_ID_PRODUCT_ATTRIBUTE_KEY,
            Criteria::INNER_JOIN
        );
        $join->setTableMap(new SpyProductAttributeKeyTableMap());

        return $this->productSearchQueryContainer
            ->queryProductSearchAttributeArchive()
            ->addJoinObject($join, SpyProductAttributeKeyTableMap::TABLE_NAME)
            ->select(SpyProductAttributeKeyTableMap::COL_KEY)
            ->setFormatter(new PropelArraySetFormatter())
            ->setDistinct()
            ->find();
    }

}
