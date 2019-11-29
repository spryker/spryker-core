<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundlePageSearch\Business\Expander;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;

class ConfigurableBundleTemplatePageSearchExpander implements ConfigurableBundleTemplatePageSearchExpanderInterface
{
    /**
     * @var \Spryker\Zed\ConfigurableBundlePageSearchExtension\Dependency\Plugin\ConfigurableBundleTemplatePageDataExpanderPluginInterface[]
     */
    protected $configurableBundleTemplatePageDataExpanderPlugins;

    /**
     * @param \Spryker\Zed\ConfigurableBundlePageSearchExtension\Dependency\Plugin\ConfigurableBundleTemplatePageDataExpanderPluginInterface[] $configurableBundleTemplatePageDataExpanderPlugins
     */
    public function __construct(array $configurableBundleTemplatePageDataExpanderPlugins)
    {
        $this->configurableBundleTemplatePageDataExpanderPlugins = $configurableBundleTemplatePageDataExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer
     */
    public function expand(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
    ): ConfigurableBundleTemplatePageSearchTransfer {
        $configurableBundleTemplatePageSearchTransfer = $this->expandConfigurableBundleTemplatePageSearchTransferWithImages(
            $configurableBundleTemplateTransfer,
            $configurableBundleTemplatePageSearchTransfer
        );

        $configurableBundleTemplatePageSearchTransfer = $this->executeConfigurableBundleTemplatePageDataExpanderPlugins(
            $configurableBundleTemplateTransfer,
            $configurableBundleTemplatePageSearchTransfer
        );

        return $configurableBundleTemplatePageSearchTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer
     */
    protected function executeConfigurableBundleTemplatePageDataExpanderPlugins(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
    ): ConfigurableBundleTemplatePageSearchTransfer {
        foreach ($this->configurableBundleTemplatePageDataExpanderPlugins as $configurableBundleTemplatePageDataExpanderPlugin) {
            $configurableBundleTemplatePageSearchTransfer = $configurableBundleTemplatePageDataExpanderPlugin->expand(
                $configurableBundleTemplateTransfer,
                $configurableBundleTemplatePageSearchTransfer
            );
        }

        return $configurableBundleTemplatePageSearchTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer
     */
    protected function expandConfigurableBundleTemplatePageSearchTransferWithImages(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
    ): ConfigurableBundleTemplatePageSearchTransfer {
        $images = [];
        $localizedProductImageSetTransfers = $this->getLocalizedProductImageSets($configurableBundleTemplateTransfer, $configurableBundleTemplatePageSearchTransfer);

        foreach ($localizedProductImageSetTransfers as $productImageSetTransfer) {
            $images = array_merge($images, $this->mapImageSetTransferToImages($productImageSetTransfer));
        }

        $configurableBundleTemplatePageSearchTransfer->setImages($images);

        return $configurableBundleTemplatePageSearchTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    protected function getLocalizedProductImageSets(
        ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer,
        ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer
    ): array {
        $configurableBundleTemplatePageSearchTransfer->requireLocale();
        $localizedProductImageSetTransfers = [];

        foreach ($configurableBundleTemplateTransfer->getProductImageSets() as $productImageSetTransfer) {
            if (!$productImageSetTransfer->getLocale()
                || $productImageSetTransfer->getLocale()->getLocaleName() !== $configurableBundleTemplatePageSearchTransfer->getLocale()) {
                continue;
            }

            $localizedProductImageSetTransfers[] = $productImageSetTransfer;
        }

        return $localizedProductImageSetTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return array
     */
    protected function mapImageSetTransferToImages(ProductImageSetTransfer $productImageSetTransfer): array
    {
        $images = [];

        foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $images[] = $productImageTransfer->toArray(false, true);
        }

        return $images;
    }
}
