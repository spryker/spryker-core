<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Resolver;

class DynamicEntityErrorPathResolver implements DynamicEntityErrorPathResolverInterface
{
    /**
     * @var string
     */
    protected const FORMATTED_INDEX_PLACEHOLDER = '%s[%d]';

    /**
     * @var string
     */
    protected const RELATION_CHAIN_PLACEHOLDER = '%s.%s';

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
        $formattedIndex = $this->buildFormattedIndex($tableAlias, $index);

        if ($parentErrorPath === $formattedIndex) {
            return $parentErrorPath;
        }

        return ltrim($this->buildChainElement($formattedIndex, $parentErrorPath), static::CHAIN_DELIMITER);
    }

    /**
     * @param string $tableAlias
     * @param int $index
     *
     * @return string
     */
    protected function buildFormattedIndex(string $tableAlias, int $index): string
    {
        return sprintf(static::FORMATTED_INDEX_PLACEHOLDER, $tableAlias, $index);
    }

    /**
     * @param string $formattedIndex
     * @param string|null $parentErrorPath
     *
     * @return string
     */
    protected function buildChainElement(string $formattedIndex, ?string $parentErrorPath = null): string
    {
        return sprintf(static::RELATION_CHAIN_PLACEHOLDER, $parentErrorPath, $formattedIndex);
    }
}
