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
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $destination
     * @param int $redisPort
     *
     * @return bool
     */
    public function export(string $destination, int $redisPort): bool
    {
        return $this->getFactory()->createRedisExporter()->export($destination, $redisPort);
    }

    /**
     * {@inheritdoc}
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
