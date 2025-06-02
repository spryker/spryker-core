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
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface getRepository()
 */
class UserController extends AbstractUserMultiFactorAuthController
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
     * @uses \Spryker\Yves\MultiFactorAuth\Form\DataProvider\TypeSelectionDataProvider::OPTION_TYPES
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
    protected const MESSAGE_REQUIRED_SELECTION_ERROR = 'multi_factor_auth.selection.error.required';

    /**
     * @var string
     */
    protected const MESSAGE_SENDING_CODE_ERROR = 'Something went wrong while sending your code. Please try again later or contact the system administrator.';

    /**
     * @var string
     */
    protected const AUTHENTICATION_CODE = 'authentication_code';

    /**
     * @var string
     */
    protected const DATA_ERROR_PARAMETER = 'data-error';

    /**
     * @var string
     */
    protected const DATA_SUCCESS_PARAMETER = 'data-success';

    /**
     * @uses {@link \Spryker\Zed\SecurityGui\Communication\Plugin\MultiFactorAuth\PostUserLoginMultiFactorAuthenticationPlugin::USER_POST_AUTHENTICATION_TYPE}
     *
     * @var string
     */
    protected const USER_POST_AUTHENTICATION_TYPE = 'USER_POST_AUTHENTICATION_TYPE';

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

        $typeSelectionFormName = $this->getFactory()->getTypeSelectionForm($options)->getName();

        if ($this->isSetUpMultiFactorAuthStep($request, $typeSelectionFormName) === true) {
            $options = array_merge($options, $this->extractSetupParameters($request, $typeSelectionFormName));
        }

        $typeSelectionForm = $this->getFactory()
            ->getTypeSelectionForm($options)
            ->handleRequest($request);

        if ($typeSelectionForm->isSubmitted()) {
            $selectedType = $this->getParameterFromRequest($request, static::TYPE, $typeSelectionFormName);

            if ($selectedType === null) {
                $typeSelectionFormView = $this->getFactory()
                    ->getTypeSelectionForm($this->getOptions($request, $typeSelectionForm))
                    ->handleRequest($request)
                    ->addError(new FormError(static::MESSAGE_REQUIRED_SELECTION_ERROR))
                    ->createView();

                return $this->viewResponse([
                    'form' => $typeSelectionForm->createView(),
                ]);
            }

            return $this->sendCodeAction($request, $selectedType, $typeSelectionForm);
        }

        return $this->viewResponse([
            'form' => $typeSelectionForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $multiFactorAuthType
     * @param \Symfony\Component\Form\FormInterface|null $form
     *
     * @return \Symfony\Component\HttpFoundation\Response|array<string, mixed>
     */
    public function sendCodeAction(Request $request, ?string $multiFactorAuthType = null, ?FormInterface $form = null)
    {
        $formName = $form?->getName() ?? $this->getFactory()->getCodeValidationForm()->getName();
        $multiFactorAuthType = $multiFactorAuthType ?? $this->getParameterFromRequest($request, MultiFactorAuthTransfer::TYPE, $formName);
        $userTransfer = $this->getUser($request, $formName);
        $options = array_merge([
            static::OPTION_TYPES => [$multiFactorAuthType],
            static::USERNAME => $userTransfer->getUsername(),
        ], $this->extractSetupParameters($request, $formName));

        $codeValidationForm = $this->getFactory()
            ->getCodeValidationForm($options)
            ->handleRequest($request);

        if ($codeValidationForm->isSubmitted() === false) {
            try {
                $this->sendUserCode($multiFactorAuthType, $userTransfer, $request);

                return $this->renderView('@MultiFactorAuth/User/send-code.twig', ['form' => $codeValidationForm->createView()]);
            } catch (Throwable $e) {
                return $this->renderView('@MultiFactorAuth/User/send-code.twig', [
                    'form' => $codeValidationForm->createView(),
                    'errorMessage' => static::MESSAGE_SENDING_CODE_ERROR,
                    ]);
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
        $multiFactorAuthType = $codeValidationForm->getData()[static::TYPE];
        $multiFactorAuthValidationResponseTransfer = $this->validateCode($userTransfer, $codeValidationForm);

        if ($multiFactorAuthValidationResponseTransfer->getStatus() === MultiFactorAuthConstants::CODE_VERIFIED) {
            if ($this->isSelectedTypeVerificationRequired($request, $multiFactorAuthType, $codeValidationForm->getName())) {
                $request->request->set(static::TYPE, $codeValidationForm->getData()[static::TYPE_TO_SET_UP]);
                $request->request->remove($codeValidationForm->getName());

                return $this->sendCodeAction($request, null, $codeValidationForm);
            }

            $this->executePostLoginMultiFactorAuthenticationPlugins($userTransfer);

            return $this->renderView('@MultiFactorAuth/Partials/validation-response.twig', ['dataResult' => static::DATA_SUCCESS_PARAMETER]);
        }

        if ($multiFactorAuthValidationResponseTransfer->getStatus() === MultiFactorAuthConstants::CODE_BLOCKED) {
            $this->addErrorMessage($multiFactorAuthValidationResponseTransfer->getMessageOrFail());

            return $this->renderView('@MultiFactorAuth/Partials/validation-response.twig', ['dataResult' => static::DATA_ERROR_PARAMETER]);
        }

        $codeValidationForm->addError(new FormError($multiFactorAuthValidationResponseTransfer->getMessageOrFail()));

        return $this->renderView('@MultiFactorAuth/User/send-code.twig', ['form' => $codeValidationForm->createView()]);
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
        $multiFactorAuthCodeTransfer = (new MultiFactorAuthCodeTransfer())
            ->setCode($codeValidationForm->getData()[static::AUTHENTICATION_CODE]);

        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setUser($userTransfer)
            ->setType($codeValidationForm->getData()[static::TYPE])
            ->setMultiFactorAuthCode($multiFactorAuthCodeTransfer);

        return $this->getFacade()->validateUserCode($multiFactorAuthTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    protected function executePostLoginMultiFactorAuthenticationPlugins(UserTransfer $userTransfer): void
    {
        foreach ($this->getFactory()->getPostLoginMultiFactorAuthenticationPlugins() as $plugin) {
            if ($plugin->isApplicable(static::USER_POST_AUTHENTICATION_TYPE) === false) {
                continue;
            }

            $plugin->createToken($userTransfer->getUsernameOrFail());
            $plugin->executeOnAuthenticationSuccess($userTransfer);
        }
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface|null $form
     *
     * @return array<string, mixed>
     */
    protected function getOptions(Request $request, ?FormInterface $form = null): array
    {
        $userTransfer = $this->getUser($request, $form?->getName());

        $options = $this->getFactory()->createTypeSelectionFormDataProvider()->getOptions($userTransfer);
        $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setUser($userTransfer);

        if ($this->assertIsActivation($request) && $this->getFacade()->validateUserMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer)->getIsRequired() === false) {
            $options[static::OPTION_TYPES] = [$this->getParameterFromRequest($request, static::TYPE_TO_SET_UP)];
        }

        return $options;
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
}
