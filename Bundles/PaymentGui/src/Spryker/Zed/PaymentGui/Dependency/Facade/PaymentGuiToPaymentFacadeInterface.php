<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentGui\Dependency\Facade;

use Generated\Shared\Transfer\PaymentMethodResponseTransfer;

interface PaymentGuiToPaymentFacadeInterface
{
    /**
     * @param int $idPaymentMethod
     *
     * @return \Generated\Shared\Transfer\PaymentMethodResponseTransfer
     */
    public function findPaymentMethodById(int $idPaymentMethod): PaymentMethodResponseTransfer;
}
