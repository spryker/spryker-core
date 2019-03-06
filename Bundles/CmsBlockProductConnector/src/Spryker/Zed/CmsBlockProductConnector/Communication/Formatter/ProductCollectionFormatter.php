<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Communication\Formatter;

class ProductCollectionFormatter implements ProductCollectionFormatterInterface
{
    protected const KEY_SKU = 'sku';
    protected const KEY_NAME = 'name';

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
                static::KEY_SKU => $sku,
                static::KEY_NAME => $name,
            ];
        }

        return $preparedSuggestData;
    }
}
