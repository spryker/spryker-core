<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\QueryPropelRule\Persistence;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\RuleQuerySetTransfer;
use Generated\Shared\Transfer\RuleQueryTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\QueryPropelRule\Persistence\QueryPropelRuleQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group QueryPropelRule
 * @group Persistence
 * @group QueryContainerTest
 */
class QueryContainerTest extends Test
{

    const EXPECTED_SKU_COLLECTION = ['019', '029', '031'];

    /**
     * @var string
     */
    protected $jsonDataNoMappings = '{"condition":"AND","rules":[{"id":"spy_product_abstract.sku","field":"spy_product_abstract.sku","type":"string","input":"text","operator":"in","value":"019,029,031"}]}';

    /**
     * @var string
     */
    protected $jsonData  = '{"condition":"AND","rules":[{"id":"product_sku","field":"product_sku","type":"string","input":"text","operator":"in","value":"019,029,031"}]}';

    /**
     * @var \Spryker\Zed\QueryPropelRule\Persistence\QueryPropelRuleQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @return void
     */
    protected function setUp()
    {
        $this->queryContainer = new QueryPropelRuleQueryContainer();
    }

    /**
     * @return void
     */
    public function testPropelCreateQueryWithoutMappings()
    {
        $query = SpyProductAbstractQuery::create();

        $ruleQuerySetTransfer = new RuleQuerySetTransfer();
        $ruleQuerySetTransfer->fromArray($this->getCriteriaData());
        $ruleQueryTransfer = new RuleQueryTransfer();
        $ruleQueryTransfer->setRuleSet($ruleQuerySetTransfer);

        $query = $this->queryContainer->createQuery($query, $ruleQueryTransfer);
        $results = $query->find();

        $this->assertCount(3, $results);
        $this->assertSkuCollection($results, static::EXPECTED_SKU_COLLECTION);
    }

    /**
     * @return void
     */
    public function testPropelCreateQueryWithMappings()
    {
        $query = SpyProductAbstractQuery::create();
        $query->innerJoinSpyProduct();

        $ruleQuerySetTransfer = new RuleQuerySetTransfer();
        $ruleQuerySetTransfer->fromArray($this->getCriteriaDataNoMappings());
        $ruleQueryTransfer = new RuleQueryTransfer();
        $ruleQueryTransfer->setRuleSet($ruleQuerySetTransfer);
        $ruleQueryTransfer->setMappings([
            'product_sku' => [
                SpyProductAbstractTableMap::COL_SKU,
                SpyProductTableMap::COL_SKU,
            ],
        ]);

        $query = $this->queryContainer->createQuery($query, $ruleQueryTransfer);
        $results = $query->find();

        $this->assertCount(7, $results);
        $this->assertSkuCollection($results, static::EXPECTED_SKU_COLLECTION);
    }

    /**
     * @return void
     */
    public function testCreateRuleSetFromJson()
    {
        $query = SpyProductAbstractQuery::create();
        $query->innerJoinSpyProduct();

        $ruleQuerySetTransfer = new RuleQuerySetTransfer();
        $ruleQuerySetTransfer->fromArray($this->getCriteriaDataNoMappings());
        $ruleQueryTransfer = new RuleQueryTransfer();
        $ruleQueryTransfer->setRuleSet($ruleQuerySetTransfer);
        $ruleQueryTransfer->setMappings([
            'product_sku' => [
                SpyProductAbstractTableMap::COL_SKU,
                SpyProductTableMap::COL_SKU,
            ],
        ]);

        $ruleQuerySetTransfer = $this->queryContainer->createRuleSetFromJson($this->jsonData);

        $this->assertInstanceOf(RuleQuerySetTransfer::class, $ruleQuerySetTransfer);
        $this->assertInstanceOf(RuleQuerySetTransfer::class, current($ruleQuerySetTransfer->getRules()));
    }

    /**
     * @return array
     */
    protected function getCriteriaData()
    {
        return json_decode($this->jsonData, true);
    }

    /**
     * @return array
     */
    protected function getCriteriaDataNoMappings()
    {
        return json_decode($this->jsonDataNoMappings, true);
    }

    /**
     * @param mixed $collection
     * @param array $expectedSkuCollection
     *
     * @return void
     */
    protected function assertSkuCollection($collection, array $expectedSkuCollection)
    {
        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity */
        foreach ($collection as $productAbstractEntity) {
            $sku = $productAbstractEntity->getSku();
            $this->assertContains($sku, $expectedSkuCollection);
        }
    }

}
