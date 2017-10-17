<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Persistence\Rule;

use ArrayObject;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer;
use Spryker\Zed\ProductRelation\Dependency\QueryContainer\ProductRelationToPropelQueryBuilderInterface;
use Spryker\Zed\ProductRelation\Persistence\Rule\Query\QueryInterface;

class ProductRelationRuleQueryCreator implements ProductRelationRuleQueryCreatorInterface
{
    /**
     * @var \Spryker\Zed\ProductRelation\Dependency\QueryContainer\ProductRelationToPropelQueryBuilderInterface
     */
    protected $queryPropelRuleQueryContainer;

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\Rule\Query\QueryInterface
     */
    protected $productQuery;

    /**
     * @param \Spryker\Zed\ProductRelation\Dependency\QueryContainer\ProductRelationToPropelQueryBuilderInterface $queryPropelRuleQueryContainer
     * @param \Spryker\Zed\ProductRelation\Persistence\Rule\Query\QueryInterface $productQuery
     */
    public function __construct(
        ProductRelationToPropelQueryBuilderInterface $queryPropelRuleQueryContainer,
        QueryInterface $productQuery
    ) {
        $this->queryPropelRuleQueryContainer = $queryPropelRuleQueryContainer;
        $this->productQuery = $productQuery;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function createQuery(ProductRelationTransfer $productRelationTransfer)
    {
        $ruleQueryTransfer = $this->mapRuleQueryTransfer($productRelationTransfer);

        $query = $this->productQuery->prepareQuery($productRelationTransfer->getQueryDataProvider());

        return $this->queryPropelRuleQueryContainer->createQuery($query, $ruleQueryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderCriteriaTransfer
     */
    protected function mapRuleQueryTransfer(ProductRelationTransfer $productRelationTransfer)
    {
        $propelQueryBuilderCriteriaTransfer = new PropelQueryBuilderCriteriaTransfer();
        $propelQueryBuilderCriteriaTransfer->setRuleSet($productRelationTransfer->getQuerySet());
        $propelQueryBuilderCriteriaTransfer->setMappings(new ArrayObject($this->productQuery->getMappings()));

        return $propelQueryBuilderCriteriaTransfer;
    }
}
