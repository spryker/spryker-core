<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestProductOffersAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
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
    public function createProductOfferEmptyRestResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $productOfferRestResources
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferCollectionRestResponse(
        array $productOfferRestResources
    ): RestResponseInterface {
        $productOffersRestResponse = $this->restResourceBuilder->createRestResponse();
        foreach ($productOfferRestResources as $productOfferRestResource) {
            $productOffersRestResponse->addResource($productOfferRestResource);
        }

        return $productOffersRestResponse;
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
        $productOffersRestResource = $this->createProductOfferRestResource($productOfferStorageTransfer, $defaultMerchantProductOfferReference);

        return $this->restResourceBuilder->createRestResponse()->addResource($productOffersRestResource);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function createProductOfferRestResources(
        ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
    ): array {
        $productOffersRestResources = [];

        foreach ($productOfferStorageCollectionTransfer->getProductOffers() as $productOffer) {
            $productOffersRestResources[$productOffer->getProductConcreteSku()][] =
                $this->createProductOfferRestResource($productOffer);
        }

        return $productOffersRestResources;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductConcreteSkuNotSpecifiedErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setCode(MerchantProductOffersRestApiConfig::RESPONSE_CODE_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setDetail(MerchantProductOffersRestApiConfig::RESPONSE_DETAIL_CONCRETE_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST);

        return $this->restResourceBuilder->createRestResponse()->addError($restErrorMessageTransfer);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferIdNotSpecifiedErrorResponse(): RestResponseInterface
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
     * @param string|null $defaultMerchantProductOfferReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createProductOfferRestResource(
        ProductOfferStorageTransfer $productOfferStorageTransfer,
        ?string $defaultMerchantProductOfferReference = null
    ): RestResourceInterface {
        $restProductOffersAttributesTransfer = (new RestProductOffersAttributesTransfer())
            ->fromArray($productOfferStorageTransfer->toArray(), true);

        if ($defaultMerchantProductOfferReference) {
            $restProductOffersAttributesTransfer->setIsDefault($defaultMerchantProductOfferReference === $productOfferStorageTransfer->getProductOfferReference());
        }

        return $this->restResourceBuilder->createRestResource(
            MerchantProductOffersRestApiConfig::RESOURCE_PRODUCT_OFFERS,
            $productOfferStorageTransfer->getProductOfferReference(),
            $restProductOffersAttributesTransfer
        );
    }
}
