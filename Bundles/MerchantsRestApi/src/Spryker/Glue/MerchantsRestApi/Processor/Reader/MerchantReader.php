<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantsRestApi\Processor\Reader;

use Generated\Shared\Transfer\MerchantSearchCollectionTransfer;
use Generated\Shared\Transfer\MerchantSearchRequestTransfer;
use Generated\Shared\Transfer\MerchantStorageCriteriaTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantSearchClientInterface;
use Spryker\Glue\MerchantsRestApi\Dependency\Client\MerchantsRestApiToMerchantStorageClientInterface;
use Spryker\Glue\MerchantsRestApi\Processor\RestResponseBuilder\MerchantRestResponseBuilderInterface;
use Spryker\Glue\MerchantsRestApi\Processor\Translator\MerchantTranslatorInterface;

class MerchantReader implements MerchantReaderInterface
{
    /**
     * @uses \Spryker\Client\MerchantSearch\Plugin\Elasticsearch\Query\PaginatedMerchantSearchQueryExpanderPlugin::PARAMETER_OFFSET
     * @var string
     */
    protected const PARAMETER_OFFSET = 'offset';

    /**
     * @uses \Spryker\Client\MerchantSearch\Plugin\Elasticsearch\Query\PaginatedMerchantSearchQueryExpanderPlugin::PARAMETER_LIMIT
     * @var string
     */
    protected const PARAMETER_LIMIT = 'limit';

    /**
     * @uses \Spryker\Client\MerchantSearch\Plugin\Elasticsearch\ResultFormatter\MerchantSearchResultFormatterPlugin::NAME
     * @var string
     */
    protected const KEY_MERCHANT_SEARCH_COLLECTION = 'MerchantSearchCollection';

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
     * @param array<string> $merchantReferences
     * @param string $localeName
     *
     * @return array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     */
    public function getMerchantsResources(array $merchantReferences, string $localeName): array
    {
        $merchantStorageTransfers = $this->merchantStorageClient->get(
            (new MerchantStorageCriteriaTransfer())->setMerchantReferences($merchantReferences),
        );

        $translatedMerchantStorageTransfers = $this->merchantTranslator->translateMerchantStorageTransfers(
            $merchantStorageTransfers,
            $localeName,
        );

        return $this->merchantRestResponseBuilder->createMerchantRestResources($translatedMerchantStorageTransfers, $localeName);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getMerchant(RestRequestInterface $restRequest): RestResponseInterface
    {
        /**
         * @var string $merchantReference
         */
        $merchantReference = $restRequest->getResource()->getId();
        $merchantStorageTransfer = $this->merchantStorageClient->findOne(
            (new MerchantStorageCriteriaTransfer())->addMerchantReference($merchantReference),
        );

        if (!$merchantStorageTransfer) {
            return $this->merchantRestResponseBuilder->createMerchantNotFoundErrorResponse();
        }

        $translatedMerchantStorageTransfer = $this->merchantTranslator->translateMerchantStorageTransfer(
            $merchantStorageTransfer,
            $restRequest->getMetadata()->getLocale(),
        );

        return $this->merchantRestResponseBuilder->createMerchantsRestResponse(
            $translatedMerchantStorageTransfer,
            $restRequest->getMetadata()->getLocale(),
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getMerchants(RestRequestInterface $restRequest): RestResponseInterface
    {
        $merchantSearchRequestTransfer = $this->createMerchantSearchRequest($restRequest);
        $searchResult = $this->merchantSearchClient->search(
            $merchantSearchRequestTransfer,
        );

        /** @var \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer */
        $merchantSearchCollectionTransfer = $searchResult[static::KEY_MERCHANT_SEARCH_COLLECTION];

        $merchantStorageCriteriaTransfer = (new MerchantStorageCriteriaTransfer())->setMerchantIds(
            $this->extractMerchantIds($merchantSearchCollectionTransfer),
        );

        $merchantStorageTransfers = $this->merchantStorageClient->get($merchantStorageCriteriaTransfer);

        $merchantStorageTransfers = $this->merchantTranslator->translateMerchantStorageTransfers(
            $merchantStorageTransfers,
            $restRequest->getMetadata()->getLocale(),
        );

        return $this->merchantRestResponseBuilder->createMerchantListRestResponse(
            $merchantSearchRequestTransfer,
            $merchantSearchCollectionTransfer,
            $merchantStorageTransfers,
            $restRequest->getMetadata()->getLocale(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer
     *
     * @return array<int>
     */
    protected function extractMerchantIds(MerchantSearchCollectionTransfer $merchantSearchCollectionTransfer): array
    {
        $merchantIds = [];

        foreach ($merchantSearchCollectionTransfer->getMerchants() as $merchantSearchTransfer) {
            /**
             * @var int $idMerchant
             */
            $idMerchant = $merchantSearchTransfer->requireIdMerchant()->getIdMerchant();

            $merchantIds[] = $idMerchant;
        }

        return $merchantIds;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\MerchantSearchRequestTransfer
     */
    protected function createMerchantSearchRequest(RestRequestInterface $restRequest): MerchantSearchRequestTransfer
    {
        $page = $restRequest->getPage();
        $requestParameters = $restRequest->getHttpRequest()->query->all();

        if ($page) {
            $requestParameters[static::PARAMETER_OFFSET] = $page->getOffset();
            $requestParameters[static::PARAMETER_LIMIT] = $page->getLimit();
        }

        return (new MerchantSearchRequestTransfer())->setRequestParameters($requestParameters);
    }
}
