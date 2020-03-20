<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantOpeningHoursRestApi\Processor\Reader;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantStorageClientInterface;
use Spryker\Glue\MerchantOpeningHoursRestApi\MerchantOpeningHoursRestApiConfig;
use Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder\MerchantOpeningHourRestResponseBuilderInterface;

class MerchantOpeningHourReader implements MerchantOpeningHourReaderInterface
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
     * @var \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder\MerchantOpeningHourRestResponseBuilderInterface
     */
    protected $merchantsRestResponseBuilder;

    /**
     * @param \Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientInterface $merchantOpeningHoursStorageClient
     * @param \Spryker\Glue\MerchantOpeningHoursRestApi\Dependency\Client\MerchantOpeningHoursRestApiToMerchantStorageClientInterface $merchantStorageClient
     * @param \Spryker\Glue\MerchantOpeningHoursRestApi\Processor\RestResponseBuilder\MerchantOpeningHourRestResponseBuilderInterface $merchantsRestResponseBuilder
     */
    public function __construct(
        MerchantOpeningHoursRestApiToMerchantOpeningHoursStorageClientInterface $merchantOpeningHoursStorageClient,
        MerchantOpeningHoursRestApiToMerchantStorageClientInterface $merchantStorageClient,
        MerchantOpeningHourRestResponseBuilderInterface $merchantsRestResponseBuilder
    ) {
        $this->merchantOpeningHoursStorageClient = $merchantOpeningHoursStorageClient;
        $this->merchantStorageClient = $merchantStorageClient;
        $this->merchantsRestResponseBuilder = $merchantsRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getMerchantOpeningHours(RestRequestInterface $restRequest): RestResponseInterface
    {
        $merchantReference = $this->findMerchantIdentifier($restRequest);
        if (!$merchantReference) {
            return $this->merchantsRestResponseBuilder->createMerchantIdentifierMissingErrorResponse();
        }

        $merchantStorageTransfers = $this->merchantStorageClient->findByMerchantReference([$merchantReference]);
        if (!$merchantStorageTransfers) {
            return $this->merchantsRestResponseBuilder->createMerchantNotFoundError();
        }

        $merchantOpeningHoursStorageTransfers = $this->merchantOpeningHoursStorageClient
            ->getMerchantOpeningHoursByMerchantIds([$merchantStorageTransfers[0]->getIdMerchant()]);
        if (!$merchantOpeningHoursStorageTransfers) {
            return $this->merchantsRestResponseBuilder->createMerchantOpeningHoursNotFoundErrorResponse();
        }

        return $this->merchantsRestResponseBuilder->createMerchantOpeningHoursRestResponse(
            reset($merchantOpeningHoursStorageTransfers),
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
        $merchantResource = $restRequest->findParentResourceByType(MerchantOpeningHoursRestApiConfig::RESOURCE_MERCHANTS);
        if ($merchantResource) {
            return $merchantResource->getId();
        }

        return null;
    }
}
