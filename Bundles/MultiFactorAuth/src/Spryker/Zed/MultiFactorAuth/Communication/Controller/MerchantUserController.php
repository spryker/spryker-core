<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Controller;

use Generated\Shared\Transfer\UserTransfer;
use Generated\Shared\Transfer\ZedUiFormRequestActionTransfer;
use Spryker\Zed\MultiFactorAuth\Communication\Form\DataProvider\TypeSelectionFormDataProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface getRepository()
 */
class MerchantUserController extends AbstractUserController
{
    /**
     * @var string
     */
    protected const MODAL_FORM_SELECTOR_PARAMETER = 'form_selector';

    /**
     * @var string
     */
    protected const MODAL_AJAX_FORM_SELECTOR_PARAMETER = 'ajax_form_selector';

    /**
     * @var string
     */
    protected const MODAL_IS_LOGIN_PARAMETER = 'is_login';

    /**
     * @var string
     */
    protected const VALIDATION_RESPONSE_SUCCESS = 'success';

    /**
     * @uses {@link \Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\MultiFactorAuth\PostMerchantUserLoginMultiFactorAuthenticationPlugin::MERCHANT_USER_POST_AUTHENTICATION_TYPE}
     *
     * @var string
     */
    protected const MERCHANT_USER_POST_AUTHENTICATION_TYPE = 'MERCHANT_USER_POST_AUTHENTICATION_TYPE';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|array<string, mixed>
     */
    public function getEnabledTypesAction(Request $request)
    {
        $options = $this->getOptions($request);

        if ($this->assertNoTypesEnabled($options) && $this->isSetUpMultiFactorAuthStep($request) === false) {
            return new JsonResponse($this->returnSubmitAjaxFormResponse($request));
        }

        return parent::getEnabledTypesAction($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array<string, mixed> $responseData
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function returnGetEnabledTypesResponse(Request $request, array $responseData): JsonResponse
    {
        /** @var string $renderedForm */
        $renderedForm = $this->renderView(
            $this->getGetEnabledTypesTemplatePath(),
            $responseData,
        )->getContent();

        $zedUIFormResquestActionTransfer = $this->buildZedUiFormRequestActionTransfer($renderedForm);

        return $this->buildResponse($zedUIFormResquestActionTransfer, static::RESPONSE_TYPE_OPEN);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array<string, mixed> $responseData
     * @param string $responseType
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function returnSendCodeResponse(Request $request, array $responseData, string $responseType): JsonResponse
    {
        /** @var string $renderedForm */
        $renderedForm = $this->renderView(
            $this->getSendCodeTemplatePath(),
            $responseData,
        )->getContent();

        $zedUIFormResquestActionTransfer = $this->buildZedUiFormRequestActionTransfer($renderedForm);

        return $this->buildResponse($zedUIFormResquestActionTransfer, $responseType);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $result
     * @param string|null $formName
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function returnValidationResponse(Request $request, ?string $result = null, ?string $formName = null): JsonResponse
    {
        $zedUIFormResquestActionTransfer = $this->buildZedUiFormRequestActionTransfer(null, $result ?? static::VALIDATION_RESPONSE_SUCCESS)
            ->setFormSelector($this->getParameterFromRequest($request, static::MODAL_FORM_SELECTOR_PARAMETER, $formName))
            ->setAjaxFormSelector($this->getParameterFromRequest($request, static::MODAL_AJAX_FORM_SELECTOR_PARAMETER, $formName))
            ->setIsLogin($this->getParameterFromRequest($request, static::MODAL_IS_LOGIN_PARAMETER, $formName))
            ->setUrl($request->headers->get('referer'));

        return $this->buildResponse($zedUIFormResquestActionTransfer, 'close');
    }

    /**
     * @param \Generated\Shared\Transfer\ZedUiFormRequestActionTransfer $zedUIFormRequestActionTransfer
     * @param string $responseType
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function buildResponse(ZedUiFormRequestActionTransfer $zedUIFormRequestActionTransfer, string $responseType): JsonResponse
    {
        $methodName = sprintf('return%sModalResponse', ucfirst($responseType));

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
        return $this->getFactory()
            ->getZedUiFactory()
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
        return $this->getFactory()
            ->getZedUiFactory()
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
        $zedUiFormResponseBuilder = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addActionCloseModal($zedUIFormRequestActionTransfer);

        if (
            $zedUIFormRequestActionTransfer->getResultOrFail() === static::VALIDATION_RESPONSE_SUCCESS
            && $zedUIFormRequestActionTransfer->getIsLogin() === false
            && !$zedUIFormRequestActionTransfer->getAjaxFormSelector()
        ) {
            $zedUiFormResponseBuilder->addActionSubmitForm($zedUIFormRequestActionTransfer);
        }

        if ($zedUIFormRequestActionTransfer->getIsLogin() || $zedUIFormRequestActionTransfer->getResult() === static::VALIDATION_RESPONSE_ERROR) {
            $zedUiFormResponseBuilder->addActionRedirect($zedUIFormRequestActionTransfer->getUrl() ?? '');
        }

        if ($zedUIFormRequestActionTransfer->getAjaxFormSelector()) {
            $zedUiFormResponseBuilder->addActionSubmitAjaxForm($zedUIFormRequestActionTransfer);
        }

        return $zedUiFormResponseBuilder->createResponse()
            ->toArray();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    protected function returnSubmitAjaxFormResponse(Request $request): array
    {
        $zedUIFormRequestActionTransfer = $this->buildZedUiFormRequestActionTransfer()
            ->setAjaxFormSelector($this->getParameterFromRequest($request, static::MODAL_AJAX_FORM_SELECTOR_PARAMETER));

        return $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addActionSubmitAjaxForm($zedUIFormRequestActionTransfer)
            ->createResponse()
            ->toArray();
    }

    /**
     * @param string|null $renderedForm
     * @param string|null $validationResult
     *
     * @return \Generated\Shared\Transfer\ZedUiFormRequestActionTransfer
     */
    protected function buildZedUiFormRequestActionTransfer(
        ?string $renderedForm = null,
        ?string $validationResult = null
    ): ZedUiFormRequestActionTransfer {
        return (new ZedUiFormRequestActionTransfer())
            ->setForm($renderedForm)
            ->setResult($validationResult);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    protected function executePostLoginMultiFactorAuthenticationPlugins(UserTransfer $userTransfer): void
    {
        foreach ($this->getFactory()->getPostLoginMultiFactorAuthenticationPlugins() as $plugin) {
            if ($plugin->isApplicable(static::MERCHANT_USER_POST_AUTHENTICATION_TYPE) === false) {
                continue;
            }

            $plugin->createToken($userTransfer->getUsernameOrFail());
            $plugin->executeOnAuthenticationSuccess($userTransfer);

            break;
        }
    }

    /**
     * @return string
     */
    protected function getSendCodeTemplatePath(): string
    {
        return '@MultiFactorAuth/MerchantUser/send-code.twig';
    }

    /**
     * @return string
     */
    protected function getGetEnabledTypesTemplatePath(): string
    {
        return '@MultiFactorAuth/MerchantUser/get-enabled-types.twig';
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    protected function getOptions(Request $request): array
    {
        $options = parent::getOptions($request);
        $options[static::MODAL_FORM_SELECTOR_PARAMETER] = $this->getParameterFromRequest($request, static::MODAL_FORM_SELECTOR_PARAMETER);
        $options[static::MODAL_AJAX_FORM_SELECTOR_PARAMETER] = $this->getParameterFromRequest($request, static::MODAL_AJAX_FORM_SELECTOR_PARAMETER);
        $options[static::MODAL_IS_LOGIN_PARAMETER] = $this->getParameterFromRequest($request, static::MODAL_IS_LOGIN_PARAMETER);

        return $options;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getTypeSelectionForm(array $options = []): FormInterface
    {
        return $this->getFactory()->getMerchantPortalTypeSelectionForm($options);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getCodeValidationForm(array $options = []): FormInterface
    {
        return $this->getFactory()->getMerchantPortalCodeValidationForm($options);
    }

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Communication\Form\DataProvider\TypeSelectionFormDataProvider
     */
    protected function getTypeSelectionFormDataProvider(): TypeSelectionFormDataProvider
    {
        return $this->getFactory()->createMerchantPortalTypeSelectionFormDataProvider();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $formName
     *
     * @return array<string, mixed>
     */
    protected function extractSetupParameters(Request $request, ?string $formName = null): array
    {
        return array_merge(parent::extractSetupParameters($request, $formName), [
            static::MODAL_FORM_SELECTOR_PARAMETER => $this->getParameterFromRequest($request, static::MODAL_FORM_SELECTOR_PARAMETER, $formName),
            static::MODAL_AJAX_FORM_SELECTOR_PARAMETER => $this->getParameterFromRequest($request, static::MODAL_AJAX_FORM_SELECTOR_PARAMETER, $formName),
            static::MODAL_IS_LOGIN_PARAMETER => $this->getParameterFromRequest($request, static::MODAL_IS_LOGIN_PARAMETER, $formName),
        ]);
    }
}
