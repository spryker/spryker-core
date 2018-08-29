<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business\Translation;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;

interface MinimumOrderValueTranslationWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function saveLocalizedMessages(MinimumOrderValueTransfer $minimumOrderValueTransfer): MinimumOrderValueTransfer;
}
