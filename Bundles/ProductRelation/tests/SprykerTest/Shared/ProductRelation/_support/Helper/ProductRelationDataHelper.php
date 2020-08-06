<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\ProductRelation\Helper;

use Codeception\Module;
use Generated\Shared\Transfer\ProductRelationTransfer;
use Generated\Shared\Transfer\ProductRelationTypeTransfer;
use Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationProductAbstractQuery;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationQuery;
use Orm\Zed\ProductRelation\Persistence\SpyProductRelationStoreQuery;
use Spryker\Shared\ProductRelation\ProductRelationTypes;
use Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductRelationDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @return void
     */
    public function ensureProductRelationTableIsEmpty(): void
    {
        $this->ensureProductRelationStoreTableIsEmpty();
        $this->ensureProductRelationProductAbstractTableIsEmpty();
        SpyProductRelationQuery::create()->deleteAll();
    }

    /**
     * @return void
     */
    public function ensureProductRelationProductAbstractTableIsEmpty(): void
    {
        SpyProductRelationProductAbstractQuery::create()->deleteAll();
    }

    /**
     * @return void
     */
    public function ensureProductRelationStoreTableIsEmpty(): void
    {
        SpyProductRelationStoreQuery::create()->deleteAll();
    }

    /**
     * @param string $productAbstractSku
     * @param int $idProductAbstract
     * @param string $productRelationKey
     * @param string $productRelationType
     * @param \Generated\Shared\Transfer\StoreRelationTransfer|null $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    public function haveProductRelation(
        string $productAbstractSku,
        int $idProductAbstract,
        string $productRelationKey,
        string $productRelationType = ProductRelationTypes::TYPE_UP_SELLING,
        ?StoreRelationTransfer $storeRelationTransfer = null
    ): ProductRelationTransfer {
        $productRelationFacade = $this->getProductRelationFacade();
        if (!$storeRelationTransfer) {
            $storeRelationTransfer = new StoreRelationTransfer();
        }

        $productRelationTransfer = $this->createProductRelationTransfer(
            $productAbstractSku,
            $idProductAbstract,
            $productRelationKey,
            $productRelationType,
            $storeRelationTransfer
        );

        $productRelationResponseTransfer = $productRelationFacade->createProductRelation($productRelationTransfer);
        $productRelationTransfer = $productRelationResponseTransfer->getProductRelation();

        $this->debug(sprintf(
            'Inserted Product Relation: %d',
            $productRelationTransfer->getIdProductRelation()
        ));

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productRelationTransfer): void {
            $this->cleanupProductRelation($productRelationTransfer);
        });

        return $productRelationTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductRelationTransfer $productRelationTransfer
     *
     * @return void
     */
    private function cleanupProductRelation(ProductRelationTransfer $productRelationTransfer): void
    {
        $idProductRelation = $productRelationTransfer->getIdProductRelation();

        $this->debug(sprintf('Deleting Product Relation: %d', $idProductRelation));

        $this->getProductRelationFacade()->deleteProductRelation($idProductRelation);
    }

    /**
     * @param string $productAbstractSku
     * @param int $idProductAbstractRelated
     * @param string $productRelationKey
     * @param string $productRelationType
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    protected function createProductRelationTransfer(
        string $productAbstractSku,
        int $idProductAbstractRelated,
        string $productRelationKey,
        string $productRelationType,
        StoreRelationTransfer $storeRelationTransfer
    ): ProductRelationTransfer {
        $productRelationTransfer = new ProductRelationTransfer();
        $productRelationTransfer->setFkProductAbstract($idProductAbstractRelated);
        $productRelationTransfer->setProductRelationKey($productRelationKey);

        $ruleQuerySetTransfer = new PropelQueryBuilderRuleSetTransfer();
        $ruleQuerySetTransfer->setCondition('AND');
        $ruleQuerySetTransfer->addRules($this->createProductAbstractSkuRuleTransfer($productAbstractSku));

        $productRelationTransfer->setQuerySet($ruleQuerySetTransfer);
        $productRelationTransfer->setIsActive(true);

        $productRelationTypeTransfer = new ProductRelationTypeTransfer();
        $productRelationTypeTransfer->setKey($productRelationType);
        $productRelationTransfer->setProductRelationType($productRelationTypeTransfer);
        $productRelationTransfer->setStoreRelation($storeRelationTransfer);

        return $productRelationTransfer;
    }

    /**
     * @param string $skuValueForFilter
     *
     * @return \Generated\Shared\Transfer\PropelQueryBuilderRuleSetTransfer
     */
    protected function createProductAbstractSkuRuleTransfer(string $skuValueForFilter): PropelQueryBuilderRuleSetTransfer
    {
        $ruleQuerySetTransfer = new PropelQueryBuilderRuleSetTransfer();
        $ruleQuerySetTransfer->setId('spy_product_abstract');
        $ruleQuerySetTransfer->setField('spy_product_abstract.sku');
        $ruleQuerySetTransfer->setType('string');
        $ruleQuerySetTransfer->setInput('text');
        $ruleQuerySetTransfer->setOperator('equal');
        $ruleQuerySetTransfer->setValue($skuValueForFilter);

        return $ruleQuerySetTransfer;
    }

    /**
     * @return \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface
     */
    protected function getProductRelationFacade(): ProductRelationFacadeInterface
    {
        return $this->getLocator()->productRelation()->facade();
    }
}
