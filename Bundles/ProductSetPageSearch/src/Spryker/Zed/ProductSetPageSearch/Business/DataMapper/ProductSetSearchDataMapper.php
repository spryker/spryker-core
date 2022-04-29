<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch\Business\DataMapper;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\LocaleTransfer;

class ProductSetSearchDataMapper implements ProductSetSearchDataMapperInterface
{
    /**
     * @var string
     */
    protected const PRODUCT_SET_RESOURCE_NAME = 'product_set';

    /**
     * @var string
     */
    protected const KEY_STORE = 'store';

    /**
     * @var string
     */
    protected const KEY_WEIGHT = 'weight';

    /**
     * @var array<string>
     */
    protected const FILTERED_KEYS = [
        'locale',
        'store',
        'type',
    ];

    /**
     * @param array<string, mixed> $data
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function mapProductSetDataToSearchData(array $data, LocaleTransfer $localeTransfer): array
    {
        return [
            PageIndexMap::STORE => $data[static::KEY_STORE],
            PageIndexMap::TYPE => static::PRODUCT_SET_RESOURCE_NAME,
            PageIndexMap::LOCALE => $localeTransfer->getLocaleName(),
            PageIndexMap::SEARCH_RESULT_DATA => $this->getSearchResultData($data),
            PageIndexMap::INTEGER_SORT => $this->getIntegerSortData($data),
        ];
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array
     */
    protected function getIntegerSortData(array $data): array
    {
        return [
            static::KEY_WEIGHT => $data[static::KEY_WEIGHT],
        ];
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array
     */
    protected function getSearchResultData(array $data): array
    {
        $result = [];

        foreach ($data as $key => $value) {
            if ($value !== null && !in_array($key, static::FILTERED_KEYS)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
