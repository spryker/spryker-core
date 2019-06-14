<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\StorageDatabase\Storage\Reader;

class PostgreSqlStorageReader extends AbstractStorageReader
{
    /**
     * @param string $keyPlaceholder
     *
     * @return string
     */
    protected function buildValueInAliasKeysPredicateFragment(string $keyPlaceholder): string
    {
        return sprintf('%s::JSONB @> %s', static::FIELD_ALIAS_KEYS, $keyPlaceholder);
    }
}
