<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\Formatter;

class ProductCollectionFormatter implements ProductCollectionFormatterInterface
{
    protected const KEY_ID = 'id';
    protected const KEY_TEXT = 'text';
    protected const FORMAT_LABEL = '%s (SKU: %s)';

    /**
     * @param array $productAbstractArray
     *
     * @return array
     */
    public function formatArray(array $productAbstractArray): array
    {
        $formatedArray = [];
        foreach ($productAbstractArray as $sku => $name) {
            $formatedArray[] = [
                static::KEY_ID => $sku,
                static::KEY_TEXT => $this->createLable($name, $sku),
            ];
        }

        return $formatedArray;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer[] $productAbstractTransfers
     *
     * @return array
     */
    public function formatTransfers(array $productAbstractTransfers): array
    {
        $formatedArray = [];
        foreach ($productAbstractTransfers as $productAbstractTransfer) {
            $label = $this->createLable($productAbstractTransfer->getName(), $productAbstractTransfer->getSku());
            $formatedArray[$label] = $productAbstractTransfer->getSku();
        }

        return $formatedArray;
    }

    /**
     * @param string $name
     * @param string $sku
     *
     * @return string
     */
    protected function createLable(string $name, string $sku): string
    {
        return sprintf(static::FORMAT_LABEL, $name, $sku);
    }
}
