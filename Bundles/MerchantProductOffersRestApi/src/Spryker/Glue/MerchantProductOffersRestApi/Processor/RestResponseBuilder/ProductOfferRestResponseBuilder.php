<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestProductOffersAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\MerchantProductOffersRestApi\MerchantProductOffersRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductOfferRestResponseBuilder implements ProductOfferRestResponseBuilderInterface
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
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferIdNotSpecifierErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(MerchantProductOffersRestApiConfig::RESPONSE_CODE_PRODUCT_OFFER_ID_IS_NOT_SPECIFIED)
            ->setDetail(MerchantProductOffersRestApiConfig::RESPONSE_DETAIL_PRODUCT_OFFER_ID_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferNotFoundErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(MerchantProductOffersRestApiConfig::RESPONSE_CODE_PRODUCT_OFFER_NOT_FOUND)
            ->setDetail(MerchantProductOffersRestApiConfig::RESPONSE_DETAIL_PRODUCT_OFFER_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     * @param string $defaultMerchantProductOfferReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferRestResponse(
        ProductOfferStorageTransfer $productOfferStorageTransfer,
        string $defaultMerchantProductOfferReference
    ): RestResponseInterface {
        $restProductOffersAttributesTransfer = (new RestProductOffersAttributesTransfer())
            ->fromArray($productOfferStorageTransfer->toArray(), true)
            ->setIsDefault($defaultMerchantProductOfferReference === $productOfferStorageTransfer->getProductOfferReference());

        $productOffersRestResource = $this->restResourceBuilder->createRestResource(
            MerchantProductOffersRestApiConfig::RESOURCE_PRODUCT_OFFERS,
            $productOfferStorageTransfer->getProductOfferReference(),
            $restProductOffersAttributesTransfer
        );

        return $this->restResourceBuilder->createRestResponse()->addResource($productOffersRestResource);
    }
}
