<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ServicePointsRestApi\Processor\Builder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ErrorResponseBuilder implements ErrorResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig
     */
    protected ServicePointsRestApiConfig $servicePointsRestApiConfig;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected RestResourceBuilderInterface $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToGlossaryStorageClientInterface
     */
    protected ServicePointsRestApiToGlossaryStorageClientInterface $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\ServicePointsRestApi\ServicePointsRestApiConfig $servicePointsRestApiConfig
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ServicePointsRestApi\Dependency\Client\ServicePointsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        ServicePointsRestApiConfig $servicePointsRestApiConfig,
        RestResourceBuilderInterface $restResourceBuilder,
        ServicePointsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        $this->servicePointsRestApiConfig = $servicePointsRestApiConfig;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->glossaryStorageClient = $glossaryStorageClient;
    }

    /**
     * @param string $errorMessage
     * @param string $localeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createErrorResponse(string $errorMessage, string $localeName): RestResponseInterface
    {
        $errorResponse = $this->restResourceBuilder->createRestResponse();

        $errorData = $this->servicePointsRestApiConfig->getGlossaryKeyToErrorDataMapping()[$errorMessage] ?? null;
        if (!$errorData) {
            return $errorResponse->addError(
                $this->createUnknownRestErrorMessageTransfer($errorMessage),
            );
        }

        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->fromArray($errorData, true)
            ->setDetail($this->glossaryStorageClient->translate($errorMessage, $localeName));

        return $errorResponse->addError($restErrorMessageTransfer);
    }

    /**
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createUnknownRestErrorMessageTransfer(string $errorMessage): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setCode(ServicePointsRestApiConfig::RESPONSE_CODE_UNKNOWN_ERROR)
            ->setDetail($errorMessage);
    }
}
