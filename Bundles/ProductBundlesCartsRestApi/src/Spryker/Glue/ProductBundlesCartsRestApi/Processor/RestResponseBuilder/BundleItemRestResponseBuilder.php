<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductBundlesCartsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestItemsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\ProductBundlesCartsRestApi\ProductBundlesCartsRestApiConfig;

class BundleItemRestResponseBuilder implements BundleItemRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\RestItemsAttributesTransfer $restItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createBundleItemResource(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        RestItemsAttributesTransfer $restItemsAttributesTransfer
    ): RestResourceInterface {
        $bundleItemRestResource = $this->restResourceBuilder->createRestResource(
            ProductBundlesCartsRestApiConfig::RESOURCE_BUNDLE_ITEMS,
            $itemTransfer->getGroupKey(),
            $restItemsAttributesTransfer
        );
        $bundleItemRestResource->setPayload($quoteTransfer);

        $bundleItemSelfLink = sprintf(
            '%s/%s/%s/%s/',
            ProductBundlesCartsRestApiConfig::RESOURCE_CARTS,
            $quoteTransfer->getUuid(),
            ProductBundlesCartsRestApiConfig::RESOURCE_CART_ITEMS,
            $itemTransfer->getGroupKey()
        );

        return $bundleItemRestResource->addLink(RestLinkInterface::LINK_SELF, $bundleItemSelfLink);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $bundleItemTransfer
     * @param \Generated\Shared\Transfer\RestItemsAttributesTransfer $restItemsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createBundledItemResource(
        ItemTransfer $bundleItemTransfer,
        RestItemsAttributesTransfer $restItemsAttributesTransfer
    ): RestResourceInterface {
        return $this->restResourceBuilder->createRestResource(
            ProductBundlesCartsRestApiConfig::RESOURCE_BUNDLED_ITEMS,
            $bundleItemTransfer->getGroupKey(),
            $restItemsAttributesTransfer
        );
    }
}
