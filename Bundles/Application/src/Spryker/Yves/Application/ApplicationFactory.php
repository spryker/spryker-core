<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application;

use Spryker\Shared\Application\EventListener\KernelLogListener;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Application\Plugin\Provider\ExceptionService\DefaultExceptionHandler;
use Spryker\Yves\Application\Plugin\Provider\ExceptionService\ExceptionHandlerDispatcher;
use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Yves\Application\ApplicationConfig getConfig()
 */
class ApplicationFactory extends AbstractFactory
{
    use LoggerTrait;

    /**
     * @return \Spryker\Yves\Application\Plugin\Provider\ExceptionService\ExceptionHandlerDispatcher
     */
    public function createExceptionHandlerDispatcher()
    {
        return new ExceptionHandlerDispatcher($this->createExceptionHandlers());
    }

    /**
     * @return \Spryker\Yves\Application\Plugin\Provider\ExceptionService\ExceptionHandlerInterface[]
     */
    public function createExceptionHandlers()
    {
        return [
            Response::HTTP_NOT_FOUND => new DefaultExceptionHandler(),
        ];
    }

    /**
     * @return \Spryker\Shared\Application\EventListener\KernelLogListener
     */
    public function createKernelLogListener()
    {
        return new KernelLogListener($this->getLogger());
    }
}
