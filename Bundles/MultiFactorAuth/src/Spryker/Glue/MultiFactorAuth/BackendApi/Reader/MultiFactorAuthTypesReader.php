<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\BackendApi\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer;
use Spryker\Glue\MultiFactorAuth\BackendApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface;
use Spryker\Glue\MultiFactorAuth\BackendApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMultiFactorAuthFacadeInterface;
use Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;

class MultiFactorAuthTypesReader implements MultiFactorAuthTypesReaderInterface
{
    /**
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMultiFactorAuthFacadeInterface $multiFactorAuthFacade
     * @param array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface> $multiFactorAuthPlugins
     * @param \Spryker\Glue\MultiFactorAuth\BackendApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface $multiFactorAuthResponseBuilder
     * @param \Spryker\Glue\MultiFactorAuth\BackendApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface $multiFactorAuthTransferBuilder
     * @param \Spryker\Glue\MultiFactorAuth\MultiFactorAuthConfig $config
     */
    public function __construct(
        protected MultiFactorAuthToMultiFactorAuthFacadeInterface $multiFactorAuthFacade,
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
        if (!$glueRequestTransfer->getRequestUser()) {
            return $this->multiFactorAuthResponseBuilder->createNoUserIdentifierErrorResponse();
        }

        $userTransfer = $this->multiFactorAuthTransferBuilder->buildUserTransfer($glueRequestTransfer);

        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthFacade
            ->getUserMultiFactorAuthTypes($userTransfer);

        $glueResponseTransfer = new GlueResponseTransfer();
        $availableTypes = $this->getMultiFactorAuthAvailableTypes();

        $processedTypes = [];
        foreach ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes() as $multiFactorAuthTransfer) {
            $processedTypes[$multiFactorAuthTransfer->getTypeOrFail()] = true;
            $glueResponseTransfer = $this->addMultiFactorAuthTypeResourceToResponse($glueResponseTransfer, $multiFactorAuthTransfer);
        }

        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthFacade
            ->getPendingActivationUserMultiFactorAuthTypes($userTransfer);
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
                $userTransfer,
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
