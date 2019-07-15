<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Session\Model\SessionStorage;

use SessionHandlerInterface;
use Spryker\Shared\Session\Exception\SessionHandlerNotFoundInSessionHandlerPoolException;

class SessionStorageHandlerPool implements SessionStorageHandlerPoolInterface
{
    /**
     * @var \SessionHandlerInterface[]
     */
    protected $sessionHandler = [];

    /**
     * @param \Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface[] $sessionHandlerProviderPlugins
     */
    public function __construct(array $sessionHandlerProviderPlugins = [])
    {
        $this->setupSessionHandlersFromPlugins($sessionHandlerProviderPlugins);
    }

    /**
     * @deprecated Will be removed with next major release.
     *
     * @param \SessionHandlerInterface $sessionHandler
     * @param string $sessionHandlerName
     *
     * @return $this
     */
    public function addHandler(SessionHandlerInterface $sessionHandler, $sessionHandlerName)
    {
        if (!isset($this->sessionHandler[$sessionHandlerName])) {
            $this->sessionHandler[$sessionHandlerName] = $sessionHandler;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $sessionHandlerName
     *
     * @throws \Spryker\Shared\Session\Exception\SessionHandlerNotFoundInSessionHandlerPoolException
     *
     * @return \SessionHandlerInterface
     */
    public function getHandler($sessionHandlerName)
    {
        if (isset($this->sessionHandler[$sessionHandlerName])) {
            return $this->sessionHandler[$sessionHandlerName];
        }

        $message = sprintf(
            'The requested session handler "%s" could not be found in the session handler pool. Check if you named it correctly and if the handler is added to the %s.',
            $sessionHandlerName,
            SessionStorageHandlerPoolInterface::class
        );

        throw new SessionHandlerNotFoundInSessionHandlerPoolException($message);
    }

    /**
     * @param \Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerProviderPluginInterface[] $sessionHandlerProviderPlugins
     *
     * @return void
     */
    protected function setupSessionHandlersFromPlugins(array $sessionHandlerProviderPlugins): void
    {
        foreach ($sessionHandlerProviderPlugins as $sessionHandlerProviderPlugin) {
            $this->sessionHandler[$sessionHandlerProviderPlugin->getSessionHandlerName()] = $sessionHandlerProviderPlugin->getSessionHandler();
        }
    }
}
