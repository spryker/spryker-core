<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Storage\Provider;

use SprykerEngine\Shared\Kernel\AbstractClientProvider;
use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Application\ApplicationConstants;

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
    protected function createClient()
    {
        $kvName = Config::get(ApplicationConstants::STORAGE_KV_SOURCE);
        $kvConfig = $this->getConfigByKvName($kvName);
        $methodName = $this->createMethodName($kvName);

        return $this->factory->$methodName($kvConfig);
    }

    /**
     * @param string $kvName
     *
     * @return string
     */
    protected function createMethodName($kvName)
    {
        return self::METHOD_PREFIX . ucfirst($kvName) . $this->clientType;
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
                return [
                    'protocol' => Config::get(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_PROTOCOL),
                    'port' => Config::get(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_PORT),
                    'host' => Config::get(ApplicationConstants::YVES_STORAGE_SESSION_REDIS_HOST),
                ];
        }
        throw new \ErrorException('Missing implementation for adapter ' . $kvName);
    }

}
