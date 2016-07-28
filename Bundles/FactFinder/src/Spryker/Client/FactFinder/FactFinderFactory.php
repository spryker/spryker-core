<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\FactFinder;

use Spryker\Client\Cart\Session\QuoteSession;
use Spryker\Client\FactFinder\Zed\FactFinderStub;
use Spryker\Client\Kernel\AbstractFactory;

class FactFinderFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\FactFinder\Zed\FactFinderStubInterface
     */
    public function createZedFactFinderStub()
    {
        return new FactFinderStub(
            $this->getProvidedDependency(FactFinderDependencyProvider::SERVICE_ZED)
        );
    }

    /**
     * @return \Spryker\Client\Cart\Session\QuoteSessionInterface
     */
    public function createSession()
    {
        return new QuoteSession($this->getSessionClient());
    }

}
