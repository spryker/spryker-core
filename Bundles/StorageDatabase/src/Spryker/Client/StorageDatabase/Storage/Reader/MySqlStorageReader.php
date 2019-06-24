<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\Storage\Reader;

class MySqlStorageReader extends AbstractStorageReader
{
    /**
     * @param string $keyPlaceholder
     *
     * @return string
     */
    protected function buildValueInAliasKeysPredicateFragment(string $keyPlaceholder): string
    {
        return sprintf('JSON_CONTAINS(%s, \'":%s"\')', static::FIELD_ALIAS_KEYS, $keyPlaceholder);
    }
}
