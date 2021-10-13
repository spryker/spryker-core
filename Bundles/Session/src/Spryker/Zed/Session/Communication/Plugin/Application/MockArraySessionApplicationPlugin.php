<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;

/**
 * @method \Spryker\Zed\Session\SessionConfig getConfig()
 * @method \Spryker\Zed\Session\Communication\SessionCommunicationFactory getFactory()
 * @method \Spryker\Zed\Session\Business\SessionFacadeInterface getFacade()
 */
class MockArraySessionApplicationPlugin extends SessionApplicationPlugin
{
    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface
     */
    protected function createSessionStorage(ContainerInterface $container): SessionStorageInterface
    {
        return $this->getFactory()->createMockArraySessionStorage();
    }
}
