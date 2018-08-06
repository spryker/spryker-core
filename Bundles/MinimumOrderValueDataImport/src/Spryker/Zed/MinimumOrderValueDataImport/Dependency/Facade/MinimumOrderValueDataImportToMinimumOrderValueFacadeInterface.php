<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MinimumOrderValueDataImport\Dependency\Facade;

use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;

interface MinimumOrderValueDataImportToMinimumOrderValueFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer
     */
    public function setGlobalThreshold(
        GlobalMinimumOrderValueTransfer $minimumOrderValueTransfer
    ): GlobalMinimumOrderValueTransfer;
}
