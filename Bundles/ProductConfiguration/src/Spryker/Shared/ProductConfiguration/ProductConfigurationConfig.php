<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ProductConfiguration;

use Spryker\Shared\Kernel\AbstractBundleConfig;
use Spryker\Shared\ProductConfiguration\Exception\EncryptionKeyNotPreConfigured;
use Spryker\Shared\ProductConfiguration\Exception\HexInitializationVectorNotPreConfigured;

class ProductConfigurationConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Dimension type as used for product configuration price.
     *
     * @api
     */
    public const PRICE_DIMENSION_PRODUCT_CONFIGURATION = 'PRODUCT_CONFIGURATION';

    /**
     * Specification:
     * - Indicate source type as pdp.
     *
     * @api
     */
    public const SOURCE_TYPE_PDP = 'SOURCE_TYPE_PDP';

    /**
     * Specification:
     * - Indicate source type as cart.
     *
     * @api
     */
    public const SOURCE_TYPE_CART = 'SOURCE_TYPE_CART';

    /**
     * @api
     *
     * @throws \Spryker\Shared\ProductConfiguration\Exception\EncryptionKeyNotPreConfigured
     *
     * @return string
     */
    public function getEncryptionKey(): string
    {
        $encryptionKey = $this->get(ProductConfigurationConstants::SPRYKER_CONFIGURATOR_ENCRYPTION_KEY, false);

        if ($encryptionKey) {
            return $encryptionKey;
        }

        throw new EncryptionKeyNotPreConfigured('Encryption key is not pre-configured, please update SPRYKER_CONFIGURATOR_ENCRYPTION_KEY env variable.');
    }

    /**
     * @api
     *
     * @throws \Spryker\Shared\ProductConfiguration\Exception\HexInitializationVectorNotPreConfigured
     *
     * @return string
     */
    public function getHexInitializationVector(): string
    {
        $hexInitializationVector = $this->get(
            ProductConfigurationConstants::SPRYKER_CONFIGURATOR_HEX_INITIALIZATION_VECTOR,
            false
        );

        if ($hexInitializationVector) {
            return $hexInitializationVector;
        }

        throw new HexInitializationVectorNotPreConfigured(
            'Hex initialization vector is not pre-configured, please update SPRYKER_CONFIGURATOR_HEX_INITIALIZATION_VECTOR env variable.'
        );
    }
}
