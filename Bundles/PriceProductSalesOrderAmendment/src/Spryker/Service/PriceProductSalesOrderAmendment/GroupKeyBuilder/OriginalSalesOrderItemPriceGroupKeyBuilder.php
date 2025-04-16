<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductSalesOrderAmendment\GroupKeyBuilder;

use Generated\Shared\Transfer\ItemTransfer;

class OriginalSalesOrderItemPriceGroupKeyBuilder implements OriginalSalesOrderItemPriceGroupKeyBuilderInterface
{
    /**
     * @param list<\Spryker\Service\PriceProductSalesOrderAmendmentExtension\Dependency\Plugin\OriginalSalesOrderItemPriceGroupKeyExpanderPluginInterface> $originalSalesOrderItemPriceGroupKeyExpanderPlugins
     */
    public function __construct(protected array $originalSalesOrderItemPriceGroupKeyExpanderPlugins)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function buildOriginalSalesOrderItemPriceGroupKey(ItemTransfer $itemTransfer): string
    {
        $groupKey = $itemTransfer->getSkuOrFail();
        $groupKey = $this->executeOriginalSalesOrderItemPriceGroupKeyExpanderPlugins($groupKey, $itemTransfer);

        return $groupKey;
    }

    /**
     * @param string $groupKey
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function executeOriginalSalesOrderItemPriceGroupKeyExpanderPlugins(
        string $groupKey,
        ItemTransfer $itemTransfer
    ): string {
        foreach ($this->originalSalesOrderItemPriceGroupKeyExpanderPlugins as $originalSalesOrderItemPriceGroupKeyExpanderPlugin) {
            $groupKey = $originalSalesOrderItemPriceGroupKeyExpanderPlugin->expandGroupKey($groupKey, $itemTransfer);
        }

        return $groupKey;
    }
}
