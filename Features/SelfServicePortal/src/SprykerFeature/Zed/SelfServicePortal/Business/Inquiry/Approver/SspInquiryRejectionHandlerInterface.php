<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Inquiry\Approver;

use Generated\Shared\Transfer\StateMachineItemTransfer;

interface SspInquiryRejectionHandlerInterface
{
    public function handleRejection(StateMachineItemTransfer $stateMachineItemTransfer): void;
}
