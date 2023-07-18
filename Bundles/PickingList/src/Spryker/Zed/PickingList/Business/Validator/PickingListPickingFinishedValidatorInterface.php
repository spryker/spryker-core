<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Validator;

use Generated\Shared\Transfer\PickingFinishedRequestTransfer;
use Generated\Shared\Transfer\PickingFinishedResponseTransfer;

interface PickingListPickingFinishedValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingFinishedRequestTransfer $pickingFinishedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingFinishedResponseTransfer
     */
    public function isPickingFinished(
        PickingFinishedRequestTransfer $pickingFinishedRequestTransfer
    ): PickingFinishedResponseTransfer;
}
