<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\Controller;

use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Generated\Shared\Transfer\ZedUiFormRequestActionTransfer;
use Spryker\Shared\MultiFactorAuthMerchantPortal\MultiFactorAuthMerchantPortalConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * @method \Spryker\Zed\MultiFactorAuthMerchantPortal\MultiFactorAuthMerchantPortalConfig getConfig()
 * @method \Spryker\Zed\MultiFactorAuthMerchantPortal\Communication\MultiFactorAuthMerchantPortalCommunicationFactory getFactory()
 */
class MerchantUserController extends AbstractController
{
    /**
     * @var string
     */
    public const IS_ACTIVATION = 'is_activation';

    /**
     * @var string
     */
    public const IS_DEACTIVATION = 'is_deactivation';

    /**
     * @var string
     */
    public const TYPE_TO_SET_UP = 'type_to_set_up';

    /**
     * @var string
     */
    public const TYPES = 'types';

    /**
     * @var string
     */
    public const MODAL_FORM_SELECTOR_PARAMETER = 'form_selector';

    /**
     * @var string
     */
    public const MODAL_AJAX_FORM_SELECTOR_PARAMETER = 'ajax_form_selector';

    /**
     * @var string
     */
    public const MODAL_IS_LOGIN_PARAMETER = 'is_login';

    /**
     * @var string
     */
    public const VALIDATION_RESPONSE_ERROR = 'error';

    /**
     * @var string
     */
    public const VALIDATION_RESPONSE_SUCCESS = 'success';

    /**
     * @var string
     */
    protected const MESSAGE_REQUIRED_SELECTION_ERROR = 'Please choose how you would like to verify your identity.';

    /**
     * @var string
     */
    protected const MESSAGE_SENDING_CODE_ERROR = 'Something went wrong while sending your code. Please try again later or contact the system administrator.';

    /**
     * @var string
     */
    protected const MESSAGE_CORRUPTED_CODE_ERROR = 'The provided code is empty or invalid. Please try again.';

    /**
     * @var string
     */
    protected const AUTHENTICATION_CODE = 'authentication_code';

    /**
     * @var string
     */
    protected const RESPONSE_TYPE_OPEN_MODAL = 'OpenModal';

    /**
     * @var string
     */
    protected const RESPONSE_TYPE_REFRESH_MODAL = 'RefreshModal';

    /**
     * @var string
     */
    protected const RESPONSE_TYPE_CLOSE_MODAL = 'CloseModal';

    /**
     * @var string
     */
    protected const RESPONSE_TYPE_SUBMIT_AJAX_FORM = 'SubmitAjaxForm';

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
        $options = $this->getFactory()->createTypeSelectionFormDataProvider()->getOptions($request);

        if ($this->assertNoTypesEnabled($options)) {
            if ($this->isSetUpMultiFactorAuthStep($request) === false) {
                return $this->returnSubmitAjaxFormResponse($request);
            }

            return $this->sendCodeAction($request, $this->getParameterFromRequest($request, static::TYPE_TO_SET_UP));
        }

        if ($this->assertOneTypeEnabled($options)) {
            return $this->sendCodeAction($request, $options[static::TYPES][0]);
        }

        $typeSelectionFormName = $this->getFactory()->getTypeSelectionForm($options)->getName();

        if ($this->isSetUpMultiFactorAuthStep($request, $typeSelectionFormName) === true) {
            $options = array_merge($options, $this->extractSetupParameters($request, $typeSelectionFormName));
        }

        $typeSelectionForm = $this->getFactory()->getTypeSelectionForm($options)->handleRequest($request);

        if ($typeSelectionForm->isSubmitted()) {
            $selectedType = $this->getParameterFromRequest($request, MultiFactorAuthTransfer::TYPE, $typeSelectionFormName);

            if ($selectedType === null) {
                $typeSelectionForm->addError(new FormError(static::MESSAGE_REQUIRED_SELECTION_ERROR));

                return $this->returnGetEnabledTypesResponse(['form' => $typeSelectionForm->createView()]);
            }

            return $this->sendCodeAction($request, $selectedType, $typeSelectionForm, static::RESPONSE_TYPE_REFRESH_MODAL);
        }

        return $this->returnGetEnabledTypesResponse(['form' => $typeSelectionForm->createView()]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $multiFactorAuthType
     * @param \Symfony\Component\Form\FormInterface|null $form
     * @param string $responseType
     *
     * @return \Symfony\Component\HttpFoundation\Response|array<string, mixed>
     */
    public function sendCodeAction(
        Request $request,
        ?string $multiFactorAuthType = null,
        ?FormInterface $form = null,
        string $responseType = self::RESPONSE_TYPE_OPEN_MODAL
    ) {
        $formName = $form?->getName() ?? $this->getFactory()->getCodeValidationForm()->getName();
        $multiFactorAuthType = $multiFactorAuthType ?? $this->getParameterFromRequest($request, MultiFactorAuthTransfer::TYPE, $formName);
        $userTransfer = $this->getFactory()->createUserReader()->getUser();
        $options = array_merge([
            static::TYPES => [$multiFactorAuthType],
        ], $this->extractSetupParameters($request, $formName));

        $codeValidationForm = $this->getFactory()->getCodeValidationForm($options)->handleRequest($request);

        if ($codeValidationForm->isSubmitted() === false) {
            try {
                $this->sendUserCode($multiFactorAuthType, $userTransfer, $request);

                return $this->returnSendCodeResponse(['form' => $codeValidationForm->createView()], $responseType);
            } catch (Throwable $e) {
                return $this->returnSendCodeResponse([
                    'form' => $codeValidationForm->createView(),
                    'errorMessage' => static::MESSAGE_SENDING_CODE_ERROR,
                ], $responseType);
            }
        }

        return $this->executeCodeValidation($request, $codeValidationForm, $userTransfer);
    }

    /**
     * @param string $multiFactorAuthType
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function sendUserCode(string $multiFactorAuthType, UserTransfer $userTransfer, Request $request): void
    {
        foreach ($this->getFactory()->getUserMultiFactorAuthPlugins() as $plugin) {
            if ($plugin->isApplicable($multiFactorAuthType) === false) {
                continue;
            }

            if ($this->assertIsActivation($request)) {
                $this->getFactory()->createUserMultiFactorAuthActivator()->activate($request, $userTransfer);
            }

            $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
                ->setUser($userTransfer)
                ->setType($multiFactorAuthType);

            $plugin->sendCode($multiFactorAuthTransfer);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $codeValidationForm
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return \Symfony\Component\HttpFoundation\Response|array<string, mixed>
     */
    protected function executeCodeValidation(
        Request $request,
        FormInterface $codeValidationForm,
        UserTransfer $userTransfer
    ) {
        $multiFactorAuthType = $codeValidationForm->getData()[MultiFactorAuthTransfer::TYPE];
        $multiFactorAuthValidationResponseTransfer = $this->validateCode($userTransfer, $codeValidationForm);

        if ($multiFactorAuthValidationResponseTransfer->getStatus() === MultiFactorAuthMerchantPortalConstants::CODE_VERIFIED) {
            if ($this->isSelectedTypeVerificationRequired($request, $multiFactorAuthType, $codeValidationForm->getName())) {
                $request->request->set(MultiFactorAuthTransfer::TYPE, $codeValidationForm->getData()[static::TYPE_TO_SET_UP]);
                $request->request->remove($codeValidationForm->getName());

                return $this->sendCodeAction($request, null, $codeValidationForm, static::RESPONSE_TYPE_REFRESH_MODAL);
            }

            $this->executePostLoginMultiFactorAuthenticationPlugins($userTransfer);

            return $this->returnValidationResponse($request, null, $codeValidationForm->getName());
        }

        if ($multiFactorAuthValidationResponseTransfer->getStatus() === MultiFactorAuthMerchantPortalConstants::CODE_BLOCKED) {
            $this->addErrorMessage($multiFactorAuthValidationResponseTransfer->getMessageOrFail());

            return $this->returnValidationResponse($request, static::VALIDATION_RESPONSE_ERROR, $codeValidationForm->getName());
        }

        $codeValidationForm->addError(new FormError($multiFactorAuthValidationResponseTransfer->getMessageOrFail()));

        return $this->returnSendCodeResponse(['form' => $codeValidationForm->createView()], static::RESPONSE_TYPE_REFRESH_MODAL);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Symfony\Component\Form\FormInterface $codeValidationForm
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    protected function validateCode(
        UserTransfer $userTransfer,
        FormInterface $codeValidationForm
    ): MultiFactorAuthValidationResponseTransfer {
        $code = $codeValidationForm->getData()[static::AUTHENTICATION_CODE] ?? null;

        if ($code === null) {
            return (new MultiFactorAuthValidationResponseTransfer())
                ->setStatus(MultiFactorAuthMerchantPortalConstants::CODE_UNVERIFIED)
                ->setMessage(static::MESSAGE_CORRUPTED_CODE_ERROR);
        }

        $multiFactorAuthCodeTransfer = (new MultiFactorAuthCodeTransfer())
            ->setCode($code);

        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setUser($userTransfer)
            ->setType($codeValidationForm->getData()[MultiFactorAuthTransfer::TYPE])
            ->setMultiFactorAuthCode($multiFactorAuthCodeTransfer);

        return $this->getFactory()->getMultiFactorAuthFacade()->validateUserCode($multiFactorAuthTransfer);
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
     * @param array<string, mixed> $responseData
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function returnGetEnabledTypesResponse(array $responseData): JsonResponse
    {
        /** @var string $renderedForm */
        $renderedForm = $this->renderView(
            $this->getGetEnabledTypesTemplatePath(),
            $responseData,
        )->getContent();

        $zedUIFormResquestActionTransfer = (new ZedUiFormRequestActionTransfer())->setForm($renderedForm);

        return $this->getFactory()->createResponseBuilder()->buildResponse($zedUIFormResquestActionTransfer, static::RESPONSE_TYPE_OPEN_MODAL);
    }

    /**
     * @param array<string, mixed> $responseData
     * @param string $responseType
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function returnSendCodeResponse(array $responseData, string $responseType): JsonResponse
    {
        /** @var string $renderedForm */
        $renderedForm = $this->renderView(
            $this->getSendCodeTemplatePath(),
            $responseData,
        )->getContent();

        $zedUIFormResquestActionTransfer = (new ZedUiFormRequestActionTransfer())->setForm($renderedForm);

        return $this->getFactory()->createResponseBuilder()->buildResponse($zedUIFormResquestActionTransfer, $responseType);
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
        $zedUIFormResquestActionTransfer = (new ZedUiFormRequestActionTransfer())
            ->fromArray($this->extractSetupParameters($request, $formName), true)
            ->setResult($result ?? static::VALIDATION_RESPONSE_SUCCESS)
            ->setUrl($request->headers->get('referer'));

        return $this->getFactory()->createResponseBuilder()->buildResponse($zedUIFormResquestActionTransfer, static::RESPONSE_TYPE_CLOSE_MODAL);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function returnSubmitAjaxFormResponse(Request $request): JsonResponse
    {
        $zedUIFormResquestActionTransfer = (new ZedUiFormRequestActionTransfer())
            ->setAjaxFormSelector($this->getParameterFromRequest($request, static::MODAL_AJAX_FORM_SELECTOR_PARAMETER));

        return $this->getFactory()->createResponseBuilder()->buildResponse($zedUIFormResquestActionTransfer, static::RESPONSE_TYPE_SUBMIT_AJAX_FORM);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $formName
     *
     * @return bool
     */
    protected function isSetUpMultiFactorAuthStep(Request $request, ?string $formName = null): bool
    {
        return $this->assertIsActivation($request, $formName) || $this->assertIsDeactivation($request, $formName);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $multiFactorAuthType
     * @param string|null $formName
     *
     * @return bool
     */
    protected function isSelectedTypeVerificationRequired(Request $request, string $multiFactorAuthType, ?string $formName = null): bool
    {
        return $this->assertIsActivation($request, $formName) && $this->getParameterFromRequest($request, static::TYPE_TO_SET_UP, $formName) !== $multiFactorAuthType;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return bool
     */
    protected function assertNoTypesEnabled(array $options): bool
    {
        return $options[static::TYPES] === [];
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return bool
     */
    protected function assertOneTypeEnabled(array $options): bool
    {
        return count($options[static::TYPES]) === 1;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $formName
     *
     * @return string|null
     */
    protected function assertIsActivation(Request $request, ?string $formName = null): ?string
    {
        return $this->getParameterFromRequest($request, static::IS_ACTIVATION, $formName);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $formName
     *
     * @return string|null
     */
    protected function assertIsDeactivation(Request $request, ?string $formName = null): ?string
    {
        return $this->getParameterFromRequest($request, static::IS_DEACTIVATION, $formName);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $formName
     *
     * @return array<string, mixed>
     */
    protected function extractSetupParameters(Request $request, ?string $formName = null): array
    {
        return [
            static::IS_ACTIVATION => $this->assertIsActivation($request, $formName),
            static::IS_DEACTIVATION => $this->assertIsDeactivation($request, $formName),
            static::TYPE_TO_SET_UP => $this->getParameterFromRequest($request, static::TYPE_TO_SET_UP, $formName),
            static::MODAL_FORM_SELECTOR_PARAMETER => $this->getParameterFromRequest($request, static::MODAL_FORM_SELECTOR_PARAMETER, $formName),
            static::MODAL_AJAX_FORM_SELECTOR_PARAMETER => $this->getParameterFromRequest($request, static::MODAL_AJAX_FORM_SELECTOR_PARAMETER, $formName),
            static::MODAL_IS_LOGIN_PARAMETER => $this->getParameterFromRequest($request, static::MODAL_IS_LOGIN_PARAMETER, $formName),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $parameter
     * @param string|null $formName
     *
     * @return mixed
     */
    protected function getParameterFromRequest(Request $request, string $parameter, ?string $formName = null): mixed
    {
        return $this->getFactory()->createRequestReader()->get($request, $parameter, $formName);
    }

    /**
     * @return string
     */
    protected function getGetEnabledTypesTemplatePath(): string
    {
        return '@MultiFactorAuthMerchantPortal/MerchantUser/get-enabled-types.twig';
    }

    /**
     * @return string
     */
    protected function getSendCodeTemplatePath(): string
    {
        return '@MultiFactorAuthMerchantPortal/MerchantUser/send-code.twig';
    }

    /**
     * @return string
     */
    protected function getValidationResponseTemplatePath(): string
    {
        return '@MultiFactorAuthMerchantPortal/Partials/validation-response.twig';
    }
}
