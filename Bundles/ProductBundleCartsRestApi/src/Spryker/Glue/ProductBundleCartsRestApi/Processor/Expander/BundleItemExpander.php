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
use Spryker\Glue\ProductBundleCartsRestApi\ProductBundleCartsRestApiConfig;

class BundleItemExpander implements BundleItemExpanderInterface
{
    /**
     * @uses \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper::BUNDLE_ITEMS
     */
    protected const BUNDLE_ITEMS = 'bundleItems';

    /**
     * @uses \Spryker\Client\ProductBundle\Grouper\ProductBundleGrouper::BUNDLE_PRODUCT
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
    public function addBundleItemResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            if (!$this->isCartsResourceValid($resource)) {
                continue;
            }

            /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
            $quoteTransfer = $resource->getPayload();

            $bundleItemTransfers = $this->getGroupedBundleItems($quoteTransfer);

            foreach ($bundleItemTransfers as $bundleItemTransfer) {
                $restItemsAttributesTransfer = $this->cartsRestApiResource->mapItemTransferToRestItemsAttributesTransfer(
                    $bundleItemTransfer,
                    (new RestItemsAttributesTransfer()),
                    $restRequest->getMetadata()->getLocale()
                );

                $bundleItemRestResource = $this->bundleItemRestResponseBuilder
                    ->createBundleItemResource($quoteTransfer, $bundleItemTransfer, $restItemsAttributesTransfer);

                $resource->addRelationship($bundleItemRestResource);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addGuestBundleItemResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            if (!$this->isGuestCartsResourceValid($resource)) {
                continue;
            }

            /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
            $quoteTransfer = $resource->getPayload();

            $bundleItemTransfers = $this->getGroupedBundleItems($quoteTransfer);

            foreach ($bundleItemTransfers as $bundleItemTransfer) {
                $restItemsAttributesTransfer = $this->cartsRestApiResource->mapItemTransferToRestItemsAttributesTransfer(
                    $bundleItemTransfer,
                    (new RestItemsAttributesTransfer()),
                    $restRequest->getMetadata()->getLocale()
                );

                $bundleItemRestResource = $this->bundleItemRestResponseBuilder
                    ->createGuestBundleItemResource($quoteTransfer, $bundleItemTransfer, $restItemsAttributesTransfer);

                $resource->addRelationship($bundleItemRestResource);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function getGroupedBundleItems(QuoteTransfer $quoteTransfer): array
    {
        $groupedBundleItems = $this->productBundleClient
            ->getGroupedBundleItems($quoteTransfer->getItems(), $quoteTransfer->getBundleItems());

        /** @var \Generated\Shared\Transfer\ItemTransfer[] $bundleItemTransfers */
        $bundleItemTransfers = [];
        foreach ($groupedBundleItems as $groupedBundleItem) {
            if ($groupedBundleItem instanceof ItemTransfer) {
                continue;
            }

            $bundleItemTransfers[] = $groupedBundleItem[static::BUNDLE_PRODUCT];
        }

        return $bundleItemTransfers;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return bool
     */
    protected function isCartsResourceValid(RestResourceInterface $resource): bool
    {
        return $resource->getType() === ProductBundleCartsRestApiConfig::RESOURCE_CARTS
            && $resource->getPayload()
            && $resource->getPayload() instanceof QuoteTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return bool
     */
    protected function isGuestCartsResourceValid(RestResourceInterface $resource): bool
    {
        return $resource->getType() === ProductBundleCartsRestApiConfig::RESOURCE_GUEST_CARTS
            && $resource->getPayload()
            && $resource->getPayload() instanceof QuoteTransfer;
    }
}
