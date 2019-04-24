<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Processor\SharedCart;

use Generated\Shared\Transfer\RestSharedCartsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiConfig;

class SharedCartReader implements SharedCartReaderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface
     */
    protected $sharedCartsRestApiClient;

    /**
     * @param array $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function getSharedCartsByCartUuid(array $resources, RestRequestInterface $restRequest): RestResourceInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $quoteUuid = $restRequest->getResource()->getId();
//        if (!$quoteUuid) {
//            return $restResponse->addError($this->createNavigationIdMissingError());
//        }

        $shareDetailCollectionTransfer = $this->sharedCartsRestApiClient->getSharedCartsByCartUuid($restRequest);

        $sharedCartAttributesTransfers = [];
        foreach ($shareDetailCollectionTransfer->getShareDetails() as $shareDetailTransfer) {
            $sharedCartAttributesTransfers[] = (new RestSharedCartsAttributesTransfer())
                ->setIdCompanyUser(
                    $shareDetailTransfer->getIdCompanyUser()
                )
                ->setIdCartPermissionGroup(
                    $shareDetailTransfer->getQuotePermissionGroup()->getIdQuotePermissionGroup()
                );
        }


        $this->buildSharedCartsResource($quoteUuid, $restResource);
//        if (!$restResource) {
//            return $restResponse->addError($this->createNavigationNotFoundError());
//        }

        return $restResponse->addResource($restResource);
//        call the SharedCartsRestApiClient::getSharedCartsByCartUuid().

    }

    protected function buildSharedCartsResource(string $uuid, RestSharedCartsAttributesTransfer $restSharedCartsAttributesTransfer): ?RestResourceInterface
    {
        $restResource = $this->restResourceBuilder->createRestResource(
            SharedCartsRestApiConfig::RESOURCE_SHARED_CARTS,
            $uuid,
            $restSharedCartsAttributesTransfer
        );

//        $restResourceSelfLink = sprintf(
//            static::SELF_LINK_TEMPLATE,
//            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
//            $sku,
//            ProductPricesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_PRICES
//        );
//        $restResource->addLink(RestLinkInterface::LINK_SELF, $restResourceSelfLink);

        return $restResource;
    }
}
