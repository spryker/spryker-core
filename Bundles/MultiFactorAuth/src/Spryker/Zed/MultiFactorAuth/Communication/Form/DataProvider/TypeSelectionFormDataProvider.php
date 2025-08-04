<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Form\DataProvider;

use Generated\Shared\Transfer\MultiFactorAuthCriteriaTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface;
use Spryker\Zed\MultiFactorAuth\Communication\Controller\UserController;
use Spryker\Zed\MultiFactorAuth\Communication\Reader\Request\RequestReaderInterface;
use Spryker\Zed\MultiFactorAuth\Communication\Reader\User\UserReaderInterface;
use Symfony\Component\HttpFoundation\Request;

class TypeSelectionFormDataProvider
{
    /**
     * @param \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface $multiFactorAuthFacade
     * @param \Spryker\Zed\MultiFactorAuth\Communication\Reader\User\UserReaderInterface $userReader
     * @param \Spryker\Zed\MultiFactorAuth\Communication\Reader\Request\RequestReaderInterface $requestReader
     */
    public function __construct(
        protected MultiFactorAuthFacadeInterface $multiFactorAuthFacade,
        protected UserReaderInterface $userReader,
        protected RequestReaderInterface $requestReader
    ) {
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    public function getOptions(Request $request): array
    {
        return [
            UserController::TYPES => $this->getTypes($request),
            UserController::IS_ACTIVATION => false,
            UserController::IS_DEACTIVATION => false,
            UserController::TYPE_TO_SET_UP => null,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<int, string>
     */
    protected function getTypes(Request $request): array
    {
        $userTransfer = $this->userReader->getUser();

        if ($this->isActivationFlow($request, $userTransfer)) {
            return [$this->requestReader->get($request, UserController::TYPE_TO_SET_UP)];
        }

        $multiFactorAuthCriteraTransfer = (new MultiFactorAuthCriteriaTransfer())->setUser($userTransfer);
        $multiFactorAuthTypesCollectionTransfer = $this->multiFactorAuthFacade->getEnabledUserMultiFactorAuthTypes($multiFactorAuthCriteraTransfer);
        $enabledTypes = [];

        foreach ($multiFactorAuthTypesCollectionTransfer->getMultiFactorAuthTypes() as $multiFactorAuthTypeTransfer) {
            $enabledTypes[] = $multiFactorAuthTypeTransfer->getTypeOrFail();
        }

        return $enabledTypes;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return bool
     */
    protected function isActivationFlow(
        Request $request,
        UserTransfer $userTransfer
    ): bool {
        $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setUser($userTransfer);

        return $this->requestReader->get($request, UserController::IS_ACTIVATION) &&
            $this->multiFactorAuthFacade->validateUserMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer)->getIsRequired() === false;
    }
}
