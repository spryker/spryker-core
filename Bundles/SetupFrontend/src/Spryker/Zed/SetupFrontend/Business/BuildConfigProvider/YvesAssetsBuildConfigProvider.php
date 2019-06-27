<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\BuildConfigProvider;

use Psr\Log\LoggerInterface;
use Spryker\Zed\SetupFrontend\Dependency\Service\SetupFrontendToUtilEncodingServiceInterface;
use Spryker\Zed\SetupFrontend\SetupFrontendConfig;

class YvesAssetsBuildConfigProvider implements YvesAssetsBuildConfigProviderInterface
{
    protected const YVES_ASSETS_CONFIG_STORE_KEY = 'storeKey';

    /**
     * @var \Spryker\Zed\SetupFrontend\SetupFrontendConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\SetupFrontend\Dependency\Service\SetupFrontendToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var array|\Spryker\Zed\SetupFrontendExtension\Dependency\Plugin\YvesFrontendStoreConfigExpanderPluginInterface[]
     */
    protected $yvesFrontendStoreConfigExpanderPlugins;

    /**
     * @param \Spryker\Zed\SetupFrontend\SetupFrontendConfig $config
     * @param \Spryker\Zed\SetupFrontend\Dependency\Service\SetupFrontendToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\SetupFrontendExtension\Dependency\Plugin\YvesFrontendStoreConfigExpanderPluginInterface[] $yvesFrontendStoreConfigExpanderPlugins
     */
    public function __construct(
        SetupFrontendConfig $config,
        SetupFrontendToUtilEncodingServiceInterface $utilEncodingService,
        array $yvesFrontendStoreConfigExpanderPlugins
    ) {
        $this->config = $config;
        $this->utilEncodingService = $utilEncodingService;
        $this->yvesFrontendStoreConfigExpanderPlugins = $yvesFrontendStoreConfigExpanderPlugins;
    }

    /**
     * @param string $storeName
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function generateYvesAssetsBuildConfig(string $storeName, LoggerInterface $logger): bool
    {
        $configData = $this->loadDataFromConfigFile($logger);

        $configData = $this->prepareConfigDataForStore($configData, $storeName);

        return $this->storeConfigDataFile($configData, $logger);
    }

    /**
     * @param array $configData
     * @param string $storeName
     *
     * @return array
     */
    protected function prepareConfigDataForStore(array $configData, string $storeName): array
    {
        $storeConfigData = [];
        if (isset($configData[$storeName])) {
            $storeConfigData = $configData[$storeName];
        }

        $storeConfigData[static::YVES_ASSETS_CONFIG_STORE_KEY] = strtolower($storeName);

        $storeConfigData = $this->executeYvesFrontendConfigExpanderPlugins($storeConfigData);

        $configData[$storeName] = $storeConfigData;

        return $configData;
    }

    /**
     * @param array $storeConfigData
     *
     * @return array
     */
    protected function executeYvesFrontendConfigExpanderPlugins(array $storeConfigData): array
    {
        foreach ($this->yvesFrontendStoreConfigExpanderPlugins as $frontendStoreConfigExpanderPlugin) {
            $storeConfigData = $frontendStoreConfigExpanderPlugin->expand($storeConfigData);
        }

        return $storeConfigData;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return array
     */
    protected function loadDataFromConfigFile(LoggerInterface $logger): array
    {
        $configFileName = $this->config->getYvesFrontendConfigFilePath();

        if (!file_exists($configFileName)) {
            $logger->info(sprintf(
                'Config file "%s" not found.',
                $configFileName
            ));

            return [];
        }

        $fileContent = file_get_contents($configFileName);

        if ($fileContent === false) {
            $logger->info(sprintf(
                'Config file "%s" is not readable.',
                $configFileName
            ));

            return [];
        }

        $logger->info(sprintf(
            'Config file "%s" successfully loaded.',
            $configFileName
        ));

        return $this->utilEncodingService->decodeJson($fileContent, true);
    }

    /**
     * @param array $configData
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    protected function storeConfigDataFile(array $configData, LoggerInterface $logger): bool
    {
        $encodedConfigFileData = $this->utilEncodingService->encodeJson($configData, JSON_PRETTY_PRINT);
        $configFileName = $this->config->getYvesFrontendConfigFilePath();

        $isFileSaved = (bool)file_put_contents(
            $configFileName,
            $encodedConfigFileData
        );

        if ($isFileSaved === false) {
            $logger->error(sprintf(
                'Config file "%s" is not writable.',
                $configFileName
            ));

            return false;
        }

        $logger->info(sprintf(
            'Config file "%s" successfully saved.',
            $configFileName
        ));

        return true;
    }
}
