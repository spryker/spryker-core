<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Form\DoubleSubmitProtection\Subscriber;

use Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\StorageInterface;
use Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Translation\TranslatorInterface;

class FormEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var \Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface
     */
    protected $tokenGenerator;

    /**
     * @var \Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\StorageInterface
     */
    protected $tokenStorage;

    /**
     * @var string
     */
    protected $errorMessage;

    /**
     * @var string
     */
    protected $fieldName;

    /**
     * @var \Symfony\Component\Translation\TranslatorInterface|null
     */
    protected $translator;

    /**
     * @var string|null
     */
    protected $translationDomain;

    /**
     * @var array
     */
    protected $translationOptions = [];

    /**
     * @param \Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface $generator
     * @param \Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\StorageInterface $storage
     * @param string $fieldName
     * @param string $errorMessage
     * @param \Symfony\Component\Translation\TranslatorInterface|null $translator
     * @param string|null $translationDomain
     */
    public function __construct(
        TokenGeneratorInterface $generator,
        StorageInterface $storage,
        string $fieldName,
        string $errorMessage,
        ?TranslatorInterface $translator = null,
        ?string $translationDomain = null
    ) {
        $this->tokenGenerator = $generator;
        $this->tokenStorage = $storage;
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
        $this->fieldName = $fieldName;
        $this->errorMessage = $errorMessage;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'validateToken',
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function validateToken(FormEvent $event): void
    {
        $form = $event->getForm();

        if (!$form->isRoot()) {
            return;
        }

        $data = $event->getData();
        $formName = $form->getName() ?: get_class($form->getConfig()->getType()->getInnerType());

        if (!is_array($data) || !$this->isTokenValid($data, $formName)) {
            $errorMessage = $this->getTranslatedErrorMessage();
            $form->addError(new FormError($errorMessage));
        }

        $this->tokenStorage->deleteToken($formName);

        if (is_array($data)) {
            unset($data[$this->fieldName]);
        }

        $event->setData($data);
    }

    /**
     * @param array $translationOptions
     *
     * @return $this
     */
    public function setTranslationOptions(array $translationOptions)
    {
        $this->translationOptions = $translationOptions;

        return $this;
    }

    /**
     * @param array $data
     * @param string $formName
     *
     * @return bool
     */
    protected function isTokenValid(array $data, string $formName): bool
    {
        if (!isset($data[$this->fieldName])) {
            return false;
        }

        $givenToken = $data[$this->fieldName];
        $expectedToken = $this->tokenStorage->getToken($formName);

        return $expectedToken !== null &&
            $this->tokenGenerator->checkTokenEquals($expectedToken, $givenToken);
    }

    /**
     * @return string
     */
    protected function getTranslatedErrorMessage(): string
    {
        $errorMessage = $this->errorMessage;
        if ($this->translator !== null) {
            $errorMessage = $this->translator->trans(
                $this->errorMessage,
                $this->translationOptions,
                $this->translationDomain
            );
        }

        return $errorMessage;
    }
}
