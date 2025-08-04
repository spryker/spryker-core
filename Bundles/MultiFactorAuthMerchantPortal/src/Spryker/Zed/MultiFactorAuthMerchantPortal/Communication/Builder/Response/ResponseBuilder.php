<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Builder\Response;

use Generated\Shared\Transfer\ZedUiFormRequestActionTransfer;
use Spryker\Shared\ZedUi\ZedUiFactoryInterface;
use Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Controller\MerchantUserController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseBuilder implements ResponseBuilderInterface
{
    /**
     * @param \Spryker\Shared\ZedUi\ZedUiFactoryInterface $zedUiFactory
     */
    public function __construct(
        protected ZedUiFactoryInterface $zedUiFactory
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\ZedUiFormRequestActionTransfer $zedUIFormRequestActionTransfer
     * @param string $responseType
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function buildResponse(ZedUiFormRequestActionTransfer $zedUIFormRequestActionTransfer, string $responseType): JsonResponse
    {
        $methodName = sprintf('return%sResponse', $responseType);

        return new JsonResponse(
            $this->{$methodName}($zedUIFormRequestActionTransfer),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ZedUiFormRequestActionTransfer $zedUIFormRequestActionTransfer
     *
     * @return array<string, mixed>
     */
    protected function returnOpenModalResponse(ZedUiFormRequestActionTransfer $zedUIFormRequestActionTransfer): array
    {
        return $this->zedUiFactory
            ->createZedUiFormResponseBuilder()
            ->addActionOpenModal($zedUIFormRequestActionTransfer)
            ->createResponse()
            ->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\ZedUiFormRequestActionTransfer $zedUIFormRequestActionTransfer
     *
     * @return array<string, mixed>
     */
    protected function returnRefreshModalResponse(ZedUiFormRequestActionTransfer $zedUIFormRequestActionTransfer): array
    {
        return $this->zedUiFactory
            ->createZedUiFormResponseBuilder()
            ->addActionRefreshModal($zedUIFormRequestActionTransfer)
            ->createResponse()
            ->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\ZedUiFormRequestActionTransfer $zedUIFormRequestActionTransfer
     *
     * @return array<string, mixed>
     */
    protected function returnCloseModalResponse(ZedUiFormRequestActionTransfer $zedUIFormRequestActionTransfer): array
    {
        $zedUiFormResponseBuilder = $this->zedUiFactory
            ->createZedUiFormResponseBuilder()
            ->addActionCloseModal($zedUIFormRequestActionTransfer);

        if (
            $zedUIFormRequestActionTransfer->getResultOrFail() === MerchantUserController::VALIDATION_RESPONSE_SUCCESS
            && $zedUIFormRequestActionTransfer->getIsLogin() === false
            && !$zedUIFormRequestActionTransfer->getAjaxFormSelector()
        ) {
            $zedUiFormResponseBuilder->addActionSubmitForm($zedUIFormRequestActionTransfer);
        }

        if ($zedUIFormRequestActionTransfer->getIsLogin() || $zedUIFormRequestActionTransfer->getResult() === MerchantUserController::VALIDATION_RESPONSE_ERROR) {
            $zedUiFormResponseBuilder->addActionRedirect($zedUIFormRequestActionTransfer->getUrl() ?? '');
        }

        if ($zedUIFormRequestActionTransfer->getAjaxFormSelector()) {
            $zedUiFormResponseBuilder->addActionSubmitAjaxForm($zedUIFormRequestActionTransfer);
        }

        return $zedUiFormResponseBuilder->createResponse()->toArray();
    }

    /**
     * @param \Generated\Shared\Transfer\ZedUiFormRequestActionTransfer $zedUIFormRequestActionTransfer
     *
     * @return array<string, mixed>
     */
    protected function returnSubmitAjaxFormResponse(ZedUiFormRequestActionTransfer $zedUIFormRequestActionTransfer): array
    {
        return $this->zedUiFactory
            ->createZedUiFormResponseBuilder()
            ->addActionSubmitAjaxForm($zedUIFormRequestActionTransfer)
            ->createResponse()
            ->toArray();
    }
}
