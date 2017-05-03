<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\PropelQueryBuilder\Persistence;

use ArrayObject;
use Codeception\TestCase\Test;
use Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaMappingTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderSortTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderTableTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\TableMap;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\PropelQueryBuilder\Persistence\PropelQueryBuilderQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group PropelQueryBuilder
 * @group Persistence
 * @group QueryContainerTest
 */
class QueryContainerTest extends Test
{

    const LIMIT = 10;
    const PAGE = 2;

    const EXPECTED_COUNT = 8;
    const EXPECTED_OFFSET = 10;
    const EXPECTED_SKU_COLLECTION = [
        '001_25904004',
        '019_30395396',
        '019_31080444',
        '029_13374503',
        '029_20370432',
        '029_13391322',
        '031_19618271',
        '031_21927455',
    ];

    /**
     * @var string
     */
    protected $jsonDataWithMappings = '{"condition":"OR","rules":[{"id":"product_sku","field":"product_sku","type":"string","input":"text","operator":"in","value":"019,029,031"},{"id":"product_sku","field":"product_sku","type":"string","input":"text","operator":"in","value":"001_25904004"}]}';

    /**
     * @var string
     */
    protected $jsonDataNoMappings = '{"condition":"OR","rules":[{"id":"spy_product_abstract.sku","field":"spy_product_abstract.sku","type":"string","input":"text","operator":"in","value":"019,029,031"},{"id":"spy_product_abstract.sku","field":"spy_product.sku","type":"string","input":"text","operator":"in","value":"001_25904004"}]}';

    /**
     * @var string
     */
    protected $jsonDataForPagination = '{"condition":"OR","rules":[{"id":"spy_product_abstract.id_product_abstract","field":"spy_product_abstract.id_product_abstract","type":"number","input":"text","operator":"greater_or_equal","value":"1"}]}';

    /**
     * @var \Spryker\Zed\PropelQueryBuilder\Persistence\PropelQueryBuilderQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->queryContainer = new PropelQueryBuilderQueryContainer();
    }

    /**
     * @return void
     */
    public function testPropelCreateQueryWithEmptyRuleSetShouldThrowException()
    {
        $this->expectException(RequiredTransferPropertyException::class);

        $query = SpyProductQuery::create();
        $query->innerJoinSpyProductAbstract();

        $criteriaTransfer = new PropelQueryBuilderCriteriaTransfer();

        $this->queryContainer->createQuery($query, $criteriaTransfer);
    }

    /**
     * @return void
     */
    public function testPropelCreateQueryWithoutMappings()
    {
        $query = SpyProductQuery::create();
        $query->innerJoinSpyProductAbstract();

        $criteriaTransfer = $this->getCriteriaWithoutMappings();

        $query = $this->queryContainer->createQuery($query, $criteriaTransfer);
        $results = $query->find();

        $this->assertCount(static::EXPECTED_COUNT, $results);
        $this->assertSkuCollection($results, static::EXPECTED_SKU_COLLECTION);
    }

    /**
     * @return void
     */
    public function testPropelCreateQueryWithMappings()
    {
        $query = SpyProductQuery::create();
        $query->innerJoinSpyProductAbstract();

        $criteriaTransfer = $this->getCriteriaWithMappings();

        $query = $this->queryContainer->createQuery($query, $criteriaTransfer);
        $results = $query->find();

        $this->assertCount(static::EXPECTED_COUNT, $results);
        $this->assertSkuCollection($results, static::EXPECTED_SKU_COLLECTION);
    }

    /**
     * @return void
     */
    public function testCreateRuleSetFromJson()
    {
        $query = SpyProductAbstractQuery::create();
        $query->innerJoinSpyProduct();

        $ruleQuerySetTransfer = $this->queryContainer->createPropelQueryBuilderCriteriaFromJson($this->jsonDataWithMappings);

        $this->assertInstanceOf(PropelQueryBuilderRuleSetTransfer::class, $ruleQuerySetTransfer);
        $this->assertInstanceOf(PropelQueryBuilderRuleSetTransfer::class, current($ruleQuerySetTransfer->getRules()));
    }

    /**
     * @return void
     */
    public function testPropelCreateQueryWithoutMappingsWithPagination()
    {
        $query = SpyProductQuery::create();
        $query->innerJoinSpyProductAbstract();

        $criteriaTransfer = $this->getCriteriaForPagination();

        $query = $this->queryContainer->createQuery($query, $criteriaTransfer);
        $count = $query->count();
        $results = $query->find();

        $this->assertEquals(self::EXPECTED_OFFSET, $query->getOffset());
        $this->assertEquals(self::LIMIT, $query->getLimit());
        $this->assertEquals(self::LIMIT, $count);
        $this->assertEquals($this->getFirstProductIdOnSecondPage(), $results->getFirst()['id_product']);
    }

    /**
     * @return void
     */
    public function testPropelCreateQueryWithoutMappingsWithoutPaginationWithSelectedColumns()
    {
        $query = SpyProductQuery::create();
        $query->innerJoinSpyProductAbstract();

        $criteriaTransfer = $this->getCriteriaWithoutMappingsWithSelectedColumns();

        $query = $this->queryContainer->createQuery($query, $criteriaTransfer);
        $results = $query->find();

        $this->assertCount(static::EXPECTED_COUNT, $results);
        $this->assertSkuCollection($results->toArray(), static::EXPECTED_SKU_COLLECTION);
    }

    /**
     * @return void
     */
    public function testPropelCreateQueryWithoutMappingsWithoutPaginationWithEmptySelectedColumnsShouldFallbackToTableColumns()
    {
        $query = SpyProductQuery::create();
        $query->innerJoinSpyProductAbstract();

        $criteriaTransfer = $this->getCriteriaWithoutMappingsWithSelectedColumns();
        $criteriaTransfer->setSelectedColumns(new ArrayObject([]));

        $query = $this->queryContainer->createQuery($query, $criteriaTransfer);
        $results = $query->find();

        $this->assertCount(static::EXPECTED_COUNT, $results);
        $this->assertSkuCollection($results->toArray(), static::EXPECTED_SKU_COLLECTION);
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function getCriteriaWithMappings()
    {
        $criteriaTransfer = $this->createCriteriaTransfer($this->jsonDataWithMappings);

        $skuMapping = new PropelQueryBuilderCriteriaMappingTransfer();
        $skuMapping->setAlias('product_sku');
        $skuMapping->setColumns([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductTableMap::COL_SKU,
        ]);
        $criteriaTransfer->addMapping($skuMapping);

        return $criteriaTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function getCriteriaWithoutMappings()
    {
        $criteriaTransfer = $this->createCriteriaTransfer($this->jsonDataNoMappings);

        return $criteriaTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function getCriteriaWithoutMappingsWithSelectedColumns()
    {
        $criteriaTransfer = $this->getCriteriaWithoutMappings();

        $columnTransfer = new PropelQueryBuilderColumnTransfer();
        $columnTransfer->setName(SpyProductTableMap::COL_ID_PRODUCT);
        $columnTransfer->setAlias('id_product');
        $criteriaTransfer->addSelectedColumn($columnTransfer);

        $columnTransfer = new PropelQueryBuilderColumnTransfer();
        $columnTransfer->setName(SpyProductTableMap::COL_SKU);
        $columnTransfer->setAlias('sku');
        $criteriaTransfer->addSelectedColumn($columnTransfer);

        return $criteriaTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function getCriteriaForPagination()
    {
        $columnTransfer = new PropelQueryBuilderColumnTransfer();
        $columnTransfer->setName(SpyProductTableMap::COL_ID_PRODUCT);
        $columnTransfer->setAlias('id_product');

        $sortItems[] = (new PropelQueryBuilderSortTransfer())
            ->setColumn($columnTransfer)
            ->setSortDirection(Criteria::DESC);

        $paginationTransfer = new PropelQueryBuilderPaginationTransfer();
        $paginationTransfer->setPage(self::PAGE);
        $paginationTransfer->setItemsPerPage(self::LIMIT);
        $paginationTransfer->setSortItems(new ArrayObject($sortItems));

        $criteriaTransfer = $this->createCriteriaTransfer($this->jsonDataForPagination);
        $criteriaTransfer->setPagination($paginationTransfer);

        return $criteriaTransfer;
    }

    /**
     * @return int
     */
    protected function getFirstProductIdOnSecondPage()
    {
        $idCollection = SpyProductQuery::create()
            ->select(SpyProductTableMap::COL_ID_PRODUCT)
            ->orderByIdProduct(Criteria::DESC)
            ->setOffset(self::EXPECTED_OFFSET)
            ->setLimit(self::LIMIT)
            ->find()
            ->toArray();

        return current($idCollection);
    }

    /**
     * @param mixed $collection
     * @param array $expectedSkuCollection
     *
     * @return void
     */
    protected function assertSkuCollection($collection, array $expectedSkuCollection)
    {
        /** @var \Orm\Zed\Product\Persistence\SpyProduct|\Orm\Zed\Product\Persistence\SpyProduct $productData */
        foreach ($collection as $productData) {
            $this->assertContains($productData['sku'], $expectedSkuCollection);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderTableTransfer
     */
    protected function getTableTransfer()
    {
        $tableTransfer = new PropelQueryBuilderTableTransfer();
        $tableTransfer->setName(SpyProductTableMap::TABLE_NAME);

        foreach (SpyProductTableMap::getFieldNames(TableMap::TYPE_FIELDNAME) as $fieldName) {
            $tableColumnTransfer = new PropelQueryBuilderColumnTransfer();
            $tableColumnTransfer->setAlias($fieldName);
            $tableColumnTransfer->setName(SpyProductTableMap::TABLE_NAME . '.' . $fieldName);

            $tableTransfer->addColumn($tableColumnTransfer);
        }
        return $tableTransfer;
    }

    /**
     * @param string $jsonString
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function createCriteriaTransfer($jsonString)
    {
        $json = json_decode($jsonString, true);

        $ruleQuerySetTransfer = new PropelQueryBuilderRuleSetTransfer();
        $ruleQuerySetTransfer->fromArray($json);

        $tableTransfer = $this->getTableTransfer();

        $criteriaTransfer = new PropelQueryBuilderCriteriaTransfer();
        $criteriaTransfer->setRuleSet($ruleQuerySetTransfer);
        $criteriaTransfer->setTable($tableTransfer);

        return $criteriaTransfer;
    }

}
