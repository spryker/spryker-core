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
     * @var array<\Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\ProductConfigurationPriceMapperPluginInterface>
     */
    protected $productConfigurationPriceMapperPlugins;

    /**
     * @var array<\Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\RestProductConfigurationPriceMapperPluginInterface>
     */
    protected $restProductConfigurationPriceMapperPlugins;

    /**
     * @param \Spryker\Glue\ProductConfigurationsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapperInterface $productConfigurationInstancePriceMapper
     * @param array<\Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\ProductConfigurationPriceMapperPluginInterface> $productConfigurationPriceMapperPlugins
     * @param array<\Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\RestProductConfigurationPriceMapperPluginInterface> $restProductConfigurationPriceMapperPlugins
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

        return $this->executeProductConfigurationPriceMapperPlugins(
            $restCartItemProductConfigurationInstanceAttributesTransfer->getPrices()->getArrayCopy(),
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

        $restProductConfigurationPriceAttributesTransfers = $this->executeRestProductConfigurationPriceMapperPlugins(
            $productConfigurationInstanceTransfer,
            $restProductConfigurationPriceAttributesTransfers->getArrayCopy()
        );

        $restCartItemProductConfigurationInstanceAttributesTransfer->setPrices(
            new ArrayObject($restProductConfigurationPriceAttributesTransfers)
        );

        return $restItemsAttributesTransfer->setProductConfigurationInstance($restCartItemProductConfigurationInstanceAttributesTransfer);
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
                $productConfigurationInstanceTransfer
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
                $restProductConfigurationPriceAttributesTransfers
            );
        }

        return $restProductConfigurationPriceAttributesTransfers;
    }
}
