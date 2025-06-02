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
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 * @method \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface getClient()
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class CustomerMultiFactorAuthFlowController extends AbstractMultiFactorAuthController
{
    /**
     * @uses {@link \SprykerShop\Yves\CustomerPage\Plugin\MultiFactorAuth\PostCustomerLoginMultiFactorAuthenticationPlugin::CUSTOMER_POST_AUTHENTICATION_TYPE}
     *
     * @var string
     */
    protected const CUSTOMER_POST_AUTHENTICATION_TYPE = 'CUSTOMER_POST_AUTHENTICATION_TYPE';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function getCustomerEnabledTypesAction(Request $request): View
    {
        return $this->getEnabledTypesAction($request);
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
        return $this->sendCodeAction($request, $multiFactorAuthType, $form);
    }

    /**
     * @return string
     */
    protected function getTypeSelectionFormTemplate(): string
    {
        return '@MultiFactorAuth/views/customer-multi-factor-auth/type-selection-form.twig';
    }

    /**
     * @return string
     */
    protected function getCodeValidationFormTemplate(): string
    {
        return '@MultiFactorAuth/views/customer-multi-factor-auth/code-validation-form.twig';
    }

    /**
     * @param string $multiFactorAuthType
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function sendCode(string $multiFactorAuthType, AbstractTransfer $customerTransfer, Request $request): void
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
     * @param string|null $formName
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function getIdentity(Request $request, ?string $formName = null): CustomerTransfer
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customerTransfer !== null) {
            return $customerTransfer;
        }

        $email = $this->getParameterFromRequest($request, CustomerTransfer::EMAIL, $formName);
        $customerTransfer = (new CustomerTransfer())->setEmail($email);

        return $this->getFactory()->getCustomerClient()->getCustomerByEmail($customerTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Symfony\Component\Form\FormInterface $codeValidationForm
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    protected function validateCode(
        AbstractTransfer $customerTransfer,
        FormInterface $codeValidationForm
    ): MultiFactorAuthValidationResponseTransfer {
        $multiFactorAuthCodeTransfer = (new MultiFactorAuthCodeTransfer())
            ->setCode($codeValidationForm->getData()[static::AUTHENTICATION_CODE]);

        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setCustomer($customerTransfer)
            ->setType($codeValidationForm->getData()[MultiFactorAuthTransfer::TYPE])
            ->setMultiFactorAuthCode($multiFactorAuthCodeTransfer);

        return $this->getClient()->validateCustomerCode($multiFactorAuthTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function executePostLoginMultiFactorAuthenticationPlugins(AbstractTransfer $customerTransfer): void
    {
        foreach ($this->getFactory()->getPostLoginMultiFactorAuthenticationPlugins() as $plugin) {
            if ($plugin->isApplicable(static::CUSTOMER_POST_AUTHENTICATION_TYPE) === false) {
                continue;
            }

            $plugin->createToken($customerTransfer->getEmailOrFail());
            $plugin->executeOnAuthenticationSuccess($customerTransfer);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface|null $form
     *
     * @return array<string, mixed>
     */
    protected function getOptions(Request $request, ?FormInterface $form = null): array
    {
        $customerTransfer = $this->getIdentity($request, $form ? $form->getName() : null);

        $options = $this->getFactory()->createCustomerTypeSelectionFormDataProvider()->getOptions($customerTransfer);
        $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setCustomer($customerTransfer);

        if ($this->assertIsActivation($request) && $this->getClient()->validateCustomerMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer)->getIsRequired() === false) {
            $options[static::OPTION_TYPES] = [$this->getParameterFromRequest($request, static::TYPE_TO_SET_UP)];
        }

        return $options;
    }
}
