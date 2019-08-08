<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence;

use Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\Payment\Persistence\PaymentPersistenceFactory getFactory()
 */
class PaymentEntityManager extends AbstractEntityManager implements PaymentEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer $salesPaymentMethodTypeTransfer
     *
     * @return void
     */
    public function saveSalesPaymentMethodTypeByPaymentProviderAndMethod(
        SalesPaymentMethodTypeTransfer $salesPaymentMethodTypeTransfer
    ): void {
        $salesPaymentMethodTypeEntity = $this->getFactory()
            ->createSalesPaymentMethodTypeQuery()
            ->filterByPaymentProvider($salesPaymentMethodTypeTransfer->getPaymentProvider()->getName())
            ->filterByPaymentMethod($salesPaymentMethodTypeTransfer->getPaymentMethod()->getMethodName())
            ->findOneOrCreate();

        $salesPaymentMethodTypeEntity->save();
    }
}
