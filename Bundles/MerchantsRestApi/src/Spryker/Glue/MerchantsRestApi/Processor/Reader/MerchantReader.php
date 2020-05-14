<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantStorageClientInterface;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Translator\MerchantTranslatorInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @var \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantStorageClientInterface
     */
    protected $merchantStorageClient;

    /**
     * @var \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface
     */
    protected $merchantRestResponseBuilder;

    /**
     * @var \Spryker\Glue\MerchantsRestApi\Processor\Translator\MerchantTranslatorInterface
     */
    protected $merchantTranslator;

    /**
     * @param \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantStorageClientInterface $merchantStorageClient
     * @param \Spryker\Glue\MerchantsRestApi\Processor\Translator\MerchantTranslatorInterface $merchantTranslator
     * @param \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface $merchantsRestResponseBuilder
     */
    public function __construct(
        MerchantsRestApiToMerchantStorageClientInterface $merchantStorageClient,
        MerchantTranslatorInterface $merchantTranslator,
        MerchantRestResponseBuilderInterface $merchantsRestResponseBuilder
    ) {
        $this->merchantStorageClient = $merchantStorageClient;
        $this->merchantTranslator = $merchantTranslator;
        $this->merchantRestResponseBuilder = $merchantsRestResponseBuilder;
    }

    /**
     * @param string[] $merchantReferences
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getMerchantsResources(array $merchantReferences, string $localeName): array
    {
        $merchantStorageTransfers = $this->merchantStorageClient->getByMerchantReferences($merchantReferences);

        $translatedMerchantStorageTransfers = $this->merchantTranslator->translateMerchantStorageTransfers(
            $merchantStorageTransfers,
            $localeName
        );

        return $this->merchantRestResponseBuilder->createMerchantRestResources($translatedMerchantStorageTransfers, $localeName);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getMerchantById(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$restRequest->getResource()->getId()) {
            return $this->merchantRestResponseBuilder->createMerchantIdentifierMissingErrorResponse();
        }

        $merchantStorageTransfer = $this->merchantStorageClient->findOneByMerchantReference($restRequest->getResource()->getId());
        if (!$merchantStorageTransfer) {
            return $this->merchantRestResponseBuilder->createMerchantNotFoundErrorResponse();
        }

        $translatedMerchantStorageTransfer = $this->merchantTranslator->translateMerchantStorageTransfer(
            $merchantStorageTransfer,
            $restRequest->getMetadata()->getLocale()
        );

        return $this->merchantRestResponseBuilder->createMerchantsRestResponse(
            $translatedMerchantStorageTransfer,
            $restRequest->getMetadata()->getLocale()
        );
    }
}
