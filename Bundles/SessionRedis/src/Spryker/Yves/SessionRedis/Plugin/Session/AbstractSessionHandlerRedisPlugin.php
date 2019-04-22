<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionRedis\Plugin\Session;

use Spryker\Shared\SessionExtension\Dependency\Plugin\SessionHandlerPluginInterface;
use Spryker\Shared\SessionRedis\Handler\SessionHandlerInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\SessionRedis\SessionRedisFactory getFactory()
 */
abstract class AbstractSessionHandlerRedisPlugin extends AbstractPlugin implements SessionHandlerPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    abstract public function getSessionHandlerName(): string;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return bool
     */
    public function close(): bool
    {
        return $this->getSessionHandler()->close();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sessionId
     *
     * @return bool
     */
    public function destroy($sessionId): bool
    {
        return $this->getSessionHandler()->destroy($sessionId);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $maxLifetime
     *
     * @return bool
     */
    public function gc($maxLifetime): bool
    {
        return $this->getSessionHandler()->gc($maxLifetime);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $savePath
     * @param string $name
     *
     * @return bool
     */
    public function open($savePath, $name): bool
    {
        return $this->getSessionHandler()->open($savePath, $name);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sessionId
     *
     * @return string
     */
    public function read($sessionId): string
    {
        return $this->getSessionHandler()->read($sessionId);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sessionId
     * @param string $sessionData

     * @return bool
     */
    public function write($sessionId, $sessionData): bool
    {
        return $this->getSessionHandler()->write($sessionId, $sessionData);
    }

    /**
     * @return \Spryker\Shared\SessionRedis\Handler\SessionHandlerInterface
     */
    abstract protected function getSessionHandler(): SessionHandlerInterface;
}
