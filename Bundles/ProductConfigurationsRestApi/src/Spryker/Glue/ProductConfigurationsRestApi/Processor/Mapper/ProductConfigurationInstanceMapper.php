<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer;
use Generated\Shared\Transfer\RestItemsAttributesTransfer;

class ProductConfigurationInstanceMapper implements ProductConfigurationInstanceMapperInterface
{
    /**
     * @var \Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapperInterface
     */
    protected $productConfigurationInstancePriceMapper;

    /**
     * @var \Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\CartItemProductConfigurationMapperPluginInterface[]
     */
    protected $cartItemProductConfigurationMapperPlugins;

    /**
     * @var \Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\RestCartItemProductConfigurationMapperPluginInterface[]
     */
    protected $restCartItemProductConfigurationMapperPlugins;

    /**
     * @param \Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapperInterface $productConfigurationInstancePriceMapper
     * @param \Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\CartItemProductConfigurationMapperPluginInterface[] $cartItemProductConfigurationMapperPlugins
     * @param \Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\RestCartItemProductConfigurationMapperPluginInterface[] $restCartItemProductConfigurationMapperPlugins
     */
    public function __construct(
        ProductConfigurationInstancePriceMapperInterface $productConfigurationInstancePriceMapper,
        array $cartItemProductConfigurationMapperPlugins,
        array $restCartItemProductConfigurationMapperPlugins
    ) {
        $this->productConfigurationInstancePriceMapper = $productConfigurationInstancePriceMapper;
        $this->cartItemProductConfigurationMapperPlugins = $cartItemProductConfigurationMapperPlugins;
        $this->restCartItemProductConfigurationMapperPlugins = $restCartItemProductConfigurationMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    public function mapRestCartItemProductConfigurationToProductConfigurationInstance(
        RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationInstanceTransfer {
        $productConfigurationInstanceTransfer = $productConfigurationInstanceTransfer->fromArray(
            $restCartItemProductConfigurationInstanceAttributesTransfer->toArray(),
            true
        );

        $priceProductTransfers = $this->productConfigurationInstancePriceMapper->mapRestProductConfigurationPriceAttributesTransfersToPriceProductTransfers(
            $restCartItemProductConfigurationInstanceAttributesTransfer->getPrices(),
            new ArrayObject()
        );
        $productConfigurationInstanceTransfer->setPrices(new ArrayObject($priceProductTransfers));

        return $this->executeProductConfigurationMapperPlugins(
            $restCartItemProductConfigurationInstanceAttributesTransfer,
            $productConfigurationInstanceTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestItemsAttributesTransfer $restItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestItemsAttributesTransfer
     */
    public function mapItemTransferToRestItemsAttributesTransfer(
        ItemTransfer $itemTransfer,
        RestItemsAttributesTransfer $restItemsAttributesTransfer
    ): RestItemsAttributesTransfer {
        $productConfigurationInstanceTransfer = $itemTransfer->getProductConfigurationInstance();
        if (!$productConfigurationInstanceTransfer) {
            return $restItemsAttributesTransfer;
        }

        $restCartItemProductConfigurationInstanceAttributesTransfer = (new RestCartItemProductConfigurationInstanceAttributesTransfer())->fromArray(
            $productConfigurationInstanceTransfer->toArray(),
            true
        );

        $restProductConfigurationPriceAttributesTransfers = $this->productConfigurationInstancePriceMapper
            ->mapPriceProductTransfersToRestProductConfigurationPriceAttributesTransfers(
                $productConfigurationInstanceTransfer->getPrices(),
                new ArrayObject()
            );
        $restCartItemProductConfigurationInstanceAttributesTransfer->setPrices($restProductConfigurationPriceAttributesTransfers);

        $restCartItemProductConfigurationInstanceAttributesTransfer = $this->executeRestCartItemProductConfigurationMapperPlugins(
            $productConfigurationInstanceTransfer,
            $restCartItemProductConfigurationInstanceAttributesTransfer
        );

        return $restItemsAttributesTransfer->setProductConfigurationInstance($restCartItemProductConfigurationInstanceAttributesTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    protected function executeProductConfigurationMapperPlugins(
        RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationInstanceTransfer {
        foreach ($this->cartItemProductConfigurationMapperPlugins as $productConfigurationMapperPlugin) {
            $productConfigurationInstanceTransfer = $productConfigurationMapperPlugin->map(
                $restCartItemProductConfigurationInstanceAttributesTransfer,
                $productConfigurationInstanceTransfer
            );
        }

        return $productConfigurationInstanceTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     * @param \Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer
     */
    protected function executeRestCartItemProductConfigurationMapperPlugins(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer,
        RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer
    ): RestCartItemProductConfigurationInstanceAttributesTransfer {
        foreach ($this->restCartItemProductConfigurationMapperPlugins as $cartItemProductConfigurationMapperPlugin) {
            $restCartItemProductConfigurationInstanceAttributesTransfer = $cartItemProductConfigurationMapperPlugin->map(
                $productConfigurationInstanceTransfer,
                $restCartItemProductConfigurationInstanceAttributesTransfer
            );
        }

        return $restCartItemProductConfigurationInstanceAttributesTransfer;
    }
}
