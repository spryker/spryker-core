<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationShoppingListsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\RestShoppingListItemProductConfigurationInstanceAttributesTransfer;

class ProductConfigurationInstanceMapper implements ProductConfigurationInstanceMapperInterface
{
    /**
     * @var \Spryker\Glue\ProductConfigurationShoppingListsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapperInterface
     */
    protected ProductConfigurationInstancePriceMapperInterface $productConfigurationInstancePriceMapper;

    /**
     * @var array<\Spryker\Glue\ProductConfigurationShoppingListsRestApiExtension\Dependency\Plugin\ProductConfigurationPriceMapperPluginInterface>
     */
    protected array $productConfigurationPriceMapperPlugins;

    /**
     * @var array<\Spryker\Glue\ProductConfigurationShoppingListsRestApiExtension\Dependency\Plugin\RestProductConfigurationPriceMapperPluginInterface>
     */
    protected array $restProductConfigurationPriceMapperPlugins;

    /**
     * @param \Spryker\Glue\ProductConfigurationShoppingListsRestApi\Processor\Mapper\ProductConfigurationInstancePriceMapperInterface $productConfigurationInstancePriceMapper
     * @param array<\Spryker\Glue\ProductConfigurationShoppingListsRestApiExtension\Dependency\Plugin\ProductConfigurationPriceMapperPluginInterface> $productConfigurationPriceMapperPlugins
     * @param array<\Spryker\Glue\ProductConfigurationShoppingListsRestApiExtension\Dependency\Plugin\RestProductConfigurationPriceMapperPluginInterface> $restProductConfigurationPriceMapperPlugins
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
     * @param \Generated\Shared\Transfer\RestShoppingListItemProductConfigurationInstanceAttributesTransfer $restShoppingListItemProductConfigurationInstanceAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    public function mapRestShoppingListItemProductConfigurationInstanceAttributesToProductConfigurationInstance(
        RestShoppingListItemProductConfigurationInstanceAttributesTransfer $restShoppingListItemProductConfigurationInstanceAttributesTransfer,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationInstanceTransfer {
        $productConfigurationInstanceTransfer = $productConfigurationInstanceTransfer->fromArray(
            $restShoppingListItemProductConfigurationInstanceAttributesTransfer->toArray(),
            true,
        );
        $priceProductTransfers = $this->productConfigurationInstancePriceMapper->mapRestProductConfigurationPriceAttributesTransfersToPriceProductTransfers(
            $restShoppingListItemProductConfigurationInstanceAttributesTransfer->getPrices(),
            new ArrayObject(),
        );

        $productConfigurationInstanceTransfer->setPrices($priceProductTransfers);

        return $this->executeProductConfigurationPriceMapperPlugins(
            $restShoppingListItemProductConfigurationInstanceAttributesTransfer->getPrices()->getArrayCopy(),
            $productConfigurationInstanceTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListItemProductConfigurationInstanceAttributesTransfer $restShoppingListItemProductConfigurationInstanceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemProductConfigurationInstanceAttributesTransfer
     */
    public function mapProductConfigurationInstanceToRestShoppingListItemProductConfigurationInstanceAttributes(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer,
        RestShoppingListItemProductConfigurationInstanceAttributesTransfer $restShoppingListItemProductConfigurationInstanceAttributesTransfer
    ): RestShoppingListItemProductConfigurationInstanceAttributesTransfer {
        $restShoppingListItemProductConfigurationInstanceAttributesTransfer = $restShoppingListItemProductConfigurationInstanceAttributesTransfer->fromArray(
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

        return $restShoppingListItemProductConfigurationInstanceAttributesTransfer->setPrices(
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
