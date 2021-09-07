<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundleCartsRestApi\Processor\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestItemsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductBundleCartsRestApi\Dependency\Client\ProductBundleCartsRestApiToProductBundleClientInterface;
use Spryker\Glue\ProductBundleCartsRestApi\Dependency\RestResource\ProductBundleCartsRestApiToCartsRestApiResourceInterface;
use Spryker\Glue\ProductBundleCartsRestApi\Processor\RestResponseBuilder\BundleItemRestResponseBuilderInterface;

class BundledItemExpander implements BundledItemExpanderInterface
{
    /**
     * @uses \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper::BUNDLE_ITEMS
     * @var string
     */
    protected const BUNDLE_ITEMS = 'bundleItems';

    /**
     * @uses \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper::BUNDLE_PRODUCT
     * @var string
     */
    protected const BUNDLE_PRODUCT = 'bundleProduct';

    /**
     * @var \Spryker\Glue\ProductBundleCartsRestApi\Dependency\Client\ProductBundleCartsRestApiToProductBundleClientInterface
     */
    protected $productBundleClient;

    /**
     * @var \Spryker\Glue\ProductBundleCartsRestApi\Dependency\RestResource\ProductBundleCartsRestApiToCartsRestApiResourceInterface
     */
    protected $cartsRestApiResource;

    /**
     * @var \Spryker\Glue\ProductBundleCartsRestApi\Processor\RestResponseBuilder\BundleItemRestResponseBuilderInterface
     */
    protected $bundleItemRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ProductBundleCartsRestApi\Dependency\Client\ProductBundleCartsRestApiToProductBundleClientInterface $productBundleClient
     * @param \Spryker\Glue\ProductBundleCartsRestApi\Dependency\RestResource\ProductBundleCartsRestApiToCartsRestApiResourceInterface $cartsRestApiResource
     * @param \Spryker\Glue\ProductBundleCartsRestApi\Processor\RestResponseBuilder\BundleItemRestResponseBuilderInterface $bundleItemRestResponseBuilder
     */
    public function __construct(
        ProductBundleCartsRestApiToProductBundleClientInterface $productBundleClient,
        ProductBundleCartsRestApiToCartsRestApiResourceInterface $cartsRestApiResource,
        BundleItemRestResponseBuilderInterface $bundleItemRestResponseBuilder
    ) {
        $this->productBundleClient = $productBundleClient;
        $this->cartsRestApiResource = $cartsRestApiResource;
        $this->bundleItemRestResponseBuilder = $bundleItemRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addBundledItemResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $localeName = $restRequest->getMetadata()->getLocale();

        foreach ($resources as $resource) {
            if (!$resource->getPayload() || !$resource->getPayload() instanceof QuoteTransfer) {
                continue;
            }

            /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
            $quoteTransfer = $resource->getPayload();

            $groupedBundledItems = $this->getGroupedBundledItems($quoteTransfer);

            $this->addRelationships($groupedBundledItems, $resource, $localeName);
        }
    }

    /**
     * @phpstan-return array<string, array<\Generated\Shared\Transfer\ItemTransfer>>
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[][]
     */
    protected function getGroupedBundledItems(QuoteTransfer $quoteTransfer): array
    {
        $groupedBundleItems = $this->productBundleClient
            ->getGroupedBundleItems($quoteTransfer->getItems(), $quoteTransfer->getBundleItems());

        /** @var \Generated\Shared\Transfer\ItemTransfer[] $bundledItemTransfers */
        $bundledItemTransfers = [];
        foreach ($groupedBundleItems as $groupedBundleItem) {
            if ($groupedBundleItem instanceof ItemTransfer) {
                continue;
            }

            $bundledItemTransfers[$groupedBundleItem[static::BUNDLE_PRODUCT]->getGroupKey()]
                = $groupedBundleItem[static::BUNDLE_ITEMS];
        }

        return $bundledItemTransfers;
    }

    /**
     * @phpstan-param array<string, array<\Generated\Shared\Transfer\ItemTransfer>> $groupedBundledItems
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[][] $groupedBundledItems
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     * @param string $localeName
     *
     * @return void
     */
    protected function addRelationships(array $groupedBundledItems, RestResourceInterface $restResource, string $localeName): void
    {
        foreach ($groupedBundledItems as $itemGroupKey => $groupedBundledItem) {
            if ($itemGroupKey !== $restResource->getId()) {
                continue;
            }

            foreach ($groupedBundledItem as $bundledItemTransfer) {
                $restItemsAttributesTransfer = $this->cartsRestApiResource->mapItemTransferToRestItemsAttributesTransfer(
                    $bundledItemTransfer,
                    (new RestItemsAttributesTransfer()),
                    $localeName
                );

                $bundledItemRestResource = $this->bundleItemRestResponseBuilder
                    ->createBundledItemResource($bundledItemTransfer, $restItemsAttributesTransfer);

                $restResource->addRelationship($bundledItemRestResource);
            }
        }
    }
}
