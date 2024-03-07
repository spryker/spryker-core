<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Persistence\Filter\Strategy;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface FilterStrategyInterface
{
    /**
     * @param string|null $fieldValue
     *
     * @return bool
     */
    public function isApplicable(?string $fieldValue): bool;

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param string $fieldConditionName
     * @param string|null $fieldValue
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function applyConditionToQuery(
        ModelCriteria $query,
        string $fieldConditionName,
        ?string $fieldValue
    ): ModelCriteria;
}
