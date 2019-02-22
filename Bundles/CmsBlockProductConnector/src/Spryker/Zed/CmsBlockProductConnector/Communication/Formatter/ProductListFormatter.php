<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\Formatter;

class ProductListFormatter implements ProductListFormatterInterface
{
    protected const KEY_ID = 'id';
    protected const KEY_TEXT = 'text';
    protected const TEXT_FORMAT = '%s (SKU: %s)';

    /**
     * @param array $suggestData
     *
     * @return array
     */
    public function prepareData(array $suggestData): array
    {
        $preparedSuggestData = [];
        foreach ($suggestData as $sku => $name) {
            $preparedSuggestData[] = [
                static::KEY_ID => $sku,
                static::KEY_TEXT => sprintf(static::TEXT_FORMAT, $name, $sku),
            ];
        }

        return $preparedSuggestData;
    }
}
