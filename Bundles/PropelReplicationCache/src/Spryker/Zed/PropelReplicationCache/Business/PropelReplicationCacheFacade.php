<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelReplicationCache\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * phpcs:disable
 * @method \Spryker\Zed\PropelReplicationCache\Business\PropelReplicationCacheBusinessFactory getFactory()
 */
class PropelReplicationCacheFacade extends AbstractFacade implements PropelReplicationCacheFacadeInterface
{
    /**
     * @var \Spryker\Zed\PropelReplicationCache\Business\PropelReplicationCacheFacade|null Performance optimization
     */
    protected static $instance = null;

    /**
     * @return self
     */
    public static function getInstance(): self
    {
        if (static::$instance === null) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     * @param int|null $ttl
     *
     * @return void
     */
    public function setKey(string $key, ?int $ttl = null): void
    {
        $this->getFactory()->createReplicationCacheModel()->setKey($key, $ttl);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $key
     *
     * @return bool
     */
    public function hasKey(string $key): bool
    {
        return $this->getFactory()->createReplicationCacheModel()->hasKey($key);
    }
}
