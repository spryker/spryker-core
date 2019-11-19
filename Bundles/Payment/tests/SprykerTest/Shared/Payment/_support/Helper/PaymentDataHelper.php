<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Payment\Helper;

use Codeception\Module;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;

class PaymentDataHelper extends Module
{
    /**
     * @return void
     */
    public function ensurePaymentMethodTableIsEmpty(): void
    {
        SpyPaymentMethodQuery::create()->deleteAll();
    }
}
