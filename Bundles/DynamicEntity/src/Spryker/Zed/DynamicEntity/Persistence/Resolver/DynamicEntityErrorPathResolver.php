<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Resolver;

class DynamicEntityErrorPathResolver implements DynamicEntityErrorPathResolverInterface
{
    /**
     * @var string
     */
    protected const FORMATTED_INDEX_PLACEHOLDER = '%s[%d]';

    /**
     * @var string
     */
    protected const DYNAMIC_ENTITY_CHAIN_PLACEHOLDER = '%s.%s';

    /**
     * @var string
     */
    protected const CHAIN_DELIMITER = '.';

    /**
     * @param int $index
     * @param string $tableAlias
     * @param string|null $parentErrorPath
     *
     * @return string
     */
    public function getErrorPath(int $index, string $tableAlias, ?string $parentErrorPath = null): string
    {
        $formattedIndex = sprintf(static::FORMATTED_INDEX_PLACEHOLDER, $tableAlias, $index);

        if ($parentErrorPath === $formattedIndex) {
            return $parentErrorPath;
        }

        return ltrim(sprintf(static::DYNAMIC_ENTITY_CHAIN_PLACEHOLDER, $parentErrorPath, $formattedIndex), static::CHAIN_DELIMITER);
    }
}
