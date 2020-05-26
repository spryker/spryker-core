<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantStorageClientInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\MerchantOpeningHoursRestApiConfig;
use Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder\MerchantOpeningHoursRestResponseBuilderInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Translator\MerchantOpeningHoursTranslatorInterface;

class MerchantOpeningHoursReader implements MerchantOpeningHoursReaderInterface
{
    /**
     * @var \Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientInterface
     */
    protected $merchantOpeningHoursStorageClient;

    /**
     * @var \Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantStorageClientInterface
     */
    protected $merchantStorageClient;

    /**
     * @var \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder\MerchantOpeningHoursRestResponseBuilderInterface
     */
    protected $merchantOpeningHoursRestResponseBuilder;

    /**
     * @var \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Translator\MerchantOpeningHoursTranslatorInterface
     */
    protected $merchantOpeningHoursTranslator;

    /**
     * @param \Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientInterface $merchantOpeningHoursStorageClient
     * @param \Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantStorageClientInterface $merchantStorageClient
     * @param \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder\MerchantOpeningHoursRestResponseBuilderInterface $merchantOpeningHoursRestResponseBuilder
     * @param \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Translator\MerchantOpeningHoursTranslatorInterface $merchantOpeningHoursTranslator
     */
    public function __construct(
        MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientInterface $merchantOpeningHoursStorageClient,
        MerchantOpeningHoursRestApiToMerchantStorageClientInterface $merchantStorageClient,
        MerchantOpeningHoursRestResponseBuilderInterface $merchantOpeningHoursRestResponseBuilder,
        MerchantOpeningHoursTranslatorInterface $merchantOpeningHoursTranslator
    ) {
        $this->merchantOpeningHoursStorageClient = $merchantOpeningHoursStorageClient;
        $this->merchantStorageClient = $merchantStorageClient;
        $this->merchantOpeningHoursRestResponseBuilder = $merchantOpeningHoursRestResponseBuilder;
        $this->merchantOpeningHoursTranslator = $merchantOpeningHoursTranslator;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getMerchantOpeningHours(RestRequestInterface $restRequest): RestResponseInterface
    {
        $merchantResource = $restRequest->findParentResourceByType(MerchantOpeningHoursRestApiConfig::RESOURCE_MERCHANTS);

        if (!$merchantResource || !$merchantResource->getId()) {
            return $this->merchantOpeningHoursRestResponseBuilder->createMerchantIdentifierMissingErrorResponse();
        }
        $merchantReference = $merchantResource->getId();

        $merchantStorageTransfer = $this->merchantStorageClient->findOneByMerchantReference($merchantReference);
        if (!$merchantStorageTransfer) {
            return $this->merchantOpeningHoursRestResponseBuilder->createMerchantNotFoundErrorResponse();
        }

        $merchantOpeningHoursStorageTransfers = $this->merchantOpeningHoursStorageClient
            ->getMerchantOpeningHoursByMerchantIds([$merchantStorageTransfer->getIdMerchant()]);

        if (!$merchantOpeningHoursStorageTransfers) {
            return $this->merchantOpeningHoursRestResponseBuilder->createEmptyMerchantOpeningHoursRestResponse();
        }

        $merchantOpeningHoursStorageTransfers = $this->merchantOpeningHoursTranslator
            ->translateMerchantOpeningHoursTransfers(
                $merchantOpeningHoursStorageTransfers,
                $restRequest->getMetadata()->getLocale()
            );

        return $this->merchantOpeningHoursRestResponseBuilder->createMerchantOpeningHoursRestResponse(
            reset($merchantOpeningHoursStorageTransfers),
            $merchantReference
        );
    }

    /**
     * @param string[] $merchantReferences
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getMerchantOpeningHoursResources(array $merchantReferences, RestRequestInterface $restRequest): array
    {
        $merchantIdsIndexedByReference = $this->getMerchantIdsIndexedByReference($merchantReferences);

        $merchantOpeningHoursStorageTransfers = $this->getTranslatedMerchantOpeningHoursStorageTransfers(
            $merchantIdsIndexedByReference,
            $restRequest->getMetadata()->getLocale()
        );

        return $this->merchantOpeningHoursRestResponseBuilder
            ->createMerchantOpeningHoursRestResources($merchantOpeningHoursStorageTransfers);
    }

    /**
     * @param string[] $merchantReferences
     *
     * @return int[]
     */
    protected function getMerchantIdsIndexedByReference(array $merchantReferences): array
    {
        $merchantStorageTransfers = $this->merchantStorageClient->getByMerchantReferences($merchantReferences);

        $merchantIdsIndexedByReference = [];
        foreach ($merchantStorageTransfers as $merchantStorageTransfer) {
            $merchantIdsIndexedByReference[$merchantStorageTransfer->getMerchantReference()] = $merchantStorageTransfer->getIdMerchant();
        }

        return $merchantIdsIndexedByReference;
    }

    /**
     * @param int[] $merchantIdsIndexedByReference
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer[]
     */
    protected function getTranslatedMerchantOpeningHoursStorageTransfers(
        array $merchantIdsIndexedByReference,
        string $localeName
    ): array {
        $merchantOpeningHoursStorageTransfers = $this->merchantOpeningHoursStorageClient
            ->getMerchantOpeningHoursByMerchantIds($merchantIdsIndexedByReference);

        if (!array_filter($merchantOpeningHoursStorageTransfers)) {
            return [];
        }

        $indexedMerchantOpeningHoursStorageTransfers = $this->indexCollectionByMerchantReferences(
            $merchantOpeningHoursStorageTransfers,
            $merchantIdsIndexedByReference
        );

        return $this->merchantOpeningHoursTranslator
            ->translateMerchantOpeningHoursTransfers($indexedMerchantOpeningHoursStorageTransfers, $localeName);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer[] $merchantOpeningHoursStorageTransfers
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer[]
     */
    protected function indexCollectionByMerchantReferences(array $merchantOpeningHoursStorageTransfers, array $merchantIds): array
    {
        $flippedMerchantOpeningHoursStorageTransfers = [];
        $merchantReferencesIndexedById = array_flip($merchantIds);
        foreach ($merchantOpeningHoursStorageTransfers as $merchantId => $merchantOpeningHourStorageTransfers) {
            $flippedMerchantOpeningHoursStorageTransfers[$merchantReferencesIndexedById[$merchantId]] = $merchantOpeningHourStorageTransfers;
        }

        return $flippedMerchantOpeningHoursStorageTransfers;
    }
}
