<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Payment;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Payment\Form\Filter\PaymentFormFilter;

/**
 * @method \Spryker\Client\Payment\PaymentClientInterface getClient()
 */
class PaymentFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Payment\Form\Filter\PaymentFormFilterInterface
     */
    public function createPaymentMethodFormFilter()
    {
        return new PaymentFormFilter(
            $this->getClient()
        );
    }
}
