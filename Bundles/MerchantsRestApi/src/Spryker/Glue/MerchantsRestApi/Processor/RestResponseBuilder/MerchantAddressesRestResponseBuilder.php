<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestMerchantAddressesAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\MerchantsRestApi\MerchantsRestApiConfig;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantAddressesMapperInterface;
use Symfony\Component\HttpFoundation\Response;

class MerchantAddressesRestResponseBuilder implements MerchantAddressesRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantAddressesMapperInterface
     */
    protected $merchantsAddressesResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantAddressesMapperInterface $merchantsAddressesResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        MerchantAddressesMapperInterface $merchantsAddressesResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->merchantsAddressesResourceMapper = $merchantsAddressesResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageProfileAddressTransfer[] $merchantStorageProfileAddressTransfers
     * @param string $merchantReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createMerchantAddressesRestResource(array $merchantStorageProfileAddressTransfers, string $merchantReference): RestResourceInterface
    {
        $restMerchantsAttributesTransfer = $this->merchantsAddressesResourceMapper
            ->mapMerchantStorageProfileAddressTransfersToRestMerchantAddressesAttributesTransfer(
                $merchantStorageProfileAddressTransfers,
                new RestMerchantAddressesAttributesTransfer()
            );

        return $this->restResourceBuilder->createRestResource(
            MerchantsRestApiConfig::RESOURCE_MERCHANT_ADDRESSES,
            $merchantReference,
            $restMerchantsAttributesTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageProfileAddressTransfer[] $merchantStorageProfileAddressTransfers
     * @param string $merchantReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantAddressesRestResponse(array $merchantStorageProfileAddressTransfers, string $merchantReference): RestResponseInterface
    {
        $merchantsRestResource = $this->createMerchantAddressesRestResource($merchantStorageProfileAddressTransfers, $merchantReference);

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addResource($merchantsRestResource);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantNotFoundErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_NOT_FOUND)
                    ->setCode(MerchantsRestApiConfig::RESPONSE_CODE_MERCHANT_NOT_FOUND)
                    ->setDetail(MerchantsRestApiConfig::RESPONSE_DETAIL_MERCHANT_NOT_FOUND)
            );
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantIdentifierMissingErrorResponse(): RestResponseInterface
    {
        return $this->restResourceBuilder->createRestResponse()
            ->addError(
                (new RestErrorMessageTransfer())
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setCode(MerchantsRestApiConfig::RESPONSE_CODE_MERCHANT_IDENTIFIER_MISSING)
                    ->setDetail(MerchantsRestApiConfig::RESPONSE_DETAIL_MERCHANT_IDENTIFIER_MISSING)
            );
    }
}
