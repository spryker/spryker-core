<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Composer;

interface ComposerJsonUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer[] $moduleTransferCollection
     * @param bool $dryRun
     *
     * @return array
     */
    public function update(array $moduleTransferCollection, $dryRun = false);
}
