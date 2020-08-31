<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\Resolver;

class AllConditionResolver implements ConditionResolverInterface
{
    protected const KEY_ALL = 'all';

    /**
     * @param string $conditionValue
     * @param array $conditionsArray
     *
     * @return array
     */
    public function getConditions(string $conditionValue, array $conditionsArray = []): array
    {
        if ($conditionValue === '') {
            return [];
        }

        if ($conditionValue === '1') {
            $conditionsArray[static::KEY_ALL] = true;

            return $conditionsArray;
        }

        $conditionsArray[static::KEY_ALL] = false;

        return $conditionsArray;
    }
}
