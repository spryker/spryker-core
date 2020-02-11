<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Model;

use SessionHandlerInterface;
use Spryker\Shared\Session\Model\SessionStorage\SessionStorageHandlerPoolInterface;
use Spryker\Shared\Session\Model\SessionStorage\SessionStorageOptionsInterface;

class SessionStorage implements SessionStorageInterface
{
    /**
     * @var \Spryker\Shared\Session\Model\SessionStorage\SessionStorageOptionsInterface
     */
    protected $sessionStorageOptions;

    /**
     * @var \Spryker\Shared\Session\Model\SessionStorage\SessionStorageHandlerPoolInterface
     */
    protected $sessionStorageHandlerPool;

    /**
     * @var string
     */
    protected $configuredHandlerName;

    /**
     * @param \Spryker\Shared\Session\Model\SessionStorage\SessionStorageOptionsInterface $sessionStorageOptions
     * @param \Spryker\Shared\Session\Model\SessionStorage\SessionStorageHandlerPoolInterface $sessionStorageHandlerPool
     * @param string $configuredHandlerName
     */
    public function __construct(
        SessionStorageOptionsInterface $sessionStorageOptions,
        SessionStorageHandlerPoolInterface $sessionStorageHandlerPool,
        $configuredHandlerName
    ) {
        $this->sessionStorageOptions = $sessionStorageOptions;
        $this->sessionStorageHandlerPool = $sessionStorageHandlerPool;
        $this->configuredHandlerName = $configuredHandlerName;
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->sessionStorageOptions->getOptions();
    }

    /**
     * {@inheritDoc}
     *
     * @return \SessionHandlerInterface
     */
    public function getAndRegisterHandler()
    {
        $handler = $this->sessionStorageHandlerPool->getHandler($this->configuredHandlerName);
        $this->registerSaveHandler($handler);

        return $handler;
    }

    /**
     * @param \SessionHandlerInterface $handler
     *
     * @return void
     */
    protected function registerSaveHandler(SessionHandlerInterface $handler)
    {
        session_set_save_handler(
            [$handler, 'open'],
            [$handler, 'close'],
            [$handler, 'read'],
            [$handler, 'write'],
            [$handler, 'destroy'],
            [$handler, 'gc']
        );
    }
}
