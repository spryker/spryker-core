<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface;
use Spryker\Glue\MerchantsRestApi\MerchantsRestApiConfig;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantAddressesRestResponseBuilderInterface;

class MerchantAddressesReader implements MerchantAddressesReaderInterface
{
    /**
     * @var \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface
     */
    protected $merchantStorageClient;

    /**
     * @var \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantAddressesRestResponseBuilderInterface
     */
    protected $merchantsAddressesRestResponseBuilder;

    /**
     * @param \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface $merchantStorageClient
     * @param \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantAddressesRestResponseBuilderInterface $merchantsAddressesRestResponseBuilder
     */
    public function __construct(
        MerchantsRestApiToMerchantsStorageClientInterface $merchantStorageClient,
        MerchantAddressesRestResponseBuilderInterface $merchantsAddressesRestResponseBuilder
    ) {
        $this->merchantStorageClient = $merchantStorageClient;
        $this->merchantsAddressesRestResponseBuilder = $merchantsAddressesRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getMerchantAddresses(RestRequestInterface $restRequest): RestResponseInterface
    {
        $merchantReference = $this->findMerchantIdentifier($restRequest);
        if (!$merchantReference) {
            return $this->merchantsAddressesRestResponseBuilder->createMerchantIdentifierMissingErrorResponse();
        }

        $merchantStorageTransfer = $this->merchantStorageClient->findByMerchantReference([$restRequest->getResource()->getId()])[0] ?? null;
        if (!$merchantStorageTransfer) {
            return $this->merchantsAddressesRestResponseBuilder->createMerchantNotFoundErrorResponse();
        }

        $merchantStorageProfileAddressTransfers = $merchantStorageTransfer
            ->getMerchantStorageProfile()
            ->getAddressCollection()
            ->getArrayCopy();

        return $this->merchantsAddressesRestResponseBuilder->createMerchantAddressesRestResponse(
            $merchantStorageProfileAddressTransfers,
            $merchantReference
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string|null
     */
    protected function findMerchantIdentifier(RestRequestInterface $restRequest): ?string
    {
        $merchantResource = $restRequest->findParentResourceByType(MerchantsRestApiConfig::RESOURCE_MERCHANTS);
        if ($merchantResource) {
            return $merchantResource->getId();
        }

        return null;
    }
}
