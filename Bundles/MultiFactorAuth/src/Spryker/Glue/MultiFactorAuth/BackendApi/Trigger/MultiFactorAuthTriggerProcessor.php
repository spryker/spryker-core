<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Glue\MultiFactorAuth\BackendApi\Trigger;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer;
use Spryker\Glue\MultiFactorAuth\BackendApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface;
use Spryker\Glue\MultiFactorAuth\BackendApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface;
use Spryker\Glue\MultiFactorAuth\BackendApi\Validator\MultiFactorAuthValidatorInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMultiFactorAuthFacadeInterface;
use Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Throwable;

class MultiFactorAuthTriggerProcessor implements MultiFactorAuthTriggerProcessorInterface
{
    /**
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToMultiFactorAuthFacadeInterface $multiFactorAuthFacade
     * @param \Spryker\Glue\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface $userFacade
     * @param \Spryker\Glue\MultiFactorAuth\BackendApi\ResponseBuilder\MultiFactorAuthResponseBuilderInterface $multiFactorAuthResponseBuilder
     * @param \Spryker\Glue\MultiFactorAuth\BackendApi\TransferBuilder\MultiFactorAuthTransferBuilderInterface $multiFactorAuthTransferBuilder
     * @param \Spryker\Glue\MultiFactorAuth\BackendApi\Validator\MultiFactorAuthValidatorInterface $multiFactorAuthValidator
     */
    public function __construct(
        protected MultiFactorAuthToMultiFactorAuthFacadeInterface $multiFactorAuthFacade,
        protected MultiFactorAuthToUserFacadeInterface $userFacade,
        protected MultiFactorAuthResponseBuilderInterface $multiFactorAuthResponseBuilder,
        protected MultiFactorAuthTransferBuilderInterface $multiFactorAuthTransferBuilder,
        protected MultiFactorAuthValidatorInterface $multiFactorAuthValidator
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Generated\Shared\Transfer\RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function triggerMultiFactorAuth(
        GlueRequestTransfer $glueRequestTransfer,
        RestMultiFactorAuthAttributesTransfer $restMultiFactorAuthAttributesTransfer
    ): GlueResponseTransfer {
        $errorResponse = $this->multiFactorAuthValidator->validateMultiFactorAuthType($glueRequestTransfer, $restMultiFactorAuthAttributesTransfer);
        if ($errorResponse !== null) {
            return $errorResponse;
        }

        $multiFactorAuthType = $restMultiFactorAuthAttributesTransfer->getTypeOrFail();
        $userCollectionTransfer = $this->userFacade->getUserCollection($this->multiFactorAuthTransferBuilder->createUserCriteriaTransfer([(int)$glueRequestTransfer->getRequestUser()?->getSurrogateIdentifier()]));
        $userTransfer = $userCollectionTransfer->getUsers()->getIterator()->current();
        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthFacade
            ->getUserMultiFactorAuthTypes($userTransfer);

        foreach ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes() as $activatedMultiFactorAuthType) {
            if ($activatedMultiFactorAuthType->getTypeOrFail() !== $multiFactorAuthType) {
                continue;
            }

            $multiFactorAuthTransfer = $this->multiFactorAuthTransferBuilder->buildMultiFactorAuthTransfer($multiFactorAuthType, $userTransfer);

            return $this->safelySendActivationCode($multiFactorAuthTransfer);
        }

        return $this->multiFactorAuthResponseBuilder->createNotFoundTypeErrorResponse();
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthTransfer $multiFactorAuthTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    protected function safelySendActivationCode(MultiFactorAuthTransfer $multiFactorAuthTransfer): GlueResponseTransfer
    {
        try {
            $this->multiFactorAuthFacade->sendUserCode(
                $multiFactorAuthTransfer->setStatus(MultiFactorAuthConstants::STATUS_ACTIVE),
            );
        } catch (Throwable $e) {
            return $this->multiFactorAuthResponseBuilder->createSendingCodeError();
        }

        return $this->multiFactorAuthResponseBuilder->createSuccessResponse();
    }
}
