<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile;

class FormDataNormalizer implements FormDataNormalizerInterface
{
    /**
     * @var string
     */
    protected const FIELD_TOKEN = '_token';

    /**
     * @var string
     */
    protected const DATATABLE_PARAM_LENGTH = '_length';

    /**
     * @var string
     */
    protected const DATATABLE_PARAM_START = '_start';

    /**
     * @var string
     */
    protected const DATATABLE_PARAM_DRAW = '_draw';

    /**
     * @var string
     */
    protected const DATATABLE_PARAM_SEARCH = '_search';

    /**
     * @var string
     */
    protected const DATATABLE_PARAM_ORDER = '_order';

    /**
     * @var string
     */
    protected const DATATABLE_PARAM_COLUMNS = '_columns';

    /**
     * @var string
     */
    protected const DATATABLE_PARAM_TABLE_LENGTH = 'table_length';

    /**
     * @var string
     */
    protected const DATATABLE_PARAM_TABLE_PROCESSING = 'table_processing';

    /**
     * @var string
     */
    protected const DELIMITER_COMMA = ',';

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function cleanFormData(array $data): array
    {
        $excludePatterns = [
            static::DATATABLE_PARAM_LENGTH,
            static::DATATABLE_PARAM_START,
            static::DATATABLE_PARAM_DRAW,
            static::DATATABLE_PARAM_SEARCH,
            static::DATATABLE_PARAM_ORDER,
            static::DATATABLE_PARAM_COLUMNS,
            static::DATATABLE_PARAM_TABLE_LENGTH,
            static::DATATABLE_PARAM_TABLE_PROCESSING,
        ];

        foreach ($data as $key => $value) {
            if ($key === static::FIELD_TOKEN || strpos($key, static::FIELD_TOKEN) !== false) {
                continue;
            }

            foreach ($excludePatterns as $pattern) {
                if (strpos($key, $pattern) !== false) {
                    unset($data[$key]);

                    continue 2;
                }
            }

            if (is_array($value)) {
                $flattened = [];
                $this->flattenArray($value, $flattened);
                $data[$key] = array_filter($flattened, function ($item) {
                    return is_scalar($item) && (bool)$item;
                });
            }
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    public function normalizeFormData(array $data): array
    {
        $normalizedData = [];

        foreach ($data as $key => $value) {
            if ($key === static::FIELD_TOKEN || strpos($key, static::FIELD_TOKEN) !== false) {
                $normalizedData[$key] = $value;

                continue;
            }

            if (is_array($value)) {
                $flattened = [];
                $this->flattenArray($value, $flattened);
                $normalizedData[$key] = array_values(array_filter($flattened, function ($item) {
                    return is_scalar($item) && (bool)$item;
                }));
            } else {
                $normalizedData[$key] = $value;
            }
        }

        return $normalizedData;
    }

    /**
     * @param mixed $value
     *
     * @return array<int>
     */
    public function normalizeFormFieldArray($value): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        if (!is_array($value)) {
            if (is_numeric($value) && $value > 0) {
                return [(int)$value];
            }

            if (is_string($value) && static::DELIMITER_COMMA !== '' && strpos($value, static::DELIMITER_COMMA) !== false) {
                $parts = explode(static::DELIMITER_COMMA, $value);
                $result = [];
                foreach ($parts as $part) {
                    $trimmed = trim($part);
                    if (is_numeric($trimmed) && $trimmed > 0) {
                        $result[] = (int)$trimmed;
                    }
                }

                return $result;
            }

            return [];
        }

        $flattened = [];
        $this->flattenArray($value, $flattened);

        return array_map('intval', array_filter($flattened, function ($item) {
            return is_numeric($item) && $item > 0;
        }));
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<int, mixed>
     */
    public function flattenToIndexedArray(array $data): array
    {
        $result = [];
        $this->flattenArray($data, $result);

        $result = array_filter($result, function ($item) {
            return is_scalar($item) && (bool)$item;
        });

        return array_values(array_unique($result));
    }

    /**
     * @param array<mixed> $array
     * @param array<mixed> $result
     *
     * @return void
     */
    protected function flattenArray(array $array, array &$result): void
    {
        foreach ($array as $value) {
            if (is_array($value)) {
                $this->flattenArray($value, $result);
            } elseif (is_scalar($value)) {
                $result[] = $value;
            }
        }
    }
}
