<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationWishlistsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\RestWishlistItemProductConfigurationInstanceAttributesTransfer;

class ProductConfigurationInstanceMapper implements ProductConfigurationInstanceMapperInterface
{
    /**
     * @var \Spryker\Glue\ProductConfigurationWishlistsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapperInterface
     */
    protected $productConfigurationInstancePriceMapper;

    /**
     * @var array<\Spryker\Glue\ProductConfigurationWishlistsRestApiExtension\Dependency\Plugin\ProductConfigurationPriceMapperPluginInterface>
     */
    protected $productConfigurationPriceMapperPlugins;

    /**
     * @var array<\Spryker\Glue\ProductConfigurationWishlistsRestApiExtension\Dependency\Plugin\RestProductConfigurationPriceMapperPluginInterface>
     */
    protected $restProductConfigurationPriceMapperPlugins;

    /**
     * @param \Spryker\Glue\ProductConfigurationWishlistsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapperInterface $productConfigurationInstancePriceMapper
     * @param array<\Spryker\Glue\ProductConfigurationWishlistsRestApiExtension\Dependency\Plugin\ProductConfigurationPriceMapperPluginInterface> $productConfigurationPriceMapperPlugins
     * @param array<\Spryker\Glue\ProductConfigurationWishlistsRestApiExtension\Dependency\Plugin\RestProductConfigurationPriceMapperPluginInterface> $restProductConfigurationPriceMapperPlugins
     */
    public function __construct(
        ProductConfigurationInstancePriceMapperInterface $productConfigurationInstancePriceMapper,
        array $productConfigurationPriceMapperPlugins,
        array $restProductConfigurationPriceMapperPlugins
    ) {
        $this->productConfigurationInstancePriceMapper = $productConfigurationInstancePriceMapper;
        $this->productConfigurationPriceMapperPlugins = $productConfigurationPriceMapperPlugins;
        $this->restProductConfigurationPriceMapperPlugins = $restProductConfigurationPriceMapperPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\RestWishlistItemProductConfigurationInstanceAttributesTransfer $restWishlistItemProductConfigurationInstanceAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    public function mapRestWishlistItemProductConfigurationInstanceAttributesToProductConfigurationInstance(
        RestWishlistItemProductConfigurationInstanceAttributesTransfer $restWishlistItemProductConfigurationInstanceAttributesTransfer,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationInstanceTransfer {
        $productConfigurationInstanceTransfer = $productConfigurationInstanceTransfer->fromArray(
            $restWishlistItemProductConfigurationInstanceAttributesTransfer->toArray(),
            true,
        );
        $priceProductTransfers = $this->productConfigurationInstancePriceMapper->mapRestProductConfigurationPriceAttributesTransfersToPriceProductTransfers(
            $restWishlistItemProductConfigurationInstanceAttributesTransfer->getPrices(),
            new ArrayObject(),
        );

        $productConfigurationInstanceTransfer->setPrices($priceProductTransfers);

        return $this->executeProductConfigurationPriceMapperPlugins(
            $restWishlistItemProductConfigurationInstanceAttributesTransfer->getPrices()->getArrayCopy(),
            $productConfigurationInstanceTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     * @param \Generated\Shared\Transfer\RestWishlistItemProductConfigurationInstanceAttributesTransfer $restWishlistItemProductConfigurationInstanceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestWishlistItemProductConfigurationInstanceAttributesTransfer
     */
    public function mapProductConfigurationInstanceToRestWishlistItemProductConfigurationInstanceAttributes(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer,
        RestWishlistItemProductConfigurationInstanceAttributesTransfer $restWishlistItemProductConfigurationInstanceAttributesTransfer
    ): RestWishlistItemProductConfigurationInstanceAttributesTransfer {
        $restWishlistItemProductConfigurationInstanceAttributesTransfer = $restWishlistItemProductConfigurationInstanceAttributesTransfer->fromArray(
            $productConfigurationInstanceTransfer->toArray(),
            true,
        );

        $restProductConfigurationPriceAttributesTransfers = $this->productConfigurationInstancePriceMapper
            ->mapPriceProductTransfersToRestProductConfigurationPriceAttributesTransfers(
                $productConfigurationInstanceTransfer->getPrices(),
                new ArrayObject(),
            );

        $restProductConfigurationPriceAttributesTransfers = $this->executeRestProductConfigurationPriceMapperPlugins(
            $productConfigurationInstanceTransfer,
            $restProductConfigurationPriceAttributesTransfers->getArrayCopy(),
        );

        return $restWishlistItemProductConfigurationInstanceAttributesTransfer->setPrices(
            new ArrayObject($restProductConfigurationPriceAttributesTransfers),
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer> $restProductConfigurationPriceAttributesTransfers
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    protected function executeProductConfigurationPriceMapperPlugins(
        array $restProductConfigurationPriceAttributesTransfers,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationInstanceTransfer {
        foreach ($this->productConfigurationPriceMapperPlugins as $productConfigurationMapperPlugin) {
            $productConfigurationInstanceTransfer = $productConfigurationMapperPlugin->map(
                $restProductConfigurationPriceAttributesTransfers,
                $productConfigurationInstanceTransfer,
            );
        }

        return $productConfigurationInstanceTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     * @param array<\Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer> $restProductConfigurationPriceAttributesTransfers
     *
     * @return array<\Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer>
     */
    protected function executeRestProductConfigurationPriceMapperPlugins(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer,
        array $restProductConfigurationPriceAttributesTransfers
    ): array {
        foreach ($this->restProductConfigurationPriceMapperPlugins as $restProductConfigurationPriceMapperPlugin) {
            $restProductConfigurationPriceAttributesTransfers = $restProductConfigurationPriceMapperPlugin->map(
                $productConfigurationInstanceTransfer,
                $restProductConfigurationPriceAttributesTransfers,
            );
        }

        return $restProductConfigurationPriceAttributesTransfers;
    }
}
