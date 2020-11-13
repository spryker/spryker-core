<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestMerchantAddressesAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\MerchantsRestApi\MerchantsRestApiConfig;
use Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantAddressMapperInterface;
use Symfony\Component\HttpFoundation\Response;

class MerchantAddressRestResponseBuilder implements MerchantAddressRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantAddressMapperInterface
     */
    protected $merchantsAddressResourceMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\MerchantsRestApi\Processor\Mapper\MerchantAddressMapperInterface $merchantsAddressResourceMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        MerchantAddressMapperInterface $merchantsAddressResourceMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->merchantsAddressResourceMapper = $merchantsAddressResourceMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer[] $merchantStorageTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createMerchantAddressesRestResources(array $merchantStorageTransfers): array
    {
        $merchantAddressRestResources = [];

        foreach ($merchantStorageTransfers as $merchantReference => $merchantStorageTransfer) {
            if (!$merchantStorageTransfer->getMerchantProfile()) {
                continue;
            }
            /**
             * @var \Generated\Shared\Transfer\MerchantStorageProfileTransfer $merchantStorageProfileTransfer
             */
            $merchantStorageProfileTransfer = $merchantStorageTransfer->getMerchantProfile();
            $merchantAddressRestResources[$merchantReference] = $this->createMerchantAddressesRestResource(
                $merchantStorageProfileTransfer->getAddressCollection(),
                $merchantReference
            );
        }

        return $merchantAddressRestResources;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MerchantStorageProfileAddressTransfer[] $merchantStorageProfileAddressTransfers
     * @param string $merchantReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createMerchantAddressesRestResponse(
        ArrayObject $merchantStorageProfileAddressTransfers,
        string $merchantReference
    ): RestResponseInterface {
        $merchantAddressesRestResource = $this->createMerchantAddressesRestResource(
            $merchantStorageProfileAddressTransfers,
            $merchantReference
        );

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addResource($merchantAddressesRestResource);
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

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MerchantStorageProfileAddressTransfer[] $merchantStorageProfileAddressTransfers
     * @param string $merchantReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createMerchantAddressesRestResource(
        ArrayObject $merchantStorageProfileAddressTransfers,
        string $merchantReference
    ): RestResourceInterface {
        $restMerchantAddressesAttributesTransfer = $this->merchantsAddressResourceMapper
            ->mapMerchantStorageProfileAddressTransfersToRestMerchantAddressesAttributesTransfer(
                $merchantStorageProfileAddressTransfers,
                new RestMerchantAddressesAttributesTransfer()
            );

        $merchantAddressesRestResource = $this->restResourceBuilder->createRestResource(
            MerchantsRestApiConfig::RESOURCE_MERCHANT_ADDRESSES,
            $merchantReference,
            $restMerchantAddressesAttributesTransfer
        );

        $merchantAddressesRestResource->addLink(
            RestLinkInterface::LINK_SELF,
            $this->getMerchantAddressesResourceSelfLink($merchantReference)
        );

        return $merchantAddressesRestResource;
    }

    /**
     * @param string $merchantReference
     *
     * @return string
     */
    protected function getMerchantAddressesResourceSelfLink(string $merchantReference): string
    {
        return sprintf(
            '%s/%s/%s',
            MerchantsRestApiConfig::RESOURCE_MERCHANTS,
            $merchantReference,
            MerchantsRestApiConfig::RESOURCE_MERCHANT_ADDRESSES
        );
    }
}
