<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Expander;

use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;

class ProductConfiguratorRequestExpander implements ProductConfiguratorRequestExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataExpanderInterface
     */
    protected $productConfiguratorRequestDataExpanderComposite;

    /**
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestExpanderPluginInterface[]
     */
    protected $productConfiguratorRequestExpanderPlugins;

    /**
     * @param \Spryker\Client\ProductConfiguration\Expander\ProductConfiguratorRequestDataExpanderInterface $productConfiguratorRequestDataExpanderComposite
     * @param \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestExpanderPluginInterface[] $productConfiguratorRequestExpanderPlugins
     */
    public function __construct(
        ProductConfiguratorRequestDataExpanderInterface $productConfiguratorRequestDataExpanderComposite,
        array $productConfiguratorRequestExpanderPlugins
    ) {
        $this->productConfiguratorRequestDataExpanderComposite = $productConfiguratorRequestDataExpanderComposite;
        $this->productConfiguratorRequestExpanderPlugins = $productConfiguratorRequestExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer
     */
    public function expandProductConfiguratorRequestWithContextData(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRequestTransfer {
        $productConfiguratorRequestDataTransfer = $this->productConfiguratorRequestDataExpanderComposite->expand(
            $productConfiguratorRequestTransfer->getProductConfiguratorRequestDataOrFail()
        );

        $productConfiguratorRequestTransfer->setProductConfiguratorRequestData(
            $productConfiguratorRequestDataTransfer
        );

        return $this->executeProductConfiguratorRequestExpanderPlugins($productConfiguratorRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer
     */
    protected function executeProductConfiguratorRequestExpanderPlugins(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRequestTransfer {
        foreach ($this->productConfiguratorRequestExpanderPlugins as $productConfiguratorRequestExpanderPlugin) {
            $productConfiguratorRequestTransfer = $productConfiguratorRequestExpanderPlugin->expand($productConfiguratorRequestTransfer);
        }

        return $productConfiguratorRequestTransfer;
    }
}
