<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Braintree;

use Spryker\Client\Braintree\Session\BraintreeSession;
use Spryker\Client\Braintree\Zed\BraintreeStub;
use Spryker\Client\Kernel\AbstractFactory;

class BraintreeFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\Braintree\Zed\BraintreeStubInterface
     */
    public function createBraintreeStub()
    {
        return new BraintreeStub($this->createZedRequestClient());
    }

}
