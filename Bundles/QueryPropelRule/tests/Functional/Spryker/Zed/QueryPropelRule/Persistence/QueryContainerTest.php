<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\QueryPropelRule\Persistence;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\RuleQueryMappingTransfer;
use Generated\Shared\Transfer\RuleQuerySetTransfer;
use Generated\Shared\Transfer\RuleQueryTransfer;
use Orm\Zed\Product\Persistence\Base\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
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

    const EXPECTED_COUNT = 8;

    /**
     * @var string
     */
    protected $jsonDataWithMappings = '{"condition":"OR","rules":[{"id":"product_sku","field":"product_sku","type":"string","input":"text","operator":"in","value":"019,029,031"},{"id":"product_sku","field":"product_sku","type":"string","input":"text","operator":"in","value":"001_25904004"}]}';

    /**
     * @var string
     */
    protected $jsonDataNoMappings = '{"condition":"OR","rules":[{"id":"spy_product_abstract.sku","field":"spy_product_abstract.sku","type":"string","input":"text","operator":"in","value":"019,029,031"},{"id":"spy_product_abstract.sku","field":"spy_product.sku","type":"string","input":"text","operator":"in","value":"001_25904004"}]}';

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
        $query = SpyProductQuery::create();
        $query->innerJoinSpyProductAbstract();

        $ruleQuerySetTransfer = new RuleQuerySetTransfer();
        $ruleQuerySetTransfer->fromArray($this->getCriteriaDataNoMappings());
        $ruleQueryTransfer = new RuleQueryTransfer();
        $ruleQueryTransfer->setRuleSet($ruleQuerySetTransfer);

        $query = $this->queryContainer->createQuery($query, $ruleQueryTransfer);
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

        $ruleQuerySetTransfer = new RuleQuerySetTransfer();
        $ruleQuerySetTransfer->fromArray($this->getCriteriaData());
        $ruleQueryTransfer = new RuleQueryTransfer();
        $ruleQueryTransfer->setRuleSet($ruleQuerySetTransfer);

        $skuMapping = new RuleQueryMappingTransfer();
        $skuMapping->setAlias('product_sku');
        $skuMapping->setColumns([
            SpyProductAbstractTableMap::COL_SKU,
            SpyProductTableMap::COL_SKU,
        ]);
        $ruleQueryTransfer->addMapping($skuMapping);

        $query = $this->queryContainer->createQuery($query, $ruleQueryTransfer);
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

        $ruleQuerySetTransfer = new RuleQuerySetTransfer();
        $ruleQuerySetTransfer->fromArray($this->getCriteriaDataNoMappings());
        $ruleQueryTransfer = new RuleQueryTransfer();
        $ruleQueryTransfer->setRuleSet($ruleQuerySetTransfer);

        $ruleQuerySetTransfer = $this->queryContainer->createRuleSetFromJson($this->jsonDataWithMappings);

        $this->assertInstanceOf(RuleQuerySetTransfer::class, $ruleQuerySetTransfer);
        $this->assertInstanceOf(RuleQuerySetTransfer::class, current($ruleQuerySetTransfer->getRules()));
    }

    /**
     * @return array
     */
    protected function getCriteriaData()
    {
        return json_decode($this->jsonDataWithMappings, true);
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
        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract|\Orm\Zed\Product\Persistence\SpyProduct $productAbstractEntity */
        foreach ($collection as $productAbstractEntity) {
            $sku = $productAbstractEntity->getSku();
            $this->assertContains($sku, $expectedSkuCollection);
        }
    }

}
