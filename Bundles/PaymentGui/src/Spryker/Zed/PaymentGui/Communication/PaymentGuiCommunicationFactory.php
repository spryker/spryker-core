<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentGui\Communication;

use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\PaymentGui\Communication\Table\PaymentMethodTable;
use Spryker\Zed\PaymentGui\PaymentGuiDependencyProvider;

class PaymentGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\PaymentGui\Communication\Table\PaymentMethodTable
     */
    public function createPaymentMethodTable(): PaymentMethodTable
    {
        return new PaymentMethodTable($this->getPaymentMethodQuery());
    }

    /**
     * @return \Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery
     */
    public function getPaymentMethodQuery(): SpyPaymentMethodQuery
    {
        return $this->getProvidedDependency(PaymentGuiDependencyProvider::PROPEL_QUERY_PAYMENT_METHOD);
    }
}
