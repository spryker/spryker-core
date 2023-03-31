<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ZedRequest\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ZedRequest\Business\Model\Repeater;
use Spryker\Zed\ZedRequest\Business\Reader\ZedRequestReader;
use Spryker\Zed\ZedRequest\Business\Reader\ZedRequestReaderInterface;
use Spryker\Zed\ZedRequest\ZedRequestDependencyProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @method \Spryker\Zed\ZedRequest\ZedRequestConfig getConfig()
 */
class ZedRequestBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ZedRequest\Business\Model\Repeater
     */
    public function createRepeater()
    {
        return new Repeater();
    }

    /**
     * @return \Spryker\Zed\ZedRequest\Business\Reader\ZedRequestReaderInterface
     */
    public function createZedRequestReader(): ZedRequestReaderInterface
    {
        return new ZedRequestReader($this->getCurrentRequest());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getCurrentRequest(): Request
    {
        return $this->getRequestStack()->getCurrentRequest();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(ZedRequestDependencyProvider::SERVICE_REQUEST_STACK);
    }
}
