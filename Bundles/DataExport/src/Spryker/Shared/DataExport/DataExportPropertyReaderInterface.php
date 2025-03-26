<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\DataExport;

interface DataExportPropertyReaderInterface
{
    /**
     * @param array $data
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public static function get(array $data, string $key, mixed $default = null): mixed;

    /**
     * @param mixed $value
     * @param array<string> $keys
     * @param mixed $default
     * @param array $rootValue
     *
     * @return mixed
     */
    public static function getByKeys(mixed $value, array $keys, mixed $default, array $rootValue): mixed;
}
