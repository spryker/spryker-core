<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication;

use Spryker\Shared\Application\EventListener\KernelLogListener;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Symfony\Component\HttpKernel\EventListener\SaveSessionListener;

/**
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 */
class ApplicationCommunicationFactory extends AbstractCommunicationFactory
{
    use LoggerTrait;

    /**
     * @return \Spryker\Shared\Application\EventListener\KernelLogListener
     */
    public function createKernelLogListener()
    {
        return new KernelLogListener(
            $this->getLogger()
        );
    }

    /**
     * @return SaveSessionListener
     */
    public function createSaveSessionEventSubscriber() : SaveSessionListener
    {
        return new SaveSessionListener();
    }
}
