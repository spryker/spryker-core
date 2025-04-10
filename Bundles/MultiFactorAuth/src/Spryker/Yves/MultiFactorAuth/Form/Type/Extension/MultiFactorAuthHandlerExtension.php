<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Form\Type\Extension;

use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface;
use Spryker\Yves\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface;
use Spryker\Yves\MultiFactorAuth\MultiFactorAuthConfig;
use Spryker\Yves\MultiFactorAuth\Plugin\Router\Customer\MultiFactorAuthCustomerRouteProviderPlugin;
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
     * @param \Spryker\Yves\MultiFactorAuth\MultiFactorAuthConfig $config
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \Twig\Environment $twig
     * @param \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface $client
     * @param \Spryker\Yves\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface $customerClient
     */
    public function __construct(
        protected MultiFactorAuthConfig $config,
        protected RequestStack $requestStack,
        protected Environment $twig,
        protected MultiFactorAuthClientInterface $client,
        protected MultiFactorAuthToCustomerClientInterface $customerClient
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
        if ($this->assertFormMustNotBeValidated($form) || $this->assertCustomerIsMultiFactorAuthEnabled() === false) {
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
        return $this->twig->render('@MultiFactorAuth/views/multi-factor-auth-handler/multi-factor-auth-handler.twig', [
            'id' => $formName,
            'url' => MultiFactorAuthCustomerRouteProviderPlugin::MULTI_FACTOR_AUTH_ROUTE_GET_CUSTOMER_ENABLED_TYPES,
            'isLoginFlow' => false,
            'formSelector' => sprintf(static::FORM_SELECTOR_PLACEHOLDER, $formName),
            'configurations' => $this->config->getEnabledRoutesAndForms(),
        ]);
    }

    /**
     * @return bool
     */
    protected function assertCustomerIsMultiFactorAuthEnabled(): bool
    {
        $customerTransfer = $this->customerClient->getCustomer();

        if ($customerTransfer === null) {
            return false;
        }

        $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setCustomer($customerTransfer);
        $multiFactorAuthValidationTransfer = $this->client->validateCustomerMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer);

        return $multiFactorAuthValidationTransfer->getIsRequiredOrFail();
    }
}
