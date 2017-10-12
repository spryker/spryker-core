<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\ZedRequest\ZedRequestDependencyProvider;

/**
 * @method \Spryker\Zed\ZedRequest\ZedRequestConfig getConfig()
 */
class ZedRequestCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ZedRequest\Dependency\Facade\ZedRequestToMessengerInterface
     */
    public function getMessengerFacade()
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::FACADE_MESSENGER);
    }

    /**
     * @return \Spryker\Zed\ZedRequest\Dependency\Facade\ZedRequestToStoreInterface
     */
    public function getStore()
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::STORE);
    }
}
