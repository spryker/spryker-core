<?php

namespace SprykerFeature\Zed\ProductOption\Business\Model;

use Generated\Shared\Transfer\ProductOptionTypeCollectionTransfer;
use Generated\Shared\Transfer\ConfigurationPresetCollectionTransfer;
use Generated\Shared\Transfer\OptionTypeCollectionTransfer;

interface ProductOptionReaderInterface
{

    /**
     * Get all product options with available option values.
     *
     * @return OptionTypeCollectionTransfer
     */
    public function getOptions();

    /**
     * Get all product options for a concrete product, including any constraintsa and exclusions
     *
     * @param string $concreteProductSku
     *
     * @return ProductOptionTypeCollectionTransfer
     */
    public function getProductOption($concreteProductSku);

    /**
     *
     * Get all configuration presets for a concrete product
     *
     * @param string $concreteProductSku
     *
     * @return ConfigurationPresetCollectionTransfer
     */
    public function getConfigPresets($concreteProductSku);
}
