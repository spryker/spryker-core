<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Translation;

use Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer;

interface MinimumOrderValueGlossaryKeyGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer
     *
     * @return void
     */
    public function assignMessageGlossaryKey(GlobalMinimumOrderValueTransfer $globalMinimumOrderValueTransfer): void;
}
