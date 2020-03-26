<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\PaymentMethodTransfer;

class ViewPaymentMethodFormDataProvider
{
    public const OPTION_STORE_RELATION_DISABLED = 'option_store_relation_disabled';

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function getData(PaymentMethodTransfer $paymentMethodTransfer): PaymentMethodTransfer
    {
        return $paymentMethodTransfer;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            static::OPTION_STORE_RELATION_DISABLED => true,
        ];
    }
}
