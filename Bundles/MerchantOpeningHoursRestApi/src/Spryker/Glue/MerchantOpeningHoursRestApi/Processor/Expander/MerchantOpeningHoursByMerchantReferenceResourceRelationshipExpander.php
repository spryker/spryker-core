<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantStorageClientInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder\MerchantOpeningHourRestResponseBuilderInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Translator\MerchantOpeningHoursTranslatorInterface;

class MerchantOpeningHoursByMerchantReferenceResourceRelationshipExpander implements MerchantOpeningHoursByMerchantReferenceResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder\MerchantOpeningHourRestResponseBuilderInterface
     */
    protected $merchantsRestResponseBuilder;

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
     * @param \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder\MerchantOpeningHourRestResponseBuilderInterface $merchantsRestResponseBuilder
     * @param \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Translator\MerchantOpeningHoursTranslatorInterface $merchantOpeningHoursTranslator
     */
    public function __construct(
        MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientInterface $merchantOpeningHoursStorageClient,
        MerchantOpeningHoursRestApiToMerchantStorageClientInterface $merchantStorageClient,
        MerchantOpeningHourRestResponseBuilderInterface $merchantsRestResponseBuilder,
        MerchantOpeningHoursTranslatorInterface $merchantOpeningHoursTranslator
    ) {
        $this->merchantOpeningHoursStorageClient = $merchantOpeningHoursStorageClient;
        $this->merchantStorageClient = $merchantStorageClient;
        $this->merchantsRestResponseBuilder = $merchantsRestResponseBuilder;
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
        $merchantReferences = $this->getMerchantReferences($resources);
        $merchantStorageTransfers = $this->merchantStorageClient->findByMerchantReference($merchantReferences);

        $merchantIds = $this->getMerchantIds($merchantStorageTransfers);
        $merchantOpeningHoursStorageTransfers = $this->merchantOpeningHoursStorageClient
            ->getMerchantOpeningHoursByMerchantIds($merchantIds);

        $merchantOpeningHoursStorageTransfers = $this->flipIdsToMerchantReference($merchantOpeningHoursStorageTransfers, $merchantIds);

        $merchantOpeningHoursStorageTransfersWithTranslatedNotes = $this->getMerchantOpeningHoursStorageTransfersWithTranslatedNotes(
            $merchantOpeningHoursStorageTransfers,
            $restRequest->getMetadata()->getLocale()
        );

        foreach ($resources as $resource) {
            $merchantOpeningHoursStorageTransferWithTranslatedNotes = $merchantOpeningHoursStorageTransfersWithTranslatedNotes[$resource->getId()];
            $restPaymentMethodsResource = $this->merchantsRestResponseBuilder->createMerchantOpeningHoursRestResource(
                $merchantOpeningHoursStorageTransferWithTranslatedNotes,
                $resource->getId()
            );

            $resource->addRelationship($restPaymentMethodsResource);
        }
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
            $references[] = $resource->getId();
        }

        return $references;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer[] $merchantStorageTransfers
     *
     * @return int[]
     */
    protected function getMerchantIds(array $merchantStorageTransfers): array
    {
        $merchantIds = [];
        foreach ($merchantStorageTransfers as $merchantStorageTransfer) {
            $merchantIds[$merchantStorageTransfer->getMerchantReference()] = $merchantStorageTransfer->getIdMerchant();
        }

        return $merchantIds;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer[] $merchantOpeningHoursStorageTransfers
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer[]
     */
    protected function getMerchantOpeningHoursStorageTransfersWithTranslatedNotes(array $merchantOpeningHoursStorageTransfers, string $locale): array
    {
        $merchantOpeningHoursStorageTransfersWithTranslatedNotes = [];
        foreach ($merchantOpeningHoursStorageTransfers as $merchantReference => $merchantOpeningHourStorageTransfers) {
            $merchantOpeningHoursStorageTransfersWithTranslatedNotes[$merchantReference] = $this->merchantOpeningHoursTranslator
                ->getMerchantOpeningHoursTransferWithTranslatedNotes(
                    $merchantOpeningHourStorageTransfers,
                    $locale
                );
        }

        return $merchantOpeningHoursStorageTransfersWithTranslatedNotes;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer[] $merchantOpeningHoursStorageTransfers
     * @param array $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantOpeningHoursStorageTransfer[]
     */
    protected function flipIdsToMerchantReference(array $merchantOpeningHoursStorageTransfers, array $merchantIds): array
    {
        $flippedMerchantOpeningHoursStorageTransfers = [];
        $merchantIds = array_flip($merchantIds);
        foreach ($merchantOpeningHoursStorageTransfers as $key => $merchantOpeningHourStorageTransfers) {
            $flippedMerchantOpeningHoursStorageTransfers[$merchantIds[$key]] = $merchantOpeningHourStorageTransfers;
        }

        return $flippedMerchantOpeningHoursStorageTransfers;
    }
}
