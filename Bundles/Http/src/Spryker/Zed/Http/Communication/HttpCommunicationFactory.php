<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Http\Communication;

use Spryker\Zed\Http\Dependency\Facade\HttpToLocaleFacadeInterface;
use Spryker\Zed\Http\HttpDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class HttpCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Http\Dependency\Facade\HttpToLocaleFacadeInterface
     */
    public function getLocaleFacade(): HttpToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(HttpDependencyProvider::FACADE_LOCALE);
    }
}
