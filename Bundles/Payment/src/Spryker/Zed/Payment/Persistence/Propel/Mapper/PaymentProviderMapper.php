<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PaymentProviderTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentProvider;

class PaymentProviderMapper
{
    /**
     * @param \Orm\Zed\Payment\Persistence\SpyPaymentProvider $paymentProviderEntity
     * @param \Generated\Shared\Transfer\PaymentProviderTransfer $paymentProviderTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer
     */
    public function mapPaymentProviderEntityToPaymentProviderTransfer(
        SpyPaymentProvider $paymentProviderEntity,
        PaymentProviderTransfer $paymentProviderTransfer
    ): PaymentProviderTransfer {
        return $paymentProviderTransfer->fromArray($paymentProviderEntity->toArray(), true);
    }
}
