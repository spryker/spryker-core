<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantsRestResponseBuilderInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface
     */
    protected $merchantsStorageClient;

    /**
     * @var \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantsRestResponseBuilderInterface
     */
    protected $merchantsRestResponseBuilder;

    /**
     * @param \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantsStorageClientInterface $merchantsStorageClient
     * @param \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantsRestResponseBuilderInterface $merchantsRestResponseBuilder
     */
    public function __construct(
        MerchantsRestApiToMerchantsStorageClientInterface $merchantsStorageClient,
        MerchantsRestResponseBuilderInterface $merchantsRestResponseBuilder
    ) {
        $this->merchantsStorageClient = $merchantsStorageClient;
        $this->merchantsRestResponseBuilder = $merchantsRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getMerchantById(RestRequestInterface $restRequest): RestResponseInterface
    {
        $merchantStorageTransfer = $this->merchantsStorageClient->findByMerchantReference([$restRequest->getResource()->getId()])[0] ?? null;

        if (!$merchantStorageTransfer) {
            return $this->merchantsRestResponseBuilder->createMerchantNotFoundError();
        }

        return $this->merchantsRestResponseBuilder->createMerchantsRestResponse($merchantStorageTransfer);
    }
}
