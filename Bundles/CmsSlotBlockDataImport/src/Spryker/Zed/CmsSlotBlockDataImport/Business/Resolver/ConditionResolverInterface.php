<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
