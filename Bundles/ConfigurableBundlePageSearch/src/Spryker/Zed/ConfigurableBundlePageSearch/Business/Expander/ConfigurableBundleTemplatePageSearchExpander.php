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

        $productImageSetTransfers = $this->getLocalizedProductImageSets($configurableBundleTemplateTransfer, $configurableBundleTemplatePageSearchTransfer);
        $productImageSetTransfers += $this->getDefaultProductImageSets($configurableBundleTemplateTransfer);

        foreach ($productImageSetTransfers as $productImageSetTransfer) {
            $images = array_merge($images, $this->getImagesArrayFromImageSetTransfer($productImageSetTransfer));
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

            $localizedProductImageSetTransfers[$productImageSetTransfer->getName()] = $productImageSetTransfer;
        }

        return $localizedProductImageSetTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetTransfer[]
     */
    protected function getDefaultProductImageSets(ConfigurableBundleTemplateTransfer $configurableBundleTemplateTransfer): array
    {
        $defaultProductImageSetTransfers = [];

        foreach ($configurableBundleTemplateTransfer->getProductImageSets() as $productImageSetTransfer) {
            if (!$productImageSetTransfer->getLocale()) {
                $defaultProductImageSetTransfers[$productImageSetTransfer->getName()] = $productImageSetTransfer;
            }
        }

        return $defaultProductImageSetTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductImageSetTransfer $productImageSetTransfer
     *
     * @return array
     */
    protected function getImagesArrayFromImageSetTransfer(ProductImageSetTransfer $productImageSetTransfer): array
    {
        $images = [];

        foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $images[] = $productImageTransfer->toArray(false, true);
        }

        return $images;
    }
}
