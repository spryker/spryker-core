<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantCommissionConnector\Business\CollectorRule;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\RuleEngineClauseTransfer;
use Spryker\Zed\ProductMerchantCommissionConnector\Business\Reader\ProductAttributeReaderInterface;
use Spryker\Zed\ProductMerchantCommissionConnector\Business\Reader\ProductReaderInterface;
use Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade\ProductMerchantCommissionConnectorToRuleEngineFacadeInterface;

class ProductAttributeCollectorRule implements ProductAttributeCollectorRuleInterface
{
    /**
     * @var \Spryker\Zed\ProductMerchantCommissionConnector\Business\Reader\ProductReaderInterface
     */
    protected ProductReaderInterface $productReader;

    /**
     * @var \Spryker\Zed\ProductMerchantCommissionConnector\Business\Reader\ProductAttributeReaderInterface
     */
    protected ProductAttributeReaderInterface $productAttributeReader;

    /**
     * @var \Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade\ProductMerchantCommissionConnectorToRuleEngineFacadeInterface
     */
    protected ProductMerchantCommissionConnectorToRuleEngineFacadeInterface $ruleEngineFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantCommissionConnector\Business\Reader\ProductReaderInterface $productReader
     * @param \Spryker\Zed\ProductMerchantCommissionConnector\Business\Reader\ProductAttributeReaderInterface $productAttributeReader
     * @param \Spryker\Zed\ProductMerchantCommissionConnector\Dependency\Facade\ProductMerchantCommissionConnectorToRuleEngineFacadeInterface $ruleEngineFacade
     */
    public function __construct(
        ProductReaderInterface $productReader,
        ProductAttributeReaderInterface $productAttributeReader,
        ProductMerchantCommissionConnectorToRuleEngineFacadeInterface $ruleEngineFacade
    ) {
        $this->productReader = $productReader;
        $this->productAttributeReader = $productAttributeReader;
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

        $productTransfersIndexedBySku = $this->productReader->getProductTransfersIndexedBySku(
            array_keys($merchantCommissionCalculationRequestItemsGroupedBySku),
        );

        $collectedItems = [];
        foreach ($productTransfersIndexedBySku as $sku => $productConcreteTransfer) {
            $productAttributes = $this->productAttributeReader->getCombinedConcreteAttributes($productConcreteTransfer);
            if (!$this->isSatisfiedBy($ruleEngineClauseTransfer, $productAttributes)) {
                continue;
            }

            $collectedItems[] = $merchantCommissionCalculationRequestItemsGroupedBySku[$sku];
        }

        return array_merge(...$collectedItems);
    }

    /**
     * @param \Generated\Shared\Transfer\RuleEngineClauseTransfer $ruleEngineClauseTransfer
     * @param array<string, string> $productAttributes
     *
     * @return bool
     */
    protected function isSatisfiedBy(
        RuleEngineClauseTransfer $ruleEngineClauseTransfer,
        array $productAttributes
    ): bool {
        foreach ($productAttributes as $attributeName => $attributeValue) {
            if ($ruleEngineClauseTransfer->getAttributeOrFail() !== $attributeName) {
                continue;
            }

            if ($this->ruleEngineFacade->compare($ruleEngineClauseTransfer, $attributeValue)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer> $merchantCommissionCalculationRequestItemTransfers
     *
     * @return array<string, list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>>
     */
    protected function getMerchantCommissionCalculationRequestItemsGroupedBySku(ArrayObject $merchantCommissionCalculationRequestItemTransfers): array
    {
        $groupedMerchantCommissionCalculationRequestItemTransfers = [];
        foreach ($merchantCommissionCalculationRequestItemTransfers as $merchantCommissionCalculationRequestItemTransfer) {
            $sku = $merchantCommissionCalculationRequestItemTransfer->getSkuOrFail();
            $groupedMerchantCommissionCalculationRequestItemTransfers[$sku][] = $merchantCommissionCalculationRequestItemTransfer;
        }

        return $groupedMerchantCommissionCalculationRequestItemTransfers;
    }
}
