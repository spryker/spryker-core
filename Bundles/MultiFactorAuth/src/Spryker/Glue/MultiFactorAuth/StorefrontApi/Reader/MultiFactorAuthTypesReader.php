<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\StorefrontApi\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer;
use Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientInterface;
use Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface;
use Spryker\Glue\MultiFactorAuth\StorefrontApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;

class MultiFactorAuthTypesReader implements MultiFactorAuthTypesReaderInterface
{
    /**
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Client\MultiFactorAuthToMultiFactorAuthClientInterface $multiFactorAuthClient
     * @param array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface> $multiFactorAuthPlugins
     * @param \Spryker\Glue\MultiFactorAuth\StorefrontApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface $multiFactorAuthResponseBuilder
     * @param \Spryker\Glue\MultiFactorAuth\StorefrontApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface $multiFactorAuthTransferBuilder
     * @param \Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig $config
     */
    public function __construct(
        protected MultiFactorAuthToMultiFactorAuthClientInterface $multiFactorAuthClient,
        protected array $multiFactorAuthPlugins,
        protected MultiFactorAuthResponseBuilderInterface $multiFactorAuthResponseBuilder,
        protected MultiFactorAuthTransferBuilderInterface $multiFactorAuthTransferBuilder,
        protected MultiFactorAuthConfig $config
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function getMultiFactorAuthTypes(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        if (!$glueRequestTransfer->getRequestCustomer()) {
            return $this->multiFactorAuthResponseBuilder->createNoCustomerIdentifierErrorResponse();
        }

        $customerTransfer = $this->multiFactorAuthTransferBuilder->buildCustomerTransfer($glueRequestTransfer);
        $multiFactorAuthCriteriaTransfer = (new MultiFactorAuthCriteriaTransfer())->setCustomer($customerTransfer);
        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthClient->getCustomerMultiFactorAuthTypes($multiFactorAuthCriteriaTransfer);

        $glueResponseTransfer = new GlueResponseTransfer();
        $availableTypes = $this->getMultiFactorAuthAvailableTypes();

        $processedTypes = [];
        foreach ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes() as $multiFactorAuthTransfer) {
            $processedTypes[$multiFactorAuthTransfer->getTypeOrFail()] = true;
            $glueResponseTransfer = $this->addMultiFactorAuthTypeResourceToResponse($glueResponseTransfer, $multiFactorAuthTransfer);
        }

        $multiFactorAuthCriteriaTransfer = $multiFactorAuthCriteriaTransfer->setStatuses([MultiFactorAuthConstants::STATUS_PENDING_ACTIVATION]);

        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthClient->getCustomerMultiFactorAuthTypes($multiFactorAuthCriteriaTransfer);

        foreach ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes() as $multiFactorAuthTransfer) {
            $processedTypes[$multiFactorAuthTransfer->getTypeOrFail()] = true;
            $glueResponseTransfer = $this->addMultiFactorAuthTypeResourceToResponse($glueResponseTransfer, $multiFactorAuthTransfer);
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

            $glueResponseTransfer = $this->addMultiFactorAuthTypeResourceToResponse($glueResponseTransfer, $multiFactorAuthTransfer);
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function addMultiFactorAuthTypeResourceToResponse(
        GlueResponseTransfer $glueResponseTransfer,
        MultiFactorAuthTransfer $multiFactorAuthTransfer
    ): GlueResponseTransfer {
        $restMultiFactorAuthAttributesTransfer = new RestMultiFactorAuthAttributesTransfer();
        $restMultiFactorAuthAttributesTransfer->fromArray($multiFactorAuthTransfer->toArray(), true);
        $restMultiFactorAuthAttributesTransfer->setStatus($this->config->getMultiFactorAuthTypeStatuses()[$multiFactorAuthTransfer->getStatusOrFail()]);

        $restResource = (new GlueResourceTransfer())
            ->setType(MultiFactorAuthConfig::RESOURCE_MULTI_FACTOR_AUTH_TYPES)
            ->setAttributes($restMultiFactorAuthAttributesTransfer);

        return $glueResponseTransfer->addResource($restResource);
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
