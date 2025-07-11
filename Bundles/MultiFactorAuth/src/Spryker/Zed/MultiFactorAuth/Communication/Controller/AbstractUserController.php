<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Controller;

use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;
use Generated\Shared\Transfer\UserConditionsTransfer;
use Generated\Shared\Transfer\UserCriteriaTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\MultiFactorAuth\Communication\Form\DataProvider\TypeSelectionFormDataProvider;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 */
abstract class AbstractUserController extends AbstractController
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
     * @uses \Spryker\Zed\MultiFactorAuth\Communication\Form\DataProvider\TypeSelectionFormDataProvider::OPTION_TYPES
     *
     * @var string
     */
    protected const OPTION_TYPES = 'types';

    /**
     * @var string
     */
    protected const TYPE_TO_SET_UP = 'type_to_set_up';

    /**
     * @var string
     */
    protected const USERNAME = 'username';

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
    protected const RESPONSE_TYPE_OPEN = 'open';

    /**
     * @var string
     */
    protected const RESPONSE_TYPE_REFRESH = 'refresh';

    /**
     * @var string
     */
    protected const VALIDATION_RESPONSE_ERROR = 'error';

    /**
     * @uses {@link \Spryker\Zed\SecurityGui\Communication\Plugin\Security\Handler\UserAuthenticationSuccessHandler::MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY}
     *
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY = '_multi_factor_auth_login_user_email';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|array<string, mixed>
     */
    public function getEnabledTypesAction(Request $request)
    {
        $options = $this->getOptions($request);

        if ($this->assertNoTypesEnabled($options) && $this->isSetUpMultiFactorAuthStep($request) === true) {
            return $this->sendCodeAction($request, $this->getParameterFromRequest($request, static::TYPE_TO_SET_UP));
        }

        if ($this->assertOneTypeEnabled($options)) {
            return $this->sendCodeAction($request, $options[static::OPTION_TYPES][0]);
        }

        $typeSelectionFormName = $this->getTypeSelectionForm($options)->getName();

        if ($this->isSetUpMultiFactorAuthStep($request, $typeSelectionFormName) === true) {
            $options = array_merge($options, $this->extractSetupParameters($request, $typeSelectionFormName));
        }

        $typeSelectionForm = $this->getTypeSelectionForm($options)->handleRequest($request);

        if ($typeSelectionForm->isSubmitted()) {
            $selectedType = $this->getParameterFromRequest($request, MultiFactorAuthTransfer::TYPE, $typeSelectionFormName);

            if ($selectedType === null) {
                $typeSelectionFormView = $this->getTypeSelectionForm($this->getOptions($request))
                    ->handleRequest($request)
                    ->addError(new FormError(static::MESSAGE_REQUIRED_SELECTION_ERROR))
                    ->createView();

                return $this->returnGetEnabledTypesResponse($request, ['form' => $typeSelectionFormView]);
            }

            return $this->sendCodeAction($request, $selectedType, $typeSelectionForm, static::RESPONSE_TYPE_REFRESH);
        }

        return $this->returnGetEnabledTypesResponse($request, ['form' => $typeSelectionForm->createView()]);
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
        string $responseType = self::RESPONSE_TYPE_OPEN
    ) {
        $formName = $form?->getName() ?? $this->getCodeValidationForm()->getName();
        $multiFactorAuthType = $multiFactorAuthType ?? $this->getParameterFromRequest($request, MultiFactorAuthTransfer::TYPE, $formName);
        $userTransfer = $this->getUser();
        $options = array_merge([
            static::OPTION_TYPES => [$multiFactorAuthType],
            static::USERNAME => $userTransfer->getUsername(),
        ], $this->extractSetupParameters($request, $formName));

        $codeValidationForm = $this->getCodeValidationForm($options)
            ->handleRequest($request);

        if ($codeValidationForm->isSubmitted() === false) {
            try {
                $this->sendUserCode($multiFactorAuthType, $userTransfer, $request);

                return $this->returnSendCodeResponse($request, ['form' => $codeValidationForm->createView()], $responseType);
            } catch (Throwable $e) {
                return $this->returnSendCodeResponse($request, [
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

        if ($multiFactorAuthValidationResponseTransfer->getStatus() === MultiFactorAuthConstants::CODE_VERIFIED) {
            if ($this->isSelectedTypeVerificationRequired($request, $multiFactorAuthType, $codeValidationForm->getName())) {
                $request->request->set(MultiFactorAuthTransfer::TYPE, $codeValidationForm->getData()[static::TYPE_TO_SET_UP]);
                $request->request->remove($codeValidationForm->getName());

                return $this->sendCodeAction($request, null, $codeValidationForm, static::RESPONSE_TYPE_REFRESH);
            }

            $this->executePostLoginMultiFactorAuthenticationPlugins($userTransfer);

            return $this->returnValidationResponse($request, null, $codeValidationForm->getName());
        }

        if ($multiFactorAuthValidationResponseTransfer->getStatus() === MultiFactorAuthConstants::CODE_BLOCKED) {
            $this->addErrorMessage($multiFactorAuthValidationResponseTransfer->getMessageOrFail());

            return $this->returnValidationResponse($request, static::VALIDATION_RESPONSE_ERROR, $codeValidationForm->getName());
        }

        $codeValidationForm->addError(new FormError($multiFactorAuthValidationResponseTransfer->getMessageOrFail()));

        return $this->returnSendCodeResponse($request, ['form' => $codeValidationForm->createView()], static::RESPONSE_TYPE_REFRESH);
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
                ->setStatus(MultiFactorAuthConstants::CODE_UNVERIFIED)
                ->setMessage(static::MESSAGE_CORRUPTED_CODE_ERROR);
        }

        $multiFactorAuthCodeTransfer = (new MultiFactorAuthCodeTransfer())
            ->setCode($code);

        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setUser($userTransfer)
            ->setType($codeValidationForm->getData()[MultiFactorAuthTransfer::TYPE])
            ->setMultiFactorAuthCode($multiFactorAuthCodeTransfer);

        return $this->getFacade()->validateUserCode($multiFactorAuthTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array<string, mixed> $responseData
     *
     * @return mixed
     */
    abstract protected function returnGetEnabledTypesResponse(Request $request, array $responseData);

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array<string, mixed> $responseData
     * @param string $responseType
     *
     * @return mixed
     */
    abstract protected function returnSendCodeResponse(Request $request, array $responseData, string $responseType);

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $result
     * @param string|null $formName
     *
     * @return mixed
     */
    abstract protected function returnValidationResponse(Request $request, ?string $result = null, ?string $formName = null);

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    abstract protected function executePostLoginMultiFactorAuthenticationPlugins(UserTransfer $userTransfer): void;

    /**
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    abstract protected function getTypeSelectionForm(array $options = []): FormInterface;

    /**
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    abstract protected function getCodeValidationForm(array $options = []): FormInterface;

    /**
     * @return \Spryker\Zed\MultiFactorAuth\Communication\Form\DataProvider\TypeSelectionFormDataProvider
     */
    abstract protected function getTypeSelectionFormDataProvider(): TypeSelectionFormDataProvider;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array<string, mixed>
     */
    protected function getOptions(Request $request): array
    {
        $userTransfer = $this->getUser();

        $options = $this->getTypeSelectionFormDataProvider()->getOptions($userTransfer);
        $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setUser($userTransfer);

        if ($this->assertIsActivation($request) && $this->getFacade()->validateUserMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer)->getIsRequired() === false) {
            $options[static::OPTION_TYPES] = [$this->getParameterFromRequest($request, static::TYPE_TO_SET_UP)];
        }

        return $options;
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
        return $options[static::OPTION_TYPES] === [];
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return bool
     */
    protected function assertOneTypeEnabled(array $options): bool
    {
        return count($options[static::OPTION_TYPES]) === 1;
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
     *  Gets the user for the Multi-Factor Authentication flow.
     *
     *  This method supports two scenarios:
     *  1. An already authenticated user is setting up Multi-Factor Authentication (retrieved via `getUserFacade()`).
     *  2. A user is performing Multi-Factor Authentication during login. In this case, there is no authenticated user yet,
     *  so we retrieve the username from the session key set by the AuthenticationSuccessHandler of the corresponding bundle.
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function getUser(): UserTransfer
    {
        if ($this->getFactory()->getUserFacade()->hasCurrentUser() === true) {
            return $this->getFactory()->getUserFacade()->getCurrentUser();
        }

        $username = $this->getFactory()->getSessionClient()->get(static::MULTI_FACTOR_AUTH_LOGIN_USER_EMAIL_SESSION_KEY);

        if ($username === null) {
            return new UserTransfer();
        }

        $userCriteriaTransfer = (new UserCriteriaTransfer())
            ->setUserConditions((new UserConditionsTransfer())
                ->addUsername($username));

        return $this->getFactory()->getUserFacade()->getUserCollection($userCriteriaTransfer)->getUsers()->offsetGet(0);
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
    protected function getValidationResponseTemplatePath(): string
    {
        return '@MultiFactorAuth/Partials/validation-response.twig';
    }
}
