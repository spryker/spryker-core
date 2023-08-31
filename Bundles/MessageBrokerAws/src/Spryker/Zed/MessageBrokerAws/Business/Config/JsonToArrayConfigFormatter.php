<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBrokerAws\Business\Config;

use Spryker\Zed\MessageBrokerAws\Business\Exception\ConfigDecodingFailedException;
use Spryker\Zed\MessageBrokerAws\Business\Exception\ConfigInvalidException;
use Spryker\Zed\MessageBrokerAws\Dependency\Facade\MessageBrokerAwsToStoreFacadeInterface;
use Spryker\Zed\MessageBrokerAws\Dependency\Service\MessageBrokerAwsToUtilEncodingServiceInterface;

/**
 * @deprecated Will be removed without replacement.
 */
class JsonToArrayConfigFormatter implements ConfigFormatterInterface
{
    /**
     * @var string
     */
    protected const DEFAULT_CONFIG_KEY = 'default';

    /**
     * @var \Spryker\Zed\MessageBrokerAws\Dependency\Facade\MessageBrokerAwsToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\MessageBrokerAws\Dependency\Service\MessageBrokerAwsToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Zed\MessageBrokerAws\Dependency\Facade\MessageBrokerAwsToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\MessageBrokerAws\Dependency\Service\MessageBrokerAwsToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        MessageBrokerAwsToStoreFacadeInterface $storeFacade,
        MessageBrokerAwsToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->storeFacade = $storeFacade;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $config
     *
     * @throws \Spryker\Zed\MessageBrokerAws\Business\Exception\ConfigInvalidException
     *
     * @return array<string, mixed>
     */
    public function format(string $config): array
    {
        $formattedConfig = $this->getFormattedConfiguration($config);

        if ($this->isSimpleConfiguration($formattedConfig)) {
            return $formattedConfig;
        }

        $defaultConfiguration = $this->getDefaultConfiguration($formattedConfig);
        $storeConfiguration = $this->getStoreConfiguration($formattedConfig);

        if ($defaultConfiguration === [] && $storeConfiguration === []) {
            throw new ConfigInvalidException(
                sprintf('No default configuration or "%s" store configuration found', $this->getCurrentStore()),
            );
        }

        return array_merge($defaultConfiguration, $storeConfiguration);
    }

    /**
     * @param string $config
     *
     * @throws \Spryker\Zed\MessageBrokerAws\Business\Exception\ConfigDecodingFailedException
     *
     * @return array<mixed>
     */
    protected function getFormattedConfiguration(string $config): array
    {
        $formattedConfig = (array)$this->utilEncodingService->decodeJson(
            html_entity_decode($config, ENT_QUOTES),
            true,
        );

        if (!$formattedConfig) {
            throw new ConfigDecodingFailedException();
        }

        return $formattedConfig;
    }

    /**
     * @param array<mixed> $formattedConfig
     *
     * @return bool
     */
    protected function isSimpleConfiguration(array $formattedConfig): bool
    {
        if (!$formattedConfig) {
            return true;
        }

        $firstConfigurationValue = reset($formattedConfig);

        return !is_array($firstConfigurationValue);
    }

    /**
     * @param array<mixed> $formattedConfig
     *
     * @return array<mixed>
     */
    protected function getStoreConfiguration(array $formattedConfig): array
    {
        $currentStoreName = $this->getCurrentStore();

        if (!array_key_exists($currentStoreName, $formattedConfig)) {
            return [];
        }

        return $formattedConfig[$currentStoreName];
    }

    /**
     * @param array<mixed> $formattedConfig
     *
     * @return array<mixed>
     */
    protected function getDefaultConfiguration(array $formattedConfig): array
    {
        if (!array_key_exists(static::DEFAULT_CONFIG_KEY, $formattedConfig)) {
            return [];
        }

        return $formattedConfig[static::DEFAULT_CONFIG_KEY];
    }

    /**
     * @return string
     */
    protected function getCurrentStore(): string
    {
        return $this->storeFacade->getCurrentStore()->getNameOrFail();
    }
}
