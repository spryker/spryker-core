<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Payment;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Kernel\AbstractBundleConfig;

class PaymentConfig extends AbstractBundleConfig
{
   /**
    * @api
    *
    * @return bool
    */
    public function isDebugEnabled(): bool
    {
        return $this->get(ApplicationConstants::ENABLE_APPLICATION_DEBUG, false);
    }
}
