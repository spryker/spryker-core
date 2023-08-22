<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShipmentTypesRestApi\Processor\Builder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ShipmentTypesRestApi\Dependency\Client\ShipmentTypesRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ErrorResponseBuilder implements ErrorResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiConfig
     */
    protected ShipmentTypesRestApiConfig $shipmentTypesRestApiConfig;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected RestResourceBuilderInterface $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ShipmentTypesRestApi\Dependency\Client\ShipmentTypesRestApiToGlossaryStorageClientInterface
     */
    protected ShipmentTypesRestApiToGlossaryStorageClientInterface $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\ShipmentTypesRestApi\ShipmentTypesRestApiConfig $shipmentTypesRestApiConfig
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ShipmentTypesRestApi\Dependency\Client\ShipmentTypesRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        ShipmentTypesRestApiConfig $shipmentTypesRestApiConfig,
        RestResourceBuilderInterface $restResourceBuilder,
        ShipmentTypesRestApiToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        $this->shipmentTypesRestApiConfig = $shipmentTypesRestApiConfig;
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

        $errorData = $this->shipmentTypesRestApiConfig->getGlossaryKeyToErrorDataMapping()[$errorMessage] ?? null;
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
            ->setCode(ShipmentTypesRestApiConfig::RESPONSE_CODE_UNKNOWN_ERROR)
            ->setDetail($errorMessage);
    }
}
