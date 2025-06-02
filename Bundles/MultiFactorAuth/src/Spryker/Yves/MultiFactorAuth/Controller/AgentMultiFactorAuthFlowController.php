<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Controller;

use Generated\Shared\Transfer\MultiFactorAuthCodeTransfer;
use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 * @method \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface getClient()
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class AgentMultiFactorAuthFlowController extends AbstractMultiFactorAuthController
{
    /**
     * @uses {@link \SprykerShop\Yves\AgentPage\Plugin\MultiFactorAuth\PostAgentLoginMultiFactorAuthenticationPlugin::AGENT_POST_AUTHENTICATION_TYPE}
     *
     * @var string
     */
    protected const AGENT_POST_AUTHENTICATION_TYPE = 'AGENT_POST_AUTHENTICATION_TYPE';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function getAgentEnabledTypesAction(Request $request): View
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
    public function sendAgentCodeAction(Request $request, ?string $multiFactorAuthType = null, ?FormInterface $form = null): View
    {
        return $this->sendCodeAction($request, $multiFactorAuthType, $form);
    }

    /**
     * @return string
     */
    protected function getTypeSelectionFormTemplate(): string
    {
        return '@MultiFactorAuth/views/user-multi-factor-auth/type-selection-form.twig';
    }

    /**
     * @return string
     */
    protected function getCodeValidationFormTemplate(): string
    {
        return '@MultiFactorAuth/views/user-multi-factor-auth/code-validation-form.twig';
    }

    /**
     * @param string $multiFactorAuthType
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function sendCode(string $multiFactorAuthType, AbstractTransfer $userTransfer, Request $request): void
    {
        foreach ($this->getFactory()->getAgentMultiFactorAuthPlugins() as $plugin) {
            if ($plugin->isApplicable($multiFactorAuthType) === false) {
                continue;
            }

            if ($this->assertIsActivation($request)) {
                $this->getFactory()->createAgentMultiFactorAuthActivator()->activate($request, $userTransfer);
            }

            $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
                ->setUser($userTransfer)
                ->setType($multiFactorAuthType);

            $plugin->sendCode($multiFactorAuthTransfer);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $formName
     *
     * @return \Generated\Shared\Transfer\UserTransfer
     */
    protected function getIdentity(Request $request, ?string $formName = null): UserTransfer
    {
        if ($this->getFactory()->getAgentClient()->isLoggedIn() === true) {
            return $this->getFactory()->getAgentClient()->getAgent();
        }

        $email = $this->getParameterFromRequest($request, UserTransfer::EMAIL, $formName);

        /** @var \Generated\Shared\Transfer\UserTransfer $userTransfer */
        $userTransfer = $this->getFactory()->getAgentClient()->findAgentByUsername((new UserTransfer())->setUsername($email));

        return $userTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     * @param \Symfony\Component\Form\FormInterface $codeValidationForm
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    protected function validateCode(
        AbstractTransfer $userTransfer,
        FormInterface $codeValidationForm
    ): MultiFactorAuthValidationResponseTransfer {
        $multiFactorAuthCodeTransfer = (new MultiFactorAuthCodeTransfer())
            ->setCode($codeValidationForm->getData()[static::AUTHENTICATION_CODE]);

        $multiFactorAuthTransfer = (new MultiFactorAuthTransfer())
            ->setUser($userTransfer)
            ->setType($codeValidationForm->getData()[MultiFactorAuthTransfer::TYPE])
            ->setMultiFactorAuthCode($multiFactorAuthCodeTransfer);

        return $this->getClient()->validateAgentCode($multiFactorAuthTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\UserTransfer $userTransfer
     *
     * @return void
     */
    protected function executePostLoginMultiFactorAuthenticationPlugins(AbstractTransfer $userTransfer): void
    {
        foreach ($this->getFactory()->getPostLoginMultiFactorAuthenticationPlugins() as $plugin) {
            if ($plugin->isApplicable(static::AGENT_POST_AUTHENTICATION_TYPE) === false) {
                continue;
            }

            $plugin->createToken($userTransfer->getUsernameOrFail());
            $plugin->executeOnAuthenticationSuccess($userTransfer);
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
        $userTransfer = $this->getIdentity($request, $form?->getName());

        $options = $this->getFactory()->createAgentTypeSelectionFormDataProvider()->getOptions($userTransfer);
        $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setUser($userTransfer);

        if ($this->assertIsActivation($request) && $this->getClient()->validateAgentMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer)->getIsRequired() === false) {
            $options[static::OPTION_TYPES] = [$this->getParameterFromRequest($request, static::TYPE_TO_SET_UP)];
        }

        return $options;
    }
}
