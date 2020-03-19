<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantSwitcher;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantSwitcher\Dependency\Client\MerchantSwitcherToZedRequestClientInterface;
use Spryker\Client\MerchantSwitcher\Zed\MerchantSwitcherStub;
use Spryker\Client\MerchantSwitcher\Zed\MerchantSwitcherStubInterface;

class MerchantSwitcherFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantSwitcher\Zed\MerchantSwitcherStubInterface
     */
    public function createMerchantSwitcherStub(): MerchantSwitcherStubInterface
    {
        return new MerchantSwitcherStub(
            $this->getZedRequestClient()
        );
    }

    /**
     * @return \Spryker\Client\MerchantSwitcher\Dependency\Client\MerchantSwitcherToZedRequestClientInterface
     */
    public function getZedRequestClient(): MerchantSwitcherToZedRequestClientInterface
    {
        return $this->getProvidedDependency(MerchantSwitcherDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
