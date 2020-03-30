<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantStorageClientInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder\MerchantOpeningHoursRestResponseBuilderInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Translator\MerchantOpeningHoursTranslatorInterface;

class MerchantOpeningHoursByMerchantReferenceResourceRelationshipExpander implements MerchantOpeningHoursByMerchantReferenceResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder\MerchantOpeningHoursRestResponseBuilderInterface
     */
    protected $merchantOpeningHoursRestResponseBuilder;

    /**
     * @var \Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantStorageClientInterface
     */
    protected $merchantStorageClient;

    /**
     * @var \Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientInterface
     */
    protected $merchantOpeningHoursStorageClient;

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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $merchantStorageTransfers = $this->getMerchantStorageTransfers($resources);

        $merchantOpeningHoursStorageTransfersWithTranslatedNotes = $this->getTranslatedMerchantOpeningHoursStorageTransfers(
            $merchantStorageTransfers,
            $restRequest
        );

        foreach ($resources as $resource) {
            $resourceId = $resource->getId();
            if (!$resourceId || !isset($merchantOpeningHoursStorageTransfersWithTranslatedNotes[$resourceId])) {
                continue;
            }

            $merchantOpeningHoursStorageTransfer = $merchantOpeningHoursStorageTransfersWithTranslatedNotes[$resourceId];
            $restMerchantOpeningHoursResource = $this->merchantOpeningHoursRestResponseBuilder->createMerchantOpeningHoursRestResource(
                $merchantOpeningHoursStorageTransfer,
                $resourceId
            );

            $resource->addRelationship($restMerchantOpeningHoursResource);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    protected function getMerchantStorageTransfers(array $resources): array
    {
        $merchantReferences = $this->getMerchantReferences($resources);

        return $this->merchantStorageClient->findByMerchantReference($merchantReferences);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer[] $merchantStorageTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer[]
     */
    protected function getTranslatedMerchantOpeningHoursStorageTransfers(array $merchantStorageTransfers, RestRequestInterface $restRequest): array
    {
        $merchantIdsIndexedByReference = $this->getMerchantIdsIndexedByReference($merchantStorageTransfers);

        $merchantOpeningHoursStorageTransfers = $this->merchantOpeningHoursStorageClient
            ->getMerchantOpeningHoursByMerchantIds($merchantIdsIndexedByReference);

        $indexedMerchantOpeningHoursStorageTransfers = $this->indexCollectionByMerchantReferences(
            $merchantOpeningHoursStorageTransfers,
            $merchantIdsIndexedByReference
        );

        return $this->merchantOpeningHoursTranslator
            ->getMerchantOpeningHoursTransfersWithTranslatedNotes(
                $indexedMerchantOpeningHoursStorageTransfers,
                $restRequest->getMetadata()->getLocale()
            );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return string[]
     */
    protected function getMerchantReferences(array $resources): array
    {
        $references = [];
        foreach ($resources as $resource) {
            $resourceId = $resource->getId();
            if (!$resourceId) {
                continue;
            }

            $references[] = $resourceId;
        }

        return $references;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer[] $merchantStorageTransfers
     *
     * @return int[]
     */
    protected function getMerchantIdsIndexedByReference(array $merchantStorageTransfers): array
    {
        $merchantIdsIndexedByReference = [];
        foreach ($merchantStorageTransfers as $merchantStorageTransfer) {
            $merchantIdsIndexedByReference[$merchantStorageTransfer->getMerchantReference()] = $merchantStorageTransfer->getIdMerchant();
        }

        return $merchantIdsIndexedByReference;
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
