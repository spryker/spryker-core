<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryMerchantCommissionConnector\Business\CollectorRule;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\CategoryMerchantCommissionConnector\Business\Reader\CategoryReaderInterface;
use Spryker\Zed\CategoryMerchantCommissionConnector\Business\Reader\ProductCategoryReaderInterface;
use Spryker\Zed\CategoryMerchantCommissionConnector\Business\Reader\ProductReaderInterface;
use Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade\CategoryMerchantCommissionConnectorToRuleEngineFacadeInterface;

class CategoryCollectorRule implements CategoryCollectorRuleInterface
{
    /**
     * @var \Spryker\Zed\CategoryMerchantCommissionConnector\Business\Reader\ProductReaderInterface
     */
    protected ProductReaderInterface $productReader;

    /**
     * @var \Spryker\Zed\CategoryMerchantCommissionConnector\Business\Reader\ProductCategoryReaderInterface
     */
    protected ProductCategoryReaderInterface $productCategoryReader;

    /**
     * @var \Spryker\Zed\CategoryMerchantCommissionConnector\Business\Reader\CategoryReaderInterface
     */
    protected CategoryReaderInterface $categoryReader;

    /**
     * @var \Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade\CategoryMerchantCommissionConnectorToRuleEngineFacadeInterface
     */
    protected CategoryMerchantCommissionConnectorToRuleEngineFacadeInterface $ruleEngineFacade;

    /**
     * @param \Spryker\Zed\CategoryMerchantCommissionConnector\Business\Reader\ProductReaderInterface $productReader
     * @param \Spryker\Zed\CategoryMerchantCommissionConnector\Business\Reader\ProductCategoryReaderInterface $productCategoryReader
     * @param \Spryker\Zed\CategoryMerchantCommissionConnector\Business\Reader\CategoryReaderInterface $categoryReader
     * @param \Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade\CategoryMerchantCommissionConnectorToRuleEngineFacadeInterface $ruleEngineFacade
     */
    public function __construct(
        ProductReaderInterface $productReader,
        ProductCategoryReaderInterface $productCategoryReader,
        CategoryReaderInterface $categoryReader,
        CategoryMerchantCommissionConnectorToRuleEngineFacadeInterface $ruleEngineFacade
    ) {
        $this->productReader = $productReader;
        $this->productCategoryReader = $productCategoryReader;
        $this->categoryReader = $categoryReader;
        $this->ruleEngineFacade = $ruleEngineFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     *
     * @return list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>
     */
    public function collect(
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        RuleEngineClauseTransfer $ruleEngineClauseTransfer
    ): array {
        $merchantCommissionCalculationRequestItemsGroupedBySku = $this->getMerchantCommissionCalculationRequestItemsGroupedBySku(
            $merchantCommissionCalculationRequestTransfer->getItems(),
        );

        $productConcreteTransfersIndexedBySku = $this->productReader->getProductConcreteTransfersIndexedBySku(
            $merchantCommissionCalculationRequestItemsGroupedBySku,
        );
        $productCategoryTransfersGroupedBySku = $this->productCategoryReader->getProductCategoriesGroupedByProductConcreteSku(
            $productConcreteTransfersIndexedBySku,
        );
        $categoryKeysGroupedByIdCategoryNode = $this->categoryReader->getCategoryKeysGroupedByIdCategoryNode(
            $productCategoryTransfersGroupedBySku,
        );

        $collectedItems = [];
        foreach ($productConcreteTransfersIndexedBySku as $sku => $productConcreteTransfer) {
            $productCategoryTransfers = $productCategoryTransfersGroupedBySku[$sku] ?? [];
            if ($productCategoryTransfers === []) {
                continue;
            }

            $categoryKeys = $this->getCategoryKeysForProductCategories($productCategoryTransfers, $categoryKeysGroupedByIdCategoryNode);
            if ($this->isSatisfiedBy($ruleEngineClauseTransfer, $categoryKeys)) {
                $collectedItems[] = $merchantCommissionCalculationRequestItemsGroupedBySku[$sku];
            }
        }

        return array_merge(...$collectedItems);
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param list<string> $categoryKeys
     *
     * @return bool
     */
    protected function isSatisfiedBy(RuleEngineClauseTransfer $ruleEngineClauseTransfer, array $categoryKeys): bool
    {
        foreach ($categoryKeys as $categoryKey) {
            if ($this->ruleEngineFacade->compare($ruleEngineClauseTransfer, $categoryKey)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductCategoryTransfer> $productCategoryTransfers
     * @param array<int, list<string>> $categoryKeysGroupedByIdCategoryNode
     *
     * @return list<string>
     */
    protected function getCategoryKeysForProductCategories(array $productCategoryTransfers, array $categoryKeysGroupedByIdCategoryNode): array
    {
        $categoryKeys = [];
        foreach ($productCategoryTransfers as $productCategoryTransfer) {
            $idCategoryNode = $productCategoryTransfer->getCategoryOrFail()->getCategoryNodeOrFail()->getIdCategoryNodeOrFail();
            $categoryKeys[] = $categoryKeysGroupedByIdCategoryNode[$idCategoryNode]
                ?? [$productCategoryTransfer->getCategoryOrFail()->getCategoryKeyOrFail()];
        }

        return array_unique(array_merge(...$categoryKeys));
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer> $merchantCommissionCalculationRequestItemTransfers
     *
     * @return array<string, list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>>
     */
    protected function getMerchantCommissionCalculationRequestItemsGroupedBySku(
        ArrayObject $merchantCommissionCalculationRequestItemTransfers
    ): array {
        $indexedMerchantCommissionCalculationRequestItems = [];
        foreach ($merchantCommissionCalculationRequestItemTransfers as $merchantCommissionCalculationRequestItemTransfer) {
            $sku = $merchantCommissionCalculationRequestItemTransfer->getSkuOrFail();
            $indexedMerchantCommissionCalculationRequestItems[$sku][] = $merchantCommissionCalculationRequestItemTransfer;
        }

        return $indexedMerchantCommissionCalculationRequestItems;
    }
}
