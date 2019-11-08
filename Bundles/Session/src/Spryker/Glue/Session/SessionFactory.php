<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Session;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\Session\Storage\MemorySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface;

class SessionFactory extends AbstractFactory
{
    /**
     * @return \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    public function createSession(): SessionInterface
    {
        return new Session($this->createMemorySessionStorage());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Storage\SessionStorageInterface
     */
    public function createMemorySessionStorage(): SessionStorageInterface
    {
        return new MemorySessionStorage();
    }
}
