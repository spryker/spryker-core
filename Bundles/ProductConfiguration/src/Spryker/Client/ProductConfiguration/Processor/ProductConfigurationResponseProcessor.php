<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Processor;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface;

class ProductConfigurationResponseProcessor implements ProductConfigurationResponseProcessorInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface[]
     */
    protected $productConfiguratorResponsePlugins;

    /**
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface
     */
    protected $defaultProductConfiguratorResponsePlugin;

    /**
     * @param \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface[] $productConfiguratorResponsePlugins
     * @param \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorResponsePluginInterface $defaultProductConfiguratorResponsePlugin
     */
    public function __construct(
        array $productConfiguratorResponsePlugins,
        ProductConfiguratorResponsePluginInterface $defaultProductConfiguratorResponsePlugin
    ) {
        $this->productConfiguratorResponsePlugins = $productConfiguratorResponsePlugins;
        $this->defaultProductConfiguratorResponsePlugin = $defaultProductConfiguratorResponsePlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        foreach ($this->productConfiguratorResponsePlugins as $configuratorKey => $productConfiguratorResponsePlugin) {
            if ($configuratorKey === $productConfiguratorResponseTransfer->getProductConfigurationInstance()->getConfiguratorKey()) {
                return $productConfiguratorResponsePlugin->processProductConfiguratorResponse($productConfiguratorResponseTransfer, $configuratorResponseData);
            }
        }

        return $this->defaultProductConfiguratorResponsePlugin
            ->processProductConfiguratorResponse($productConfiguratorResponseTransfer, $configuratorResponseData);
    }
}
