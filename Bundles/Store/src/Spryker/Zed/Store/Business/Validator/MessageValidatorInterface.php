<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Validator;

use Generated\Shared\Transfer\MessageValidationResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface MessageValidatorInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $messageTransfer
     *
     * @return \Generated\Shared\Transfer\MessageValidationResponseTransfer
     */
    public function validate(TransferInterface $messageTransfer): MessageValidationResponseTransfer;
}
