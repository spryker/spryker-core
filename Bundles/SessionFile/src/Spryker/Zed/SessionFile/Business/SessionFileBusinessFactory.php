<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionFile\Business;

use Spryker\Shared\SessionFile\Handler\SessionAccountHandlerFileInterface;
use Spryker\Shared\SessionFile\Hasher\BcryptHasher;
use Spryker\Shared\SessionFile\Hasher\HasherInterface;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SessionFile\Communication\Handler\SessionUserHandlerFile;

/**
 * @method \Spryker\Zed\SessionFile\SessionFileConfig getConfig()
 */
class SessionFileBusinessFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\SessionFile\Handler\SessionAccountHandlerFileInterface
     */
    public function createSessionUserFileHandler(): SessionAccountHandlerFileInterface
    {
        return new SessionUserHandlerFile(
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
