<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentGui\Communication\Form\DataProvider;

class PaymentMethodFormDataProvider extends ViewPaymentMethodFormDataProvider
{
    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            static::OPTION_STORE_RELATION_DISABLED => false,
        ];
    }
}
