<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SessionFile;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SessionFile\Handler\SessionCustomerHandlerFile;
use Spryker\Shared\SessionFile\Handler\SessionAccountHandlerFileInterface;
use Spryker\Shared\SessionFile\Hasher\BcryptHasher;
use Spryker\Shared\SessionFile\Hasher\HasherInterface;

/**
 * @method \Spryker\Client\SessionFile\SessionFileConfig getConfig()
 */
class SessionFileFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\SessionFile\Handler\SessionAccountHandlerFileInterface
     */
    public function createSessionCustomerFileHandler(): SessionAccountHandlerFileInterface
    {
        return new SessionCustomerHandlerFile(
            $this->createFileHasher(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Shared\SessionFile\Hasher\HasherInterface
     */
    protected function createFileHasher(): HasherInterface
    {
        return new BcryptHasher();
    }
}
