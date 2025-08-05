<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\Service\Resolver;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;

interface PaymentMethodResolverInterface
{
    public function resolvePaymentMethod(ItemTransfer $itemTransfer, PaymentTransfer $paymentTransfer): string;
}
