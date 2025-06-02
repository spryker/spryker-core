<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Form\Type\Extension;

use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface;
use Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

class MultiFactorAuthHandlerExtension extends BasicMultiFactorAuthTypeExtension
{
    /**
     * @var string
     */
    protected const MULTI_FACTOR_AUTH_HANDLERS = 'multiFactorHandlers';

    /**
     * @var string
     */
    protected const FORM_SELECTOR_PLACEHOLDER = '[name="%s"]';

    /**
     * @var string
     */
    protected const GET_ENABLED_TYPES_URL = '/multi-factor-auth/user/get-enabled-types';

    /**
     * @param \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig $config
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \Twig\Environment $twig
     * @param \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface $facade
     * @param \Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface $userFacade
     */
    public function __construct(
        protected MultiFactorAuthConfig $config,
        protected RequestStack $requestStack,
        protected Environment $twig,
        protected MultiFactorAuthFacadeInterface $facade,
        protected MultiFactorAuthToUserFacadeInterface $userFacade
    ) {
        parent::__construct($config, $requestStack);
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if ($this->assertFormMustNotBeValidated($form) || $this->assertUserMultiFactorAuthEnabled() === false) {
            return;
        }

        $formName = $form->getName();
        $view->vars[static::MULTI_FACTOR_AUTH_HANDLERS][$formName] = $this->renderMultiFactorHandler($formName);
    }

    /**
     * @param string $formName
     *
     * @return string
     */
    protected function renderMultiFactorHandler(string $formName): string
    {
        return $this->twig->render('@MultiFactorAuth/Partials/multi-factor-authentication-handler.twig', [
            'modalId' => $formName,
            'url' => static::GET_ENABLED_TYPES_URL,
            'isLoginFlow' => false,
            'formSelector' => sprintf(static::FORM_SELECTOR_PLACEHOLDER, $formName),
            'configurations' => $this->config->getEnabledRoutesAndForms(),
        ]);
    }

    /**
     * @return bool
     */
    protected function assertUserMultiFactorAuthEnabled(): bool
    {
        if ($this->userFacade->hasCurrentUser() === false) {
            return false;
        }

        $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setUser(
            $this->userFacade->getCurrentUser(),
        );
        $multiFactorAuthValidationResponseTransfer = $this->facade->validateUserMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer);

        return $multiFactorAuthValidationResponseTransfer->getIsRequiredOrFail();
    }
}
