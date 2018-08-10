<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Country;

use Spryker\Client\Country\Zed\CountryStub;
use Spryker\Client\Country\Zed\CountryStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CountryFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Country\Zed\CountryStubInterface
     */
    public function createZedCountryStub(): CountryStubInterface
    {
        return new CountryStub($this->getProvidedDependency(CountryDependencyProvider::CLIENT_ZED_REQUEST));
    }
}
