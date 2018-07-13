<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Persistence;

use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;

interface MinimumOrderValueRepositoryInterface
{
    /**
     * @param string $minimumOrderValueTypeName
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer
     */
    public function findMinimumOrderValueTypeByName(
        string $minimumOrderValueTypeName
    ): MinimumOrderValueTypeTransfer;
}
