<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
