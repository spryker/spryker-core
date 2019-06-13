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
    protected $setupFrontendConfig;

    /**
     * @var string
     */
    protected $storeName;

    /**
     * @var \Spryker\Zed\SetupFrontend\Dependency\Service\SetupFrontendToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var array|\Spryker\Zed\SetupFrontendExtension\Dependency\Plugin\YvesFrontendStoreConfigExpanderPluginInterface[]
     */
    protected $yvesFrontendStoreConfigExpanderPlugins;

    /**
     * @param \Spryker\Zed\SetupFrontend\SetupFrontendConfig $setupFrontendConfig
     * @param \Spryker\Zed\SetupFrontend\Dependency\Service\SetupFrontendToUtilEncodingServiceInterface $utilEncodingService
     * @param string $storeName
     * @param \Spryker\Zed\SetupFrontendExtension\Dependency\Plugin\YvesFrontendStoreConfigExpanderPluginInterface[] $yvesFrontendStoreConfigExpanderPlugins
     */
    public function __construct(
        SetupFrontendConfig $setupFrontendConfig,
        SetupFrontendToUtilEncodingServiceInterface $utilEncodingService,
        string $storeName,
        array $yvesFrontendStoreConfigExpanderPlugins
    ) {
        $this->setupFrontendConfig = $setupFrontendConfig;
        $this->storeName = $storeName;
        $this->utilEncodingService = $utilEncodingService;
        $this->yvesFrontendStoreConfigExpanderPlugins = $yvesFrontendStoreConfigExpanderPlugins;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return bool
     */
    public function generateYvesAssetsBuildConfig(LoggerInterface $logger): bool
    {
        $configData = $this->loadDataFromConfigFile($logger);

        $configData = $this->prepareConfigDataForCurrentStore($configData);

        return $this->storeConfigDataFile($configData, $logger);
    }

    /**
     * @param array $configData
     *
     * @return array
     */
    protected function prepareConfigDataForCurrentStore(array $configData): array
    {
        $storeConfigData = [];
        if (isset($configData[$this->storeName])) {
            $storeConfigData = $configData[$this->storeName];
        }

        $storeConfigData[static::YVES_ASSETS_CONFIG_STORE_KEY] = strtolower($this->storeName);

        $storeConfigData = $this->executeExpandYvesFrontendConfigDataPlugins($storeConfigData);

        $configData[$this->storeName] = $storeConfigData;

        return $configData;
    }

    /**
     * @param array $storeConfigData
     *
     * @return array
     */
    protected function executeExpandYvesFrontendConfigDataPlugins(array $storeConfigData): array
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
        $configFileName = $this->setupFrontendConfig->getYvesFrontendConfigFilePath();

        if (!file_exists($configFileName)) {
            $logger->info(sprintf(
                'Config file "%s" not found.',
                $configFileName
            ));

            return [];
        }

        $encodedConfigFileData = file_get_contents($configFileName);

        if ($encodedConfigFileData === false) {
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

        return $this->utilEncodingService->decodeJson($encodedConfigFileData, true);
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
        $configFileName = $this->setupFrontendConfig->getYvesFrontendConfigFilePath();

        $isFileUpdated = (bool)file_put_contents(
            $configFileName,
            $encodedConfigFileData
        );

        if ($isFileUpdated === false) {
            $logger->error(sprintf(
                'Config file "%s" is not available for update.',
                $configFileName
            ));

            return false;
        }

        $logger->info(sprintf(
            'Config file "%s" successfully stored.',
            $configFileName
        ));

        return true;
    }
}
