<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Storage\Provider;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\AbstractClientProvider;

/**
 * @deprecated Not used anymore.
 */
abstract class AbstractKvProvider extends AbstractClientProvider
{

    const METHOD_PREFIX = 'createClient';
    const KV_ADAPTER_REDIS = 'redis';

    /**
     * @var string
     */
    protected $clientType;

    /**
     * @return mixed
     */
    protected function createZedClient()
    {
        $kvName = Config::get(ApplicationConstants::STORAGE_KV_SOURCE);
        $kvConfig = $this->getConfigByKvName($kvName);
        $methodName = $this->createMethodName($kvName);

        return new $methodName($kvConfig);
    }

    /**
     * @param string $kvName
     *
     * @return string
     */
    protected function createMethodName($kvName)
    {
        return ucfirst($kvName) . $this->clientType;
    }

    /**
     * @param string $kvName
     *
     * @throws \ErrorException
     * @throws \Exception
     *
     * @return array
     */
    public function getConfigByKvName($kvName)
    {
        switch ($kvName) {
            case self::KV_ADAPTER_REDIS:
                return $this->getConnectionParameters();
        }
        throw new \ErrorException('Missing implementation for adapter ' . $kvName);
    }

    /**
     * @return array
     */
    protected function getConnectionParameters()
    {
        $config = [
            'protocol' => Config::get(SessionConstants::YVES_SESSION_REDIS_PROTOCOL),
            'port' => Config::get(SessionConstants::YVES_SESSION_REDIS_PORT),
            'host' => Config::get(SessionConstants::YVES_SESSION_REDIS_HOST),
        ];

        if (Config::hasKey(SessionConstants::YVES_SESSION_REDIS_PASSWORD)) {
            $config['password'] = Config::get(SessionConstants::YVES_SESSION_REDIS_PASSWORD);
        }

        $config['persistent'] = false;
        if (Config::hasKey(SessionConstants::YVES_SESSION_PERSISTENT_CONNECTION)) {
            $config['persistent'] = (bool)Config::get(SessionConstants::YVES_SESSION_PERSISTENT_CONNECTION);
        }

        return $config;
    }

}
