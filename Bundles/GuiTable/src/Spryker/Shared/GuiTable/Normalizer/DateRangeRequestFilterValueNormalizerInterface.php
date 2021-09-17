<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GuiTable\Normalizer;

use Generated\Shared\Transfer\CriteriaRangeFilterTransfer;

interface DateRangeRequestFilterValueNormalizerInterface
{
    /**
     * @param array<int|string>|string|int|bool $value
     *
     * @return \Generated\Shared\Transfer\CriteriaRangeFilterTransfer|null
     */
    public function normalizeFilterValue($value): ?CriteriaRangeFilterTransfer;
}
