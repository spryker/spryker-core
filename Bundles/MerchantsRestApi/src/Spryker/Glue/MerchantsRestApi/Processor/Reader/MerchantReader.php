<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Reader;

use Generated\Shared\Transfer\MerchantSearchRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantSearchClientInterface;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantStorageClientInterface;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Translator\MerchantTranslatorInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @uses @todo
     */
    protected const QUERY_STRING_PARAMETER = 'q';

    /**
     * @uses @todo
     */
    protected const PARAMETER_OFFSET = 'offset';

    /**
     * @uses @todo
     */
    protected const PARAMETER_LIMIT = 'limit';

    /**
     * @uses @todo
     */
    protected const KEY_MERCHANT_COLLECTION = 'MercahantCollection';

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
     * @var \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantSearchClientInterface
     */
    protected $merchantSearchClient;

    /**
     * @param \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantStorageClientInterface $merchantStorageClient
     * @param \Spryker\Glue\MerchantsRestApi\Processor\Translator\MerchantTranslatorInterface $merchantTranslator
     * @param \Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface $merchantsRestResponseBuilder
     * @param \Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantSearchClientInterface $merchantSearchClient
     */
    public function __construct(
        MerchantsRestApiToMerchantStorageClientInterface $merchantStorageClient,
        MerchantTranslatorInterface $merchantTranslator,
        MerchantRestResponseBuilderInterface $merchantsRestResponseBuilder,
        MerchantsRestApiToMerchantSearchClientInterface $merchantSearchClient
    ) {
        $this->merchantStorageClient = $merchantStorageClient;
        $this->merchantTranslator = $merchantTranslator;
        $this->merchantRestResponseBuilder = $merchantsRestResponseBuilder;
        $this->merchantSearchClient = $merchantSearchClient;
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
    public function get(RestRequestInterface $restRequest): RestResponseInterface
    {
        if ($restRequest->getResource()->getId()) {
            return $this->getMerchant($restRequest);
        }

        return $this->getMerchants($restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getMerchant(RestRequestInterface $restRequest): RestResponseInterface
    {
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

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getMerchants(RestRequestInterface $restRequest): RestResponseInterface
    {
        $merchantSearchRequestTransfer = $this->createMerchantSearchRequest($restRequest);
        $searchResult = $this->merchantSearchClient->searchMerchants(
            $merchantSearchRequestTransfer
        );

        /** @var \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer */
        $merchantSearchCollectionTransfer = $searchResult[static::KEY_MERCHANT_COLLECTION];

        return $this->merchantRestResponseBuilder->createMerchantListRestResponse(
            $merchantSearchRequestTransfer,
            $merchantSearchCollectionTransfer,
            $restRequest->getMetadata()->getLocale()
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\MerchantSearchRequestTransfer
     */
    protected function createMerchantSearchRequest(RestRequestInterface $restRequest): MerchantSearchRequestTransfer
    {
        $page = $restRequest->getPage();
        $requestParameters = [];

        if ($page) {
            $requestParameters[static::PARAMETER_OFFSET] = $page->getOffset();
            $requestParameters[static::PARAMETER_LIMIT] = $page->getLimit();
        }

        return (new MerchantSearchRequestTransfer())->setRequestParameters($requestParameters);
    }
}
