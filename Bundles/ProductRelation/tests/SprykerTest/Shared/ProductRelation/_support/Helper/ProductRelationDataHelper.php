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
use Spryker\Shared\ProductRelation\ProductRelationTypes;
use Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class ProductRelationDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param string $skuAbstractProduct
     * @param int $idProductAbstract
     * @param string $productRelationType
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    public function haveProductRelation(
        string $skuAbstractProduct,
        int $idProductAbstract,
        string $productRelationType = ProductRelationTypes::TYPE_UP_SELLING
    ): ProductRelationTransfer {
        $productRelationFacade = $this->getProductRelationFacade();

        $productRelationTransfer = $this->createProductRelationTransfer($skuAbstractProduct, $idProductAbstract, $productRelationType);

        $idProductRelation = $productRelationFacade->createProductRelation($productRelationTransfer);

        $productRelationTransfer = $productRelationFacade->findProductRelationById($idProductRelation);

        $this->debug(sprintf(
            'Inserted Product Relation: %d',
            $productRelationTransfer->getIdProductRelation()
        ));

        $productRelationTransfer->setIsRebuildScheduled(true);
        $productRelationFacade->updateProductRelation($productRelationTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($productRelationTransfer) {
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
     * @param string $skuAbstractProduct
     * @param int $idProductAbstractRelated
     * @param string $productRelationType
     *
     * @return \Generated\Shared\Transfer\ProductRelationTransfer
     */
    protected function createProductRelationTransfer(
        string $skuAbstractProduct,
        int $idProductAbstractRelated,
        string $productRelationType
    ): ProductRelationTransfer {
        $productRelationTransfer = new ProductRelationTransfer();
        $productRelationTransfer->setFkProductAbstract($idProductAbstractRelated);

        $ruleQuerySetTransfer = new PropelQueryBuilderRuleSetTransfer();
        $ruleQuerySetTransfer->setCondition('AND');
        $ruleQuerySetTransfer->addRules($this->createProductAbstractSkuRuleTransfer($skuAbstractProduct));

        $productRelationTransfer->setQuerySet($ruleQuerySetTransfer);
        $productRelationTransfer->setIsActive(true);

        $productRelationTypeTransfer = new ProductRelationTypeTransfer();
        $productRelationTypeTransfer->setKey($productRelationType);
        $productRelationTransfer->setProductRelationType($productRelationTypeTransfer);

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
