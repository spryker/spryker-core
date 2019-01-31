<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\QueryDataResolver;

interface QueryDataMapperInterface
{
    /**
     * @param string $key
     *
     * @return string
     */
    public function map(string $key): string;

    /**
     * @param string[] $keys
     *
     * @return array
     */
    public function mapMany(array $keys): array;
}
