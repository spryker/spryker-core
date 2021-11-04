<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Mapper;

use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;

class ProductConfigurationResponseMapper implements ProductConfigurationResponseMapperInterface
{
    /**
     * @var string
     */
    protected const KEY_PRODUCT_CONFIGURATION_INSTANCE = 'productConfigurationInstance';

    /**
     * @var \Spryker\Client\ProductConfiguration\Mapper\ProductConfigurationInstancePriceMapperInterface
     */
    protected $productConfigurationInstancePriceMapper;

    /**
     * @param \Spryker\Client\ProductConfiguration\Mapper\ProductConfigurationInstancePriceMapperInterface $productConfigurationInstancePriceMapper
     */
    public function __construct(ProductConfigurationInstancePriceMapperInterface $productConfigurationInstancePriceMapper)
    {
        $this->productConfigurationInstancePriceMapper = $productConfigurationInstancePriceMapper;
    }

    /**
     * @param array $configuratorResponseData
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer
     */
    public function mapConfiguratorResponseDataToProductConfiguratorResponseTransfer(
        array $configuratorResponseData,
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
    ): ProductConfiguratorResponseTransfer {
        $productConfiguratorResponseTransfer->fromArray($configuratorResponseData, true);

        $productConfigurationInstanceTransfer = $this->productConfigurationInstancePriceMapper
            ->mapConfiguratorResponseDataPricesToProductConfigurationInstancePrices(
                $configuratorResponseData[static::KEY_PRODUCT_CONFIGURATION_INSTANCE] ?? [],
                $productConfiguratorResponseTransfer->getProductConfigurationInstanceOrFail(),
            );

        return $productConfiguratorResponseTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
    }
}
