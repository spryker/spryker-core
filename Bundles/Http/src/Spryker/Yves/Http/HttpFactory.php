<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Http;

use Spryker\Yves\Http\Dependency\Client\HttpToLocaleClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class HttpFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Http\Dependency\Client\HttpToLocaleClientInterface
     */
    public function getLocaleClient(): HttpToLocaleClientInterface
    {
        return $this->getProvidedDependency(HttpDependencyProvider::CLIENT_LOCALE);
    }
}
