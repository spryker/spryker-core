<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\SalesOrderAmendment\GroupKeyBuilder;

use Generated\Shared\Transfer\ItemTransfer;

class OriginalSalesOrderItemGroupKeyBuilder implements OriginalSalesOrderItemGroupKeyBuilderInterface
{
    /**
     * @param list<\Spryker\Service\SalesOrderAmendmentExtension\Dependency\Plugin\OriginalSalesOrderItemGroupKeyExpanderPluginInterface> $originalSalesOrderItemGroupKeyExpanderPlugins
     */
    public function __construct(protected array $originalSalesOrderItemGroupKeyExpanderPlugins)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function buildOriginalSalesOrderItemGroupKey(ItemTransfer $itemTransfer): string
    {
        $groupKey = $itemTransfer->getSkuOrFail();
        $groupKey = $this->executeOriginalSalesOrderItemGroupKeyExpanderPlugins($groupKey, $itemTransfer);

        return $groupKey;
    }

    /**
     * @param string $groupKey
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function executeOriginalSalesOrderItemGroupKeyExpanderPlugins(
        string $groupKey,
        ItemTransfer $itemTransfer
    ): string {
        foreach ($this->originalSalesOrderItemGroupKeyExpanderPlugins as $originalSalesOrderItemGroupKeyExpanderPlugin) {
            $groupKey = $originalSalesOrderItemGroupKeyExpanderPlugin->expandGroupKey($groupKey, $itemTransfer);
        }

        return $groupKey;
    }
}
