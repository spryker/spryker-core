<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Ratepay;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Ratepay\Zed\RatepayStub;

class RatepayFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Ratepay\Zed\RatepayStubInterface
     */
    public function createRatepayStub()
    {
        return new RatepayStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient()
    {
        return $this->getProvidedDependency(RatepayDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
