<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Nopayment;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Nopayment\Handler\NopaymentHandler;

class NopaymentFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Nopayment\Handler\NopaymentHandler
     */
    public function createNopaymentHandler()
    {
        return new NopaymentHandler();
    }
}
