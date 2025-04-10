<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Subscriber;

use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Yves\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

class MultiFactorAuthFormEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_ACCESS_DENIED = 'multi_factor_auth.access_denied';

    /**
     * @param \Spryker\Client\MultiFactorAuth\MultiFactorAuthClientInterface $client
     * @param \Spryker\Yves\MultiFactorAuth\Dependency\Client\MultiFactorAuthToCustomerClientInterface $customerClient
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     */
    public function __construct(
        protected MultiFactorAuthClientInterface $client,
        protected MultiFactorAuthToCustomerClientInterface $customerClient,
        protected TranslatorInterface $translator
    ) {
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function onPreSubmit(FormEvent $event): void
    {
        $customerTransfer = $this->customerClient->getCustomer();

        if ($customerTransfer === null) {
            return;
        }

        $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setCustomer($customerTransfer);
        $multiFactorAuthValidationTransfer = $this->client->validateCustomerMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer);

        if ($multiFactorAuthValidationTransfer->getIsRequired() === false) {
            return;
        }

        $form = $event->getForm();

        if ($form->isRoot() === false) {
            return;
        }

        if ($multiFactorAuthValidationTransfer->getStatus() === MultiFactorAuthConstants::CODE_UNVERIFIED || $multiFactorAuthValidationTransfer->getStatus() === null) {
            $form->addError(new FormError(
                $this->translator->trans(static::ERROR_MESSAGE_ACCESS_DENIED),
            ));
        }

        if ($multiFactorAuthValidationTransfer->getStatus() === MultiFactorAuthConstants::CODE_BLOCKED) {
            $event->stopPropagation();
        }
    }
}
