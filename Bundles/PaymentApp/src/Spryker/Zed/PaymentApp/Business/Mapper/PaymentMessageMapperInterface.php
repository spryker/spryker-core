<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\PaymentApp\Business\Mapper;

use Generated\Shared\Transfer\PaymentAppStatusUpdatedTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface PaymentMessageMapperInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $paymentAppMessageTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentAppStatusUpdatedTransfer
     */
    public function mapPaymentMessageTransferToPaymentAppStatusUpdatedTransfer(AbstractTransfer $paymentAppMessageTransfer): PaymentAppStatusUpdatedTransfer;
}
