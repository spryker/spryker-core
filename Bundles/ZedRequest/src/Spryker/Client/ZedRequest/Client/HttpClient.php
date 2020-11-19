<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ZedRequest\Client;

use Spryker\Client\ZedRequest\Plugin\AuthTokenHeaderExpanderPlugin;
use Spryker\Client\ZedRequest\Plugin\RequestIdHeaderExpanderPlugin;
use Spryker\Client\ZedRequest\ZedRequestConfig;
use Spryker\Service\UtilNetwork\UtilNetworkServiceInterface;
use Spryker\Service\UtilText\UtilTextServiceInterface;
use Spryker\Shared\ZedRequest\Client\AbstractHttpClient;

class HttpClient extends AbstractHttpClient implements HttpClientInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestConfig
     */
    protected $config;

    /**
     * @var \Spryker\Client\ZedRequestExtension\Dependency\Plugin\HeaderExpanderPluginInterface[]
     */
    protected $headerExpanderPlugins;

    /**
     * @deprecated Added for BC compatibility reasons, will be removed with next major.
     *
     * @var \Spryker\Service\UtilText\UtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestConfig $config
     * @param array $headerExpanderPlugins
     * @param \Spryker\Service\UtilNetwork\UtilNetworkServiceInterface $utilNetworkService
     * @param \Spryker\Service\UtilText\UtilTextServiceInterface $utilTextService
     */
    public function __construct(
        ZedRequestConfig $config,
        array $headerExpanderPlugins,
        UtilNetworkServiceInterface $utilNetworkService,
        UtilTextServiceInterface $utilTextService
    ) {
        parent::__construct($config->getZedRequestBaseUrl(), $utilNetworkService, $config->getClientConfiguration());

        $this->config = $config;
        $this->utilTextService = $utilTextService;
        $this->headerExpanderPlugins = $headerExpanderPlugins;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        $header = [];

        foreach ($this->headerExpanderPlugins as $headerExpanderPlugin) {
            $header = $headerExpanderPlugin->expandHeader($header);
        }

        $header = $this->ensureHeaderBackwardsCompatibility($header);

        return $header;
    }

    /**
     * @deprecated Method only exists for backward-compatibility reasons and will be removed with the next major.
     *
     * @param array $header
     *
     * @return array
     */
    protected function ensureHeaderBackwardsCompatibility(array $header): array
    {
        if (!isset($header['Auth-Token'])) {
            trigger_error(
                sprintf('Spryker: When you need the "Auth-Token" header use the "%s" to add it. With the next major it will not be added automatically anymore.', AuthTokenHeaderExpanderPlugin::class),
                E_USER_DEPRECATED
            );

            $header['Auth-Token'] = $this->utilTextService->generateToken($this->config->getRawToken(), $this->config->getTokenOptions());
        }

        if (!isset($header['X-Request-ID'])) {
            trigger_error(
                sprintf('Spryker: When you need the "X-Request-ID" header use the "%s" to add it. With the next major it will not be added automatically anymore.', RequestIdHeaderExpanderPlugin::class),
                E_USER_DEPRECATED
            );

            $header['X-Request-ID'] = $this->utilNetworkService->getRequestId();
        }

        return $header;
    }
}
