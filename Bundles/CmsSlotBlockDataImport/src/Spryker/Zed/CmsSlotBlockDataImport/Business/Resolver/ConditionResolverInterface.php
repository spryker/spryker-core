<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver;

interface ConditionResolverInterface
{
    /**
     * @param string $conditionValue
     * @param array $conditionsArray
     *
     * @return array
     */
    public function getConditions(string $conditionValue, array $conditionsArray = []): array;
}
