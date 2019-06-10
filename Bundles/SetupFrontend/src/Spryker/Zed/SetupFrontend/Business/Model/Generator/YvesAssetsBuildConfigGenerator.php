<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SetupFrontend\Business\Model\Generator;

use Psr\Log\LoggerInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\SetupFrontend\Dependency\Service\SetupFrontendToUtilEncodingServiceInterface;
use Spryker\Zed\SetupFrontend\SetupFrontendConfig;

class YvesAssetsBuildConfigGenerator implements YvesAssetsBuildConfigGeneratorInterface
{
    /**
     * @var \Spryker\Zed\SetupFrontend\SetupFrontendConfig
     */
    protected $setupFrontendConfig;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var \Spryker\Zed\SetupFrontend\Dependency\Service\SetupFrontendToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var array|\Spryker\Zed\SetupFrontendExtension\Dependency\YvesFrontendStoreConfigExpanderPluginInterface[]
     */
    protected $yvesFrontendStoreConfigExpanderPlugins;

    /**
     * @param \Spryker\Zed\SetupFrontend\SetupFrontendConfig $setupFrontendConfig
     * @param \Spryker\Zed\SetupFrontend\Dependency\Service\SetupFrontendToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Zed\SetupFrontendExtension\Dependency\YvesFrontendStoreConfigExpanderPluginInterface[] $yvesFrontendStoreConfigExpanderPlugins
     */
    public function __construct(
        SetupFrontendConfig $setupFrontendConfig,
        SetupFrontendToUtilEncodingServiceInterface $utilEncodingService,
        Store $store,
        array $yvesFrontendStoreConfigExpanderPlugins
    ) {
        $this->setupFrontendConfig = $setupFrontendConfig;
        $this->store = $store;
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
        $storeName = $this->store->getStoreName();
        $storeKey = strtolower($storeName);
        if (isset($configData[$storeKey])) {
            $storeConfigData = $configData[$storeKey];
        }

        $storeConfigData[SetupFrontendConfig::YVES_ASSETS_CONFIG_STORE_NAME_KEY] = $storeName;

        $storeConfigData = $this->executeExpandYvesFrontendConfigDataPlugins($storeConfigData);

        $configData[$storeKey] = $storeConfigData;

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

        $logger->info(sprintf(
            'Config file "%s" successfully loaded.',
            $configFileName
        ));

        if (!$encodedConfigFileData) {
            return [];
        }

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

        if ($isFileUpdated) {
            $logger->info(sprintf(
                'Config file "%s" successfully stored.',
                $configFileName
            ));

            return true;
        }

        return false;
    }
}
