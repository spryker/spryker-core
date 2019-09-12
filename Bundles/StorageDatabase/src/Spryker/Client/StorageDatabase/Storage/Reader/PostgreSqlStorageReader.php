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

    /**
     * @param string $keyPlaceholder
     *
     * @return string
     */
    protected function buildKeyEqualsValuePredicateFragment(string $keyPlaceholder): string
    {
        return sprintf('%s = %s', static::FIELD_KEY, $keyPlaceholder);
    }

    /**
     * @param string[] $keyPlaceholders
     *
     * @return string
     */
    protected function buildKeyInValuesPredicateFragment(array $keyPlaceholders): string
    {
        $keyInCriterion = implode(',', $keyPlaceholders);

        return sprintf('%s IN (%s)', static::FIELD_KEY, $keyInCriterion);
    }
}
