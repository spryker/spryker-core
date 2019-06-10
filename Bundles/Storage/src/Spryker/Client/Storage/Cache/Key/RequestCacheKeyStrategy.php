<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Storage\Cache\Key;

use Spryker\Client\Storage\Dependency\Client\StorageToLocaleClientInterface;
use Spryker\Client\Storage\Dependency\Client\StorageToStoreClientInterface;
use Spryker\Client\Storage\StorageConfig;
use Symfony\Component\HttpFoundation\Request;

class RequestCacheKeyStrategy implements CacheKeyStrategyInterface
{
    protected const KEY_NAME_PREFIX = 'storage';
    protected const KEY_NAME_SEPARATOR = ':';

    /**
     * @var \Spryker\Client\Storage\Dependency\Client\StorageToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @var \Spryker\Client\Storage\Dependency\Client\StorageToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @var \Spryker\Client\Storage\StorageConfig
     */
    protected $config;

    /**
     * @param \Spryker\Client\Storage\Dependency\Client\StorageToStoreClientInterface $storeClient
     * @param \Spryker\Client\Storage\Dependency\Client\StorageToLocaleClientInterface $localeClient
     * @param \Spryker\Client\Storage\StorageConfig $config
     */
    public function __construct(
        StorageToStoreClientInterface $storeClient,
        StorageToLocaleClientInterface $localeClient,
        StorageConfig $config
    ) {
        $this->storeClient = $storeClient;
        $this->localeClient = $localeClient;
        $this->config = $config;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     *
     * @return string
     */
    public function generateCacheKey(?Request $request = null): string
    {
        if ($request) {
            $requestUri = $request->getRequestUri();
            $serverName = $request->server->get('SERVER_NAME');
            $getParameters = $request->query->all();
        } else {
            $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
            $serverName = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : null;
            $getParameters = $_GET;
        }

        if ($requestUri === null || $serverName === null) {
            return '';
        }

        $urlSegments = strtok($requestUri, '?');

        $getParametersKey = $this->generateGetParametersKey($getParameters);
        $cacheKey = $this->assembleCacheKey($urlSegments, $getParametersKey);

        return $cacheKey;
    }

    /**
     * @return string
     */
    public function getStrategyName(): string
    {
        return $this->config->getRequestCacheKeyGenerationStrategy();
    }

    /**
     * @param array $getParameters
     *
     * @return string
     */
    protected function generateGetParametersKey(array $getParameters): string
    {
        $allowedGetParametersConfig = $this->config->getAllowedGetParametersList();

        if (count($allowedGetParametersConfig) === 0) {
            return '';
        }

        $allowedGetParameters = array_intersect_key($getParameters, array_flip($allowedGetParametersConfig));
        if (count($allowedGetParameters) === 0) {
            return '';
        }

        ksort($allowedGetParameters);

        return '?' . http_build_query($allowedGetParameters);
    }

    /**
     * @param string $urlSegments
     * @param string $getParametersKey
     *
     * @return string
     */
    protected function assembleCacheKey($urlSegments, $getParametersKey): string
    {
        $storeName = $this->storeClient->getCurrentStore()->getName();
        $locale = $this->localeClient->getCurrentLocale();

        $cacheKey = strtolower(
            $storeName . static::KEY_NAME_SEPARATOR .
            $locale . static::KEY_NAME_SEPARATOR .
            static::KEY_NAME_PREFIX . static::KEY_NAME_SEPARATOR .
            $urlSegments . $getParametersKey
        );

        return $cacheKey;
    }
}
