<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SecurityBlocker\Delegator\SecurityBlockerStorageDelegator;
use Spryker\Client\SecurityBlocker\Delegator\SecurityBlockerStorageDelegatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class SecurityBlockerFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SecurityBlocker\Delegator\SecurityBlockerStorageDelegatorInterface
     */
    public function createSecurityBlockerStorageDelegator(): SecurityBlockerStorageDelegatorInterface
    {
        return new SecurityBlockerStorageDelegator();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(SecurityBlockerDependencyProvider::SERVICE_REQUEST_STACK);
    }
}
