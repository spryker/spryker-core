<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\Processor\Reader;

use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientInterface;
use Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig;
use Spryker\Glue\MultiFactorAuth\Processor\ResponseBuilder\MultiFactorAuthResponseBuilderInterface;
use Spryker\Glue\MultiFactorAuth\Processor\TransferBuilder\MultiFactorAuthTransferBuilderInterface;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;

class MultiFactorAuthTypesReader implements MultiFactorAuthTypesReaderInterface
{
    /**
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientInterface $multiFactorAuthClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface> $multiFactorAuthPlugins
     * @param \Spryker\Glue\MultiFactorAuth\Processor\ResponseBuilder\MultiFactorAuthResponseBuilderInterface $multiFactorAuthResponseBuilder
     * @param \Spryker\Glue\MultiFactorAuth\Processor\TransferBuilder\MultiFactorAuthTransferBuilderInterface $multiFactorAuthTransferBuilder
     * @param \Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig $config
     */
    public function __construct(
        protected MultiFactorAuthToMultiFactorAuthClientInterface $multiFactorAuthClient,
        protected RestResourceBuilderInterface $restResourceBuilder,
        protected array $multiFactorAuthPlugins,
        protected MultiFactorAuthResponseBuilderInterface $multiFactorAuthResponseBuilder,
        protected MultiFactorAuthTransferBuilderInterface $multiFactorAuthTransferBuilder,
        protected MultiFactorAuthConfig $config
    ) {
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getMultiFactorAuthTypes(RestRequestInterface $restRequest): RestResponseInterface
    {
        if (!$restRequest->getRestUser()) {
            return $this->multiFactorAuthResponseBuilder->createNoCustomerIdentifierErrorResponse();
        }

        $customerTransfer = $this->multiFactorAuthTransferBuilder->buildCustomerTransfer($restRequest);

        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthClient
            ->getCustomerMultiFactorAuthTypes((new MultiFactorAuthCriteriaTransfer())->setCustomer($customerTransfer));

        $restResponse = $this->restResourceBuilder->createRestResponse();
        $availableTypes = $this->getMultiFactorAuthAvailableTypes();

        $processedTypes = [];
        foreach ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes() as $multiFactorAuthTransfer) {
            $processedTypes[$multiFactorAuthTransfer->getTypeOrFail()] = true;
            $restResponse = $this->addMultiFactorAuthTypeResourceToResponse($restResponse, $multiFactorAuthTransfer);
        }

        $multiFactorAuthCriteriaTransfer = (new MultiFactorAuthCriteriaTransfer())
            ->setCustomer($customerTransfer)
            ->setStatuses([MultiFactorAuthConstants::STATUS_PENDING_ACTIVATION]);

        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthClient->getCustomerMultiFactorAuthTypes($multiFactorAuthCriteriaTransfer);

        foreach ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes() as $multiFactorAuthTransfer) {
            $processedTypes[$multiFactorAuthTransfer->getTypeOrFail()] = true;
            $restResponse = $this->addMultiFactorAuthTypeResourceToResponse($restResponse, $multiFactorAuthTransfer);
        }

        foreach ($availableTypes as $type) {
            if (isset($processedTypes[$type])) {
                continue;
            }

            $multiFactorAuthTransfer = $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthTransfer(
                $type,
                $customerTransfer,
                null,
                MultiFactorAuthConstants::STATUS_INACTIVE,
            );

            $restResponse = $this->addMultiFactorAuthTypeResourceToResponse($restResponse, $multiFactorAuthTransfer);
        }

        return $restResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function addMultiFactorAuthTypeResourceToResponse(
        RestResponseInterface $restResponse,
        MultiFactorAuthTransfer $multiFactorAuthTransfer
    ): RestResponseInterface {
        $restMultiFactorAuthAttributesTransfer = new RestMultiFactorAuthAttributesTransfer();
        $restMultiFactorAuthAttributesTransfer->fromArray($multiFactorAuthTransfer->toArray(), true);
        $restMultiFactorAuthAttributesTransfer->setStatus($this->config->getMultiFactorAuthTypeStatuses()[$multiFactorAuthTransfer->getStatusOrFail()]);

        $restResource = $this->restResourceBuilder
            ->createRestResource(
                MultiFactorAuthConfig::RESOURCE_MULTI_FACTOR_AUTH_TYPES,
                null,
                $restMultiFactorAuthAttributesTransfer,
            );

        return $restResponse->addResource($restResource);
    }

    /**
     * @return array<string, string>
     */
    protected function getMultiFactorAuthAvailableTypes(): array
    {
        $availableTypes = [];

        foreach ($this->multiFactorAuthPlugins as $plugin) {
            $availableTypes[$plugin->getName()] = $plugin->getName();
        }

        return $availableTypes;
    }
}
