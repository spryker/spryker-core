<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface
     */
    protected $merchantStorageClient;

    /**
     * @var \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface
     */
    protected $merchantsRestResponseBuilder;

    /**
     * @param \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface $merchantStorageClient
     * @param \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface $merchantsRestResponseBuilder
     */
    public function __construct(
        MerchantsRestApiToMerchantsStorageClientInterface $merchantStorageClient,
        MerchantRestResponseBuilderInterface $merchantsRestResponseBuilder
    ) {
        $this->merchantStorageClient = $merchantStorageClient;
        $this->merchantsRestResponseBuilder = $merchantsRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getMerchantById(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$restRequest->getResource()->getId()) {
            return $this->merchantsRestResponseBuilder->createMerchantIdentifierMissingErrorResponse();
        }

        $merchantStorageTransfer = $this->merchantStorageClient->findByMerchantReference([$restRequest->getResource()->getId()])[0] ?? null;
        if (!$merchantStorageTransfer) {
            return $this->merchantsRestResponseBuilder->createMerchantNotFoundErrorResponse();
        }

        return $this->merchantsRestResponseBuilder->createMerchantsRestResponse($merchantStorageTransfer);
    }
}
