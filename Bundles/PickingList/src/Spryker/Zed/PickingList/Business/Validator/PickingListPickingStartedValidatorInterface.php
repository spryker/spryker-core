<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Validator;

use Generated\Shared\Transfer\PickingStartedRequestTransfer;
use Generated\Shared\Transfer\PickingStartedResponseTransfer;

interface PickingListPickingStartedValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingStartedRequestTransfer $pickingStartedRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingStartedResponseTransfer
     */
    public function isPickingStarted(
        PickingStartedRequestTransfer $pickingStartedRequestTransfer
    ): PickingStartedResponseTransfer;
}
