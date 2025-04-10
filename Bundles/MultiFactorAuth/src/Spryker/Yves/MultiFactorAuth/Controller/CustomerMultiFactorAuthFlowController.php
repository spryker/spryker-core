<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 * @method \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface getClient()
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class CustomerMultiFactorAuthFlowController extends AbstractCustomerMultiFactorAuthController
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
    protected const EMAIL = 'email';

    /**
     * @var string
     */
    protected const MESSAGE_REQUIRED_SELECTION_ERROR = 'multi_factor_auth.selection.error.required';

    /**
     * @var string
     */
    protected const AUTHENTICATION_CODE = 'authentication_code';

    /**
     * @var string
     */
    protected const DATA_ERROR_PARAMETER = 'data-error';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function getCustomerEnabledTypesAction(Request $request): View
    {
        $options = $this->getOptions($request);

        if ($this->assertNoTypesEnabled($options) && $this->isSetUpMultiFactorAuthStep($request) === true) {
            return $this->sendCustomerCodeAction($request, $this->getParameterFromRequest($request, static::TYPE_TO_SET_UP));
        }

        if ($this->assertOneTypeEnabled($options)) {
            return $this->sendCustomerCodeAction($request, $options[static::OPTION_TYPES][0]);
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

                return $this->view(['form' => $typeSelectionFormView], [], '@MultiFactorAuth/views/customer-multi-factor-auth/type-selection-form.twig');
            }

            return $this->sendCustomerCodeAction($request, $selectedType, $typeSelectionForm);
        }

        return $this->view(['form' => $typeSelectionForm->createView()], [], '@MultiFactorAuth/views/customer-multi-factor-auth/type-selection-form.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $multiFactorAuthType
     * @param \Symfony\Component\Form\FormInterface|null $form
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function sendCustomerCodeAction(Request $request, ?string $multiFactorAuthType = null, ?FormInterface $form = null): View
    {
        $formName = $form?->getName() ?? $this->getFactory()->getCodeValidationForm()->getName();
        $multiFactorAuthType = $multiFactorAuthType ?? $this->getParameterFromRequest($request, static::TYPE, $formName);
        $customerTransfer = $this->getCustomer($request, $formName);
        $options = array_merge([
            static::OPTION_TYPES => [$multiFactorAuthType],
            static::EMAIL => $customerTransfer->getEmail(),
        ], $this->extractSetupParameters($request, $formName));

        $codeValidationForm = $this->getFactory()
            ->getCodeValidationForm($options)
            ->handleRequest($request);

        if ($codeValidationForm->isSubmitted() === false) {
            $this->sendCustomerCode($multiFactorAuthType, $customerTransfer, $request);

            return $this->view(['form' => $codeValidationForm->createView()], [], '@MultiFactorAuth/views/customer-multi-factor-auth/code-validation-form.twig');
        }

        return $this->executeCodeValidation($request, $codeValidationForm, $customerTransfer);
    }

    /**
     * @param string $multiFactorAuthType
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function sendCustomerCode(string $multiFactorAuthType, CustomerTransfer $customerTransfer, Request $request): void
    {
        foreach ($this->getFactory()->getCustomerMultiFactorAuthPlugins() as $plugin) {
            if ($plugin->isApplicable($multiFactorAuthType) === false) {
                continue;
            }

            if ($this->assertIsActivation($request)) {
                $this->getFactory()->createCustomerMultiFactorAuthActivator()->activate($request, $customerTransfer);
            }

            $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
                ->setCustomer($customerTransfer)
                ->setType($multiFactorAuthType);

            $plugin->sendCode($multiFactorAuthTransfer);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $codeValidationForm
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    protected function executeCodeValidation(
        Request $request,
        FormInterface $codeValidationForm,
        CustomerTransfer $customerTransfer
    ): View {
        $multiFactorAuthType = $codeValidationForm->getData()[static::TYPE];
        $multiFactorAuthValidationResponseTransfer = $this->validateCustomerCode($customerTransfer, $codeValidationForm);

        if ($multiFactorAuthValidationResponseTransfer->getStatus() === MultiFactorAuthConstants::CODE_VERIFIED) {
            if ($this->isSelectedTypeVerificationRequired($request, $multiFactorAuthType, $codeValidationForm->getName())) {
                $request->request->set(static::TYPE, $codeValidationForm->getData()[static::TYPE_TO_SET_UP]);
                $request->request->remove($codeValidationForm->getName());

                return $this->sendCustomerCodeAction($request, null, $codeValidationForm);
            }

            return $this->view([], [], '@MultiFactorAuth/views/customer-multi-factor-auth/validation-response.twig');
        }

        if ($multiFactorAuthValidationResponseTransfer->getStatus() === MultiFactorAuthConstants::CODE_BLOCKED) {
            $this->addErrorMessage($multiFactorAuthValidationResponseTransfer->getMessageOrFail());

            return $this->view(['dataError' => static::DATA_ERROR_PARAMETER], [], '@MultiFactorAuth/views/customer-multi-factor-auth/validation-response.twig');
        }

        $codeValidationForm->addError(new FormError($multiFactorAuthValidationResponseTransfer->getMessageOrFail()));

        return $this->view(['form' => $codeValidationForm->createView()], [], '@MultiFactorAuth/views/customer-multi-factor-auth/code-validation-form.twig');
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Symfony\Component\Form\FormInterface $codeValidationForm
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    protected function validateCustomerCode(
        CustomerTransfer $customerTransfer,
        FormInterface $codeValidationForm
    ): MultiFactorAuthValidationResponseTransfer {
        $multiFactorAuthCodeTransfer = (new MultiFactorAuthCodeTransfer())
            ->setCode($codeValidationForm->getData()[static::AUTHENTICATION_CODE]);

        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setCustomer($customerTransfer)
            ->setType($codeValidationForm->getData()[static::TYPE])
            ->setMultiFactorAuthCode($multiFactorAuthCodeTransfer);

        return $this->getClient()->validateCustomerCode($multiFactorAuthTransfer);
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
        $customerTransfer = $this->getCustomer($request, $form ? $form->getName() : null);

        $options = $this->getFactory()->createTypeSelectionFormDataProvider()->getOptions($customerTransfer);
        $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setCustomer($customerTransfer);

        if ($this->assertIsActivation($request) && $this->getClient()->validateCustomerMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer)->getIsRequired() === false) {
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
