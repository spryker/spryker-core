<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\DataExport;

class DataExportPropertyReader implements DataExportPropertyReaderInterface
{
    /**
     * @var string
     */
    protected const FIELD_DELIMITER = '.';

    /**
     * @var string
     */
    protected const ARRAY_DELIMITER = '*';

    /**
     * @var string
     */
    protected const KEY_ROOT = '$';

    /**
     * @param array $data
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public static function get(array $data, string $key, mixed $default = null): mixed
    {
        $key = ltrim($key, static::KEY_ROOT . static::FIELD_DELIMITER);

        /** @phpstan-ignore-next-line argument.type */
        $keys = explode(static::FIELD_DELIMITER, $key);

        return static::getByKeys($data, $keys, $default, $data);
    }

    /**
     * @param mixed $value
     * @param array<string> $keys
     * @param mixed $default
     * @param array $rootValue
     *
     * @return mixed
     */
    public static function getByKeys(mixed $value, array $keys, mixed $default, array $rootValue): mixed
    {
        while (count($keys)) {
            $key = array_shift($keys);

            if (!is_array($value)) {
                return $default;
            }

            if ($key === static::ARRAY_DELIMITER) {
                return array_map(fn ($item) => static::getByKeys($item, $keys, $default, $rootValue), $value);
            }

            if ($key === static::KEY_ROOT) {
                return static::getByKeys($value, $keys, $default, $rootValue);
            }

            if (!array_key_exists($key, $value)) {
                return $default;
            }

            $value = $value[$key];
        }

        return $value;
    }
}
