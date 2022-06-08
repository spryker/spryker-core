<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Business\EntityIdentifierBuilder;

use Generated\Shared\Transfer\PaymentProviderTransfer;

class PaymentProviderEntityIdentifierBuilder implements PaymentProviderEntityIdentifierBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return string
     */
    public function buildEntityIdentifier(PaymentProviderTransfer $paymentProviderTransfer): string
    {
        return $paymentProviderTransfer->getPaymentProviderKeyOrFail();
    }
}
