<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesCartsRestApi\Processor\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestItemsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductBundlesCartsRestApi\Dependency\Client\ProductBundlesCartsRestApiToProductBundleClientInterface;
use Spryker\Glue\ProductBundlesCartsRestApi\Dependency\RestResource\ProductBundlesCartsRestApiToCartsRestApiResourceInterface;
use Spryker\Glue\ProductBundlesCartsRestApi\Processor\RestResponseBuilder\BundleItemRestResponseBuilderInterface;

class BundledItemExpander implements BundledItemExpanderInterface
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
     * @var \Spryker\Glue\ProductBundlesCartsRestApi\Dependency\Client\ProductBundlesCartsRestApiToProductBundleClientInterface
     */
    protected $productBundleClient;

    /**
     * @var \Spryker\Glue\ProductBundlesCartsRestApi\Dependency\RestResource\ProductBundlesCartsRestApiToCartsRestApiResourceInterface
     */
    protected $cartsRestApiResource;

    /**
     * @var \Spryker\Glue\ProductBundlesCartsRestApi\Processor\RestResponseBuilder\BundleItemRestResponseBuilderInterface
     */
    protected $bundleItemRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ProductBundlesCartsRestApi\Dependency\Client\ProductBundlesCartsRestApiToProductBundleClientInterface $productBundleClient
     * @param \Spryker\Glue\ProductBundlesCartsRestApi\Dependency\RestResource\ProductBundlesCartsRestApiToCartsRestApiResourceInterface $cartsRestApiResource
     * @param \Spryker\Glue\ProductBundlesCartsRestApi\Processor\RestResponseBuilder\BundleItemRestResponseBuilderInterface $bundleItemRestResponseBuilder
     */
    public function __construct(
        ProductBundlesCartsRestApiToProductBundleClientInterface $productBundleClient,
        ProductBundlesCartsRestApiToCartsRestApiResourceInterface $cartsRestApiResource,
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
        foreach ($resources as $resource) {
            if (!$resource->getPayload() || !$resource->getPayload() instanceof QuoteTransfer) {
                continue;
            }

            /** @var \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer */
            $quoteTransfer = $resource->getPayload();

            $groupedBundledItems = $this->getGroupedBundledItems($quoteTransfer);

            foreach ($groupedBundledItems as $itemGroupKey => $groupedBundledItem) {
                if ($itemGroupKey !== $resource->getId()) {
                    continue;
                }

                foreach ($groupedBundledItem as $bundledItemTransfer) {
                    $restItemsAttributesTransfer = $this->cartsRestApiResource->mapItemTransferToRestItemsAttributesTransfer(
                        $bundledItemTransfer,
                        (new RestItemsAttributesTransfer()),
                        $restRequest->getMetadata()->getLocale()
                    );

                    $bundledItemRestResource = $this->bundleItemRestResponseBuilder
                        ->createBundledItemResource($bundledItemTransfer, $restItemsAttributesTransfer);

                    $resource->addRelationship($bundledItemRestResource);
                }
            }
        }
    }

    /**
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
}
