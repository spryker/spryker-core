<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Marker;

use Exception;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductAttributeKeyTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Spryker\Shared\ProductSearch\ProductSearchConfig;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToTouchInterface;
use Spryker\Zed\ProductSearch\Persistence\ProductSearchQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;

abstract class AbstractAttributeMarker implements AttributeMarkerInterface
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
    public function touchProductAbstract()
    {
        $attributeNames = $this->getAttributeNames();

        if (!$attributeNames) {
            return;
        }

        $this->productSearchQueryContainer
            ->getConnection()
            ->beginTransaction();

        try {
            $this->processAttributes($attributeNames);

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
     * @return array
     */
    abstract protected function getAttributeNames();

    /**
     * @param array $attributeNames
     *
     * @return void
     */
    abstract protected function processAttributes(array $attributeNames);

    /**
     * @param \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeArchiveQuery|\Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapArchiveQuery $query
     * @param string $leftColumn
     *
     * @return array
     */
    protected function queryArchivedAttributeKeys($query, $leftColumn)
    {
        $join = new ModelJoin(
            $leftColumn,
            SpyProductAttributeKeyTableMap::COL_ID_PRODUCT_ATTRIBUTE_KEY,
            Criteria::INNER_JOIN
        );
        $join->setTableMap(new SpyProductAttributeKeyTableMap());

        return $query
            ->addJoinObject($join, SpyProductAttributeKeyTableMap::TABLE_NAME)
            ->select(SpyProductAttributeKeyTableMap::COL_KEY)
            ->setFormatter(new PropelArraySetFormatter())
            ->setDistinct()
            ->find();
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

        $this->touchFacade->bulkTouchSetActive(ProductSearchConfig::RESOURCE_TYPE_PRODUCT_ABSTRACT, $productAbstractIds);
    }

    /**
     * @param \Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery|\Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeMapQuery $query
     *
     * @return array
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
}
