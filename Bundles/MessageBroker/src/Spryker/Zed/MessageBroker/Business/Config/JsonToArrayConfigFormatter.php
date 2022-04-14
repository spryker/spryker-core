<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Config;

use Spryker\Zed\MessageBroker\Business\Exception\ConfigDecodingFailedException;
use Spryker\Zed\MessageBroker\Dependency\Service\MessageBrokerToUtilEncodingServiceInterface;

class JsonToArrayConfigFormatter implements ConfigFormatterInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\Dependency\Service\MessageBrokerToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\MessageBroker\Dependency\Service\MessageBrokerToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(MessageBrokerToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $config
     *
     * @throws \Spryker\Zed\MessageBroker\Business\Exception\ConfigDecodingFailedException
     *
     * @return array<string, mixed>
     */
    public function format(string $config): array
    {
        $formattedConfig = $this->utilEncodingService->decodeJson(
            html_entity_decode($config, ENT_QUOTES),
            true,
        );

        if (!$formattedConfig) {
            throw new ConfigDecodingFailedException();
        }

        return $formattedConfig;
    }
}
