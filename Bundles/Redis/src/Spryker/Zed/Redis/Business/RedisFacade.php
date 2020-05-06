<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Redis\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Redis\Business\RedisBusinessFactory getFactory()
 */
class RedisFacade extends AbstractFacade implements RedisFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $destination
     * @param int $redisPort
     * @param string|null $redisHost
     *
     * @return bool
     */
    public function export(string $destination, int $redisPort, ?string $redisHost = null): bool
    {
        return $this->getFactory()->createRedisExporter()->export($destination, $redisPort, $redisHost);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $source
     * @param string $destination
     *
     * @return bool
     */
    public function import(string $source, string $destination): bool
    {
        return $this->getFactory()->createRedisImporter()->import($source, $destination);
    }
}
