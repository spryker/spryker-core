<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PropelQueryBuilder\Persistence;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderColumnTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaMappingTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderPaginationTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderSortTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Map\TableMap;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Zed\PropelQueryBuilder\Persistence\PropelQueryBuilderQueryContainer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PropelQueryBuilder
 * @group Persistence
 * @group QueryContainerTest
 * Add your own group annotations below this line
 */
class QueryContainerTest extends Unit
{
    public const LIMIT = 10;
    public const PAGE = 2;

    public const EXPECTED_COUNT = 8;
    public const EXPECTED_OFFSET = 10;
    public const EXPECTED_SKU_COLLECTION = [
        'test_concrete_sku_1',
        'test_concrete_sku_2',
        'test_concrete_sku_3',
        'test_concrete_sku_4',
        'test_concrete_sku_5',
        'test_concrete_sku_6',
        'test_concrete_sku_7',
        'test_concrete_sku_8',
    ];

    /**
     * @var string
     */
    protected $jsonDataWithMappings = '{
      "condition": "OR",
      "rules": [
        {
          "id": "product_sku",
          "field": "product_sku",
          "type": "string",
          "input": "text",
          "operator": "in",
          "value": "test_abstract_sku_1,test_abstract_sku_2,test_abstract_sku_3,test_abstract_sku_4"
        },
        {
          "id": "product_sku",
          "field": "product_sku",
          "type": "string",
          "input": "text",
          "operator": "in",
          "value": "test_concrete_sku_5,test_concrete_sku_6,test_concrete_sku_7,test_concrete_sku_8"
        }
      ]
    }';

    /**
     * @var string
     */
    protected $jsonDataNoMappings = '{
      "condition": "OR",
      "rules": [
        {
          "id": "spy_product_abstract.sku",
          "field": "spy_product_abstract.sku",
          "type": "string",
          "input": "text",
          "operator": "in",
          "value": "test_abstract_sku_1,test_abstract_sku_2,test_abstract_sku_3,test_abstract_sku_4"
        },
        {
          "id": "spy_product_abstract.sku",
          "field": "spy_product.sku",
          "type": "string",
          "input": "text",
          "operator": "in",
          "value": "test_concrete_sku_5,test_concrete_sku_6,test_concrete_sku_7,test_concrete_sku_8"
        }
      ]
    }';

    /**
     * @var string
     */
    protected $jsonDataForPagination = '{
      "condition": "OR",
      "rules": [
        {
          "id": "spy_product_abstract.id_product_abstract",
          "field": "spy_product_abstract.id_product_abstract",
          "type": "number",
          "input": "text",
          "operator": "greater_or_equal",
          "value": "1"
        }
      ]
    }';

    /**
     * @var \Spryker\Zed\PropelQueryBuilder\Persistence\PropelQueryBuilderQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \SprykerTest\Zed\PropelQueryBuilder\PropelQueryBuilderPersistenceTester
     */
    protected $tester;

    /**
     * @var \Propel\Runtime\ActiveQuery\ModelCriteria|\Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected $query;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->queryContainer = new PropelQueryBuilderQueryContainer();

        $this->query = SpyProductQuery::create();
        $this->query->innerJoinSpyProductAbstract();

        $this->prepareTestProducts();
    }

    /**
     * @return void
     */
    public function testPropelCreateQueryWithEmptyRuleSetShouldThrowException(): void
    {
        $this->expectException(RequiredTransferPropertyException::class);

        $criteriaTransfer = new PropelQueryBuilderCriteriaTransfer();

        $this->queryContainer->createQuery($this->query, $criteriaTransfer);
    }

    /**
     * @return void
     */
    public function testPropelCreateQueryWithoutMappings(): void
    {
        $criteriaTransfer = $this->getCriteriaWithoutMappings();

        $query = $this->queryContainer->createQuery($this->query, $criteriaTransfer);

        $results = $query->find();
        $this->assertCount(static::EXPECTED_COUNT, $results);
        $this->assertSkuCollection($results, static::EXPECTED_SKU_COLLECTION);
    }

    /**
     * @return void
     */
    public function testPropelCreateQueryWithMappings(): void
    {
        $criteriaTransfer = $this->getCriteriaWithMappings();

        $query = $this->queryContainer->createQuery($this->query, $criteriaTransfer);

        $results = $query->find();
        $this->assertCount(static::EXPECTED_COUNT, $results);
        $this->assertSkuCollection($results, static::EXPECTED_SKU_COLLECTION);
    }

    /**
     * @return void
     */
    public function testCreateRuleSetFromJson(): void
    {
        $json = $this->jsonDataWithMappings;

        $ruleQuerySetTransfer = $this->queryContainer->createPropelQueryBuilderCriteriaFromJson($json);

        /** @var \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer $propelQueryBuilderRuleSetTransfer */
        $propelQueryBuilderRuleSetTransfer = $ruleQuerySetTransfer->getRules()[0];

        $this->assertInstanceOf(PropelQueryBuilderRuleSetTransfer::class, $ruleQuerySetTransfer);
        $this->assertInstanceOf(PropelQueryBuilderRuleSetTransfer::class, $propelQueryBuilderRuleSetTransfer);
    }

    /**
     * @return void
     */
    public function testPropelCreateQueryWithoutMappingsWithPagination(): void
    {
        $criteriaTransfer = $this->getCriteriaForPagination();

        $query = $this->queryContainer->createQuery($this->query, $criteriaTransfer);

        $this->assertSame(static::EXPECTED_OFFSET, $query->getOffset());
        $this->assertSame(static::LIMIT, $query->getLimit());
        $this->assertSame(static::LIMIT, $query->count());
        $this->assertSame($this->getFirstProductIdOnSecondPage(), $query->find()->getFirst()->getIdProduct());
    }

    /**
     * @return void
     */
    public function testPropelCreateQueryWithoutMappingsWithPaginationAndItemsPerPage(): void
    {
        $criteriaTransfer = $this->getCriteriaForPaginationPageAndItemsPerPage();

        $query = $this->queryContainer->createQuery($this->query, $criteriaTransfer);

        $this->assertSame(static::EXPECTED_OFFSET, $query->getOffset());
        $this->assertSame(static::LIMIT, $query->getLimit());
        $this->assertSame(static::LIMIT, $query->count());
        $this->assertSame($this->getFirstProductIdOnSecondPage(), $query->find()->getFirst()->getIdProduct());
    }

    /**
     * @return void
     */
    public function testPropelCreateQueryWithoutMappingsWithoutPaginationWithSelectedColumns(): void
    {
        $criteriaTransfer = $this->getCriteriaWithoutMappingsWithSelectedColumns();

        $query = $this->queryContainer->createQuery($this->query, $criteriaTransfer);

        $results = $query->find();
        $this->assertCount(static::EXPECTED_COUNT, $results);
        $this->assertSkuCollectionWithSelectedColumns($results->toArray(), static::EXPECTED_SKU_COLLECTION);
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function getCriteriaWithMappings(): PropelQueryBuilderCriteriaTransfer
    {
        $json = json_decode($this->jsonDataWithMappings, true);

        $ruleQuerySetTransfer = new PropelQueryBuilderRuleSetTransfer();
        $ruleQuerySetTransfer->fromArray($json);
        $criteriaTransfer = new PropelQueryBuilderCriteriaTransfer();
        $criteriaTransfer->setRuleSet($ruleQuerySetTransfer);

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
    protected function getCriteriaWithoutMappings(): PropelQueryBuilderCriteriaTransfer
    {
        $json = json_decode($this->jsonDataNoMappings, true);

        $ruleQuerySetTransfer = new PropelQueryBuilderRuleSetTransfer();
        $ruleQuerySetTransfer->fromArray($json);

        $criteriaTransfer = new PropelQueryBuilderCriteriaTransfer();
        $criteriaTransfer->setRuleSet($ruleQuerySetTransfer);

        return $criteriaTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function getCriteriaWithoutMappingsWithSelectedColumns(): PropelQueryBuilderCriteriaTransfer
    {
        $criteriaTransfer = $this->getCriteriaWithoutMappings();
        $columnSelectionTransfer = $this->getColumnSelectionTransfer();

        $columnTransfer = new PropelQueryBuilderColumnTransfer();
        $columnTransfer->setName(SpyProductTableMap::COL_ID_PRODUCT);
        $columnTransfer->setAlias('id_product');
        $columnSelectionTransfer->addSelectedColumn($columnTransfer);

        $columnTransfer = new PropelQueryBuilderColumnTransfer();
        $columnTransfer->setName(SpyProductTableMap::COL_SKU);
        $columnTransfer->setAlias('sku');
        $columnSelectionTransfer->addSelectedColumn($columnTransfer);

        $criteriaTransfer->setColumnSelection($columnSelectionTransfer);

        return $criteriaTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function getCriteriaForPagination(): PropelQueryBuilderCriteriaTransfer
    {
        $json = json_decode($this->jsonDataForPagination, true);

        $columnTransfer = new PropelQueryBuilderColumnTransfer();
        $columnTransfer->setName(SpyProductTableMap::COL_ID_PRODUCT);
        $columnTransfer->setAlias('id_product');

        $sortItems = [];
        $sortItems[] = (new PropelQueryBuilderSortTransfer())
            ->setColumn($columnTransfer)
            ->setSortDirection(Criteria::DESC);

        $paginationTransfer = new PropelQueryBuilderPaginationTransfer();
        $paginationTransfer->setOffset(10);
        $paginationTransfer->setLimit(self::LIMIT);
        $paginationTransfer->setSortItems(new ArrayObject($sortItems));

        $ruleQuerySetTransfer = new PropelQueryBuilderRuleSetTransfer();
        $ruleQuerySetTransfer->fromArray($json);

        $criteriaTransfer = new PropelQueryBuilderCriteriaTransfer();
        $criteriaTransfer->setRuleSet($ruleQuerySetTransfer);
        $criteriaTransfer->setPagination($paginationTransfer);

        return $criteriaTransfer;
    }

    /**
     * @deprecated Use limit/offset instead
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function getCriteriaForPaginationPageAndItemsPerPage(): PropelQueryBuilderCriteriaTransfer
    {
        $json = json_decode($this->jsonDataForPagination, true);

        $columnTransfer = new PropelQueryBuilderColumnTransfer();
        $columnTransfer->setName(SpyProductTableMap::COL_ID_PRODUCT);
        $columnTransfer->setAlias('id_product');

        $sortItems = [];
        $sortItems[] = (new PropelQueryBuilderSortTransfer())
            ->setColumn($columnTransfer)
            ->setSortDirection(Criteria::DESC);

        $paginationTransfer = new PropelQueryBuilderPaginationTransfer();
        $paginationTransfer->setPage(self::PAGE);
        $paginationTransfer->setItemsPerPage(self::LIMIT);
        $paginationTransfer->setSortItems(new ArrayObject($sortItems));

        $ruleQuerySetTransfer = new PropelQueryBuilderRuleSetTransfer();
        $ruleQuerySetTransfer->fromArray($json);

        $criteriaTransfer = new PropelQueryBuilderCriteriaTransfer();
        $criteriaTransfer->setRuleSet($ruleQuerySetTransfer);
        $criteriaTransfer->setPagination($paginationTransfer);

        return $criteriaTransfer;
    }

    /**
     * @return int
     */
    protected function getFirstProductIdOnSecondPage(): int
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
     * @return \Generated\Shared\Transfer\PropelQueryBuilderColumnSelectionTransfer
     */
    protected function getColumnSelectionTransfer(): PropelQueryBuilderColumnSelectionTransfer
    {
        $columnSelectionTransfer = new PropelQueryBuilderColumnSelectionTransfer();

        $tableAliases = SpyProductTableMap::getFieldNames(TableMap::TYPE_FIELDNAME);
        foreach ($tableAliases as $columnAlias) {
            $columnTransfer = new PropelQueryBuilderColumnTransfer();
            $columnTransfer->setName(SpyProductTableMap::TABLE_NAME . '.' . $columnAlias);
            $columnTransfer->setAlias($columnAlias);

            $columnSelectionTransfer->addTableColumn($columnTransfer);
        }

        return $columnSelectionTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\Product\Persistence\SpyProduct[] $collection
     * @param array $expectedSkuCollection
     *
     * @return void
     */
    protected function assertSkuCollection($collection, array $expectedSkuCollection): void
    {
        foreach ($collection as $productEntity) {
            $sku = $productEntity->getSku();
            $this->assertContains($sku, $expectedSkuCollection);
        }
    }

    /**
     * @param array $collection
     * @param array $expectedSkuCollection
     *
     * @return void
     */
    protected function assertSkuCollectionWithSelectedColumns(array $collection, array $expectedSkuCollection): void
    {
        foreach ($collection as $productData) {
            $this->assertContains($productData['sku'], $expectedSkuCollection);
        }
    }

    /**
     * @return void
     */
    protected function prepareTestProducts(): void
    {
        $this->tester->haveProduct(['sku' => 'test_concrete_sku_1'], ['sku' => 'test_abstract_sku_1']);
        $this->tester->haveProduct(['sku' => 'test_concrete_sku_2'], ['sku' => 'test_abstract_sku_2']);
        $this->tester->haveProduct(['sku' => 'test_concrete_sku_3'], ['sku' => 'test_abstract_sku_3']);
        $this->tester->haveProduct(['sku' => 'test_concrete_sku_4'], ['sku' => 'test_abstract_sku_4']);
        $this->tester->haveProduct(['sku' => 'test_concrete_sku_5'], ['sku' => 'test_abstract_sku_5']);
        $this->tester->haveProduct(['sku' => 'test_concrete_sku_6'], ['sku' => 'test_abstract_sku_6']);
        $this->tester->haveProduct(['sku' => 'test_concrete_sku_7'], ['sku' => 'test_abstract_sku_7']);
        $this->tester->haveProduct(['sku' => 'test_concrete_sku_8'], ['sku' => 'test_abstract_sku_8']);
    }
}
