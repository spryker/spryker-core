<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Subscriber;

use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;
use Spryker\Shared\MultiFactorAuth\MultiFactorAuthConstants;
use Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface;
use Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface;
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
    protected const ERROR_MESSAGE_ACCESS_DENIED = 'Access is strictly restricted until multi-factor authentication verification is successfully completed. Please ensure that JavaScript is enabled in your browser, refresh the page, and try again. If the problem persists, you may need to complete the multi-factor authentication process again.';

    /**
     * @var string
     */
    protected const ERROR_ACCESS_DENIED_CAUSE = 'access_denied';

    /**
     * @param \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface $facade
     * @param \Spryker\Zed\MultiFactorAuth\Dependency\Facade\MultiFactorAuthToUserFacadeInterface $userFacade
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     */
    public function __construct(
        protected MultiFactorAuthFacadeInterface $facade,
        protected MultiFactorAuthToUserFacadeInterface $userFacade,
        protected TranslatorInterface $translator
    ) {
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => ['onPreSubmit', 100],
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function onPreSubmit(FormEvent $event): void
    {
        if ($this->userFacade->hasCurrentUser() === false) {
            return;
        }

        $multiFactorAuthValidationRequestTransfer = (new MultiFactorAuthValidationRequestTransfer())->setUser($this->userFacade->getCurrentUser());
        $multiFactorAuthValidationResponseTransfer = $this->facade->validateUserMultiFactorAuthStatus($multiFactorAuthValidationRequestTransfer);

        if ($multiFactorAuthValidationResponseTransfer->getIsRequired() === false) {
            return;
        }

        $form = $event->getForm();

        if ($form->isRoot() === false) {
            return;
        }

        $this->removeExtraFields($event);

        if ($this->assertCodeIsNotVerified($multiFactorAuthValidationResponseTransfer)) {
            $form->addError(new FormError(
                $this->translator->trans(static::ERROR_MESSAGE_ACCESS_DENIED),
                null,
                [],
                null,
                static::ERROR_ACCESS_DENIED_CAUSE,
            ));

            $event->stopPropagation();
        }

        if ($this->assertCodeIsBlocked($multiFactorAuthValidationResponseTransfer)) {
            $form->addError(new FormError(''));

            $event->stopPropagation();
        }
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    protected function removeExtraFields(FormEvent $event): void
    {
        $formFields = array_keys($event->getForm()->all());
        $data = $event->getData();

        $filteredData = array_intersect_key($data, array_flip($formFields));
        $event->setData($filteredData);
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer $multiFactorAuthValidationResponseTransfer
     *
     * @return bool
     */
    protected function assertCodeIsNotVerified(MultiFactorAuthValidationResponseTransfer $multiFactorAuthValidationResponseTransfer): bool
    {
        return $multiFactorAuthValidationResponseTransfer->getStatus() === MultiFactorAuthConstants::CODE_UNVERIFIED || $multiFactorAuthValidationResponseTransfer->getStatus() === null;
    }

    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer $multiFactorAuthValidationResponseTransfer
     *
     * @return bool
     */
    protected function assertCodeIsBlocked(MultiFactorAuthValidationResponseTransfer $multiFactorAuthValidationResponseTransfer): bool
    {
        return $multiFactorAuthValidationResponseTransfer->getStatus() === MultiFactorAuthConstants::CODE_BLOCKED;
    }
}
