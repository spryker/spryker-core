<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataExport\Business\Mapper;

use Spryker\Shared\DataExport\DataExportPropertyReader;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class DataExportMapper implements DataExportMapperInterface
{
    /**
     * @var string
     */
    protected const ARRAY_KEY_WILDCARD = '*';

    /**
     * @var string
     */
    protected const ARRAY_KEY_ZERO = '0';

    /**
     * @param array<mixed> $rawData
     * @param list<string> $fieldMapping
     *
     * @return array<array<mixed>>
     */
    public function mapDatabaseDataToExportFields(array $rawData, array $fieldMapping): array
    {
        return array_map(fn ($item) => $this->mapItem($this->mapItemToArray($item), $fieldMapping), $rawData);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|array<mixed> $item
     *
     * @return array<mixed>
     */
    protected function mapItemToArray(AbstractTransfer|array $item): array
    {
        if (!is_array($item)) {
            return $item->toArray(true, true);
        }

        return $item;
    }

    /**
     * @param array $data
     * @param list<string> $fields
     *
     * @return array<mixed>
     */
    protected function mapItem(array $data, array $fields): array
    {
        $result = [];

        foreach ($fields as $pathKey) {
            $explodedKey = explode(':', $pathKey);
            $exportKey = $explodedKey[0];
            $pathKey = $explodedKey[1] ?? $explodedKey[0];

            if (str_contains($exportKey, '*')) {
                $result = array_merge($result, $this->mapDatabaseDataToExportFieldsInArrays($exportKey, $pathKey, $data));

                continue;
            }

            $result[$exportKey] = (new DataExportPropertyReader())->get($data, $pathKey);
        }

        return $result;
    }

    /**
     * @param list<string> $pathKeys
     *
     * @return array<mixed>
     */
    public function extractFieldHeaders(array $pathKeys): array
    {
        $result = [];

        foreach ($pathKeys as $pathKey) {
            $explodedKey = explode(':', $pathKey);

            $result[] = $explodedKey[0];
        }

        return $result;
    }

    /**
     * @param string $exportKey
     * @param string $pathKey
     * @param array $data
     *
     * @return array<mixed>
     */
    public function mapDatabaseDataToExportFieldsInArrays(string $exportKey, string $pathKey, array $data): array
    {
        $result = [];

        $prefix = mb_substr($exportKey, 0, mb_strpos($exportKey, static::ARRAY_KEY_WILDCARD) ?: 0);
        $postfix = mb_substr($exportKey, mb_strpos($exportKey, static::ARRAY_KEY_WILDCARD) + 1);

        $res = (new DataExportPropertyReader())->get($data, $pathKey);

        if (!is_array($res)) {
            $result[$prefix . static::ARRAY_KEY_ZERO . $postfix] = $res;

            return $result;
        }

        foreach ($res as $key => $item) {
            $result[$prefix . $key . $postfix] = $item;
        }

        return $result;
    }
}
