<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlock\Business\Validator;

use Generated\Shared\Transfer\ValidationResponseTransfer;

interface CmsSlotBlockValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsSlotBlockTransfer[] $cmsSlotBlockTransfers
     *
     * @return \Generated\Shared\Transfer\ValidationResponseTransfer
     */
    public function getIsCmsSlotBlockListValid(array $cmsSlotBlockTransfers): ValidationResponseTransfer;
}
