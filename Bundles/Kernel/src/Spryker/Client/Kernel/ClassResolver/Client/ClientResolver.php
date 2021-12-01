<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Kernel\ClassResolver\Client;

use Spryker\Client\Kernel\ClassResolver\AbstractClassResolver;

class ClientResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'ClientClient';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Client\Kernel\ClassResolver\Client\ClientNotFoundException
     *
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Client\Kernel\AbstractClient|null $resolved */
        $resolved = $this->doResolve($callerClass);
        if ($resolved === null) {
            throw new ClientNotFoundException($this->getClassInfo());
        }

        return $resolved;
    }
}
