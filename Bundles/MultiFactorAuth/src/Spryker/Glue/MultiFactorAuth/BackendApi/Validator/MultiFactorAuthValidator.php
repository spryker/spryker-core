<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\BackendApi\Validator;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Glue\MultiFactorAuth\BackendApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMultiFactorAuthFacadeInterface;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;

class MultiFactorAuthValidator implements MultiFactorAuthValidatorInterface
{
    /**
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMultiFactorAuthFacadeInterface $multiFactorAuthFacade
     * @param array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface> $multiFactorAuthPlugins
     * @param \Spryker\Glue\MultiFactorAuth\BackendApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface $multiFactorAuthResponseBuilder
     */
    public function __construct(
        protected MultiFactorAuthToMultiFactorAuthFacadeInterface $multiFactorAuthFacade,
        protected array $multiFactorAuthPlugins,
        protected MultiFactorAuthResponseBuilderInterface $multiFactorAuthResponseBuilder
    ) {
    }

    /**
     * @param string $multiFactorAuthCode
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     * @param array<int> $additionalStatuses
     * @param string|null $multiFactorAuthType
     *
     * @return bool
     */
    public function isMultiFactorAuthCodeValid(
        string $multiFactorAuthCode,
        UserTransfer $userTransfer,
        MultiFactorAuthTransfer $multiFactorAuthTransfer,
        array $additionalStatuses = [],
        ?string $multiFactorAuthType = null
    ): bool {
        $multiFactorAuthCodeCriteriaTransfer = (new MultiFactorAuthCodeCriteriaTransfer())
            ->setCode($multiFactorAuthCode)
            ->setUser($userTransfer)
            ->setType($multiFactorAuthType);

        $multiFactorAuthCodeWithTypeTransfer = $this->multiFactorAuthFacade->findUserMultiFactorAuthType($multiFactorAuthCodeCriteriaTransfer);

        if (
            $multiFactorAuthCodeWithTypeTransfer->getIdCode() === null ||
            $multiFactorAuthCodeWithTypeTransfer->getTypeOrFail() !== $multiFactorAuthTransfer->getTypeOrFail()
        ) {
            return false;
        }

        if ($multiFactorAuthCodeWithTypeTransfer->getStatusOrFail() === MultiFactorAuthConstants::STATUS_ACTIVE) {
             $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setUser($userTransfer)->setAdditionalStatuses($additionalStatuses);
             $multiFactorAuthValidationResponseTransfer = $this->multiFactorAuthFacade->validateUserMultiFactorAuthStatus(
                 $multiFactorAuthValidationRequestTransfer,
             );

            return $multiFactorAuthValidationResponseTransfer->getIsRequired() === false;
        }

        return $this->isMultiFactorAuthCodeVerified($multiFactorAuthTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param string $multiFactorAuthType
     *
     * @return bool
     */
    public function isPendingActivationMultiFactorAuthType(
        UserTransfer $userTransfer,
        string $multiFactorAuthType
    ): bool {
        $multiFactorAuthCriteriaTransfer = (new MultiFactorAuthCriteriaTransfer())
            ->setUser($userTransfer)
            ->setStatuses([MultiFactorAuthConstants::STATUS_PENDING_ACTIVATION]);

        $pendingActivationMultiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthFacade->getUserMultiFactorAuthTypes($multiFactorAuthCriteriaTransfer);

        if ($pendingActivationMultiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes()->count() === 0) {
            return false;
        }

        foreach ($pendingActivationMultiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes() as $pendingActivationMultiFactorAuthType) {
            if ($pendingActivationMultiFactorAuthType->getType() === $multiFactorAuthType) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer
     * @param string $multiFactorAuthType
     *
     * @return bool
     */
    public function isActivatedMultiFactorAuthType(
        MultiFactorAuthTypesCollectionTransfer $multiFactorAuthTypesCollectionTransfer,
        string $multiFactorAuthType
    ): bool {
        foreach ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes() as $activatedMultiFactorAuthType) {
            if ($activatedMultiFactorAuthType->getTypeOrFail() === $multiFactorAuthType) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer|null
     */
    public function validateMultiFactorAuthType(
        GlueRequestTransfer $glueRequestTransfer,
        RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
    ): ?GlueResponseTransfer {
        if (!$glueRequestTransfer->getRequestUser()) {
            return $this->multiFactorAuthResponseBuilder->createUserNotFoundResponse();
        }

        if (!$restMultiFactorAuthAttributesTransfer->getType()) {
            return $this->multiFactorAuthResponseBuilder->createMissingTypeErrorResponse();
        }

        $availableTypes = $this->getMultiFactorAuthAvailableTypes();
        if (!array_key_exists($restMultiFactorAuthAttributesTransfer->getTypeOrFail(), $availableTypes)) {
            return $this->multiFactorAuthResponseBuilder->createNotFoundTypeErrorResponse();
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return bool
     */
    protected function isMultiFactorAuthCodeVerified(MultiFactorAuthTransfer $multiFactorAuthTransfer): bool
    {
        $validationResponse = $this->multiFactorAuthFacade->validateUserCode($multiFactorAuthTransfer);

        return $validationResponse->getStatus() === MultiFactorAuthConstants::CODE_VERIFIED;
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
