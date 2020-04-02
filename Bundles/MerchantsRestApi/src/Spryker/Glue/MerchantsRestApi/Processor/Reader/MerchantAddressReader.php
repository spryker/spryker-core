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
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantAddressRestResponseBuilderInterface;

class MerchantAddressReader implements MerchantAddressReaderInterface
{
    /**
     * @var \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface
     */
    protected $merchantStorageClient;

    /**
     * @var \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantAddressRestResponseBuilderInterface
     */
    protected $merchantsAddressRestResponseBuilder;

    /**
     * @param \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface $merchantStorageClient
     * @param \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantAddressRestResponseBuilderInterface $merchantsAddressRestResponseBuilder
     */
    public function __construct(
        MerchantsRestApiToMerchantsStorageClientInterface $merchantStorageClient,
        MerchantAddressRestResponseBuilderInterface $merchantsAddressRestResponseBuilder
    ) {
        $this->merchantStorageClient = $merchantStorageClient;
        $this->merchantsAddressRestResponseBuilder = $merchantsAddressRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getMerchantAddresses(RestRequestInterface $restRequest): RestResponseInterface
    {
        $merchantResource = $restRequest->findParentResourceByType(MerchantsRestApiConfig::RESOURCE_MERCHANTS);
        if (!$merchantResource || !$merchantResource->getId()) {
            return $this->merchantsAddressRestResponseBuilder->createMerchantIdentifierMissingErrorResponse();
        }

        $merchantStorageTransfer = $this->merchantStorageClient->findOneByMerchantReference($restRequest->getResource()->getId());
        if (!$merchantStorageTransfer) {
            return $this->merchantsAddressRestResponseBuilder->createMerchantNotFoundErrorResponse();
        }

        $merchantStorageProfileAddressTransfers = $merchantStorageTransfer
            ->getMerchantStorageProfile()
            ->getAddressCollection();

        return $this->merchantsAddressRestResponseBuilder->createMerchantAddressesRestResponse(
            $merchantStorageProfileAddressTransfers,
            $merchantResource->getId()
        );
    }
}
