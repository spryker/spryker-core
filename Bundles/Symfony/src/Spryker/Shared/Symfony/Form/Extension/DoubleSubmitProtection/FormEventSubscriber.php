<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection;

use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\StorageInterface;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class FormEventSubscriber implements EventSubscriberInterface
{
    public const DEFAULT_ERROR_MESSAGE = 'This form has been already submitted.';
    public const DEFAULT_TOKEN_FIELD_NAME = '_requestToken';

    /**
     * @var \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface
     */
    protected $tokenGenerator;

    /**
     * @var \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\StorageInterface
     */
    protected $tokenStorage;

    /**
     * @var string
     */
    protected $errorMessage = self::DEFAULT_ERROR_MESSAGE;

    /**
     * @var string
     */
    protected $fieldName = self::DEFAULT_TOKEN_FIELD_NAME;

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
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'validateToken',
        ];
    }

    /**
     * @param \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface $generator
     * @param \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\StorageInterface $storage
     * @param \Symfony\Component\Translation\TranslatorInterface|null $translator
     * @param string|null $translationDomain
     */
    public function __construct(
        TokenGeneratorInterface $generator,
        StorageInterface $storage,
        $translator = null,
        $translationDomain = null
    ) {
        $this->tokenGenerator = $generator;
        $this->tokenStorage = $storage;
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return void
     */
    public function validateToken(FormEvent $event)
    {
        $form = $event->getForm();

        if (!$form->isRoot()) {
            return;
        }

        $data = $event->getData();
        $formName = $form->getName() ?: get_class($form->getConfig()->getType()->getInnerType());

        if (!$this->isTokenValid($data, $formName)) {
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
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     *
     * @return $this
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @param string $fieldName
     *
     * @return $this
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;

        return $this;
    }

    /**
     * @return array
     */
    public function getTranslationOptions()
    {
        return $this->translationOptions;
    }

    /**
     * @param array $translationOptions
     *
     * @return $this
     */
    public function setTranslationOptions($translationOptions)
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
    protected function isTokenValid($data, $formName)
    {
        $givenToken = $data[$this->fieldName];
        $expectedToken = $this->tokenStorage->getToken($formName);

        return !empty($expectedToken) &&
            !empty($givenToken) &&
            $this->tokenGenerator->checkTokenEquals($expectedToken, $givenToken);
    }

    /**
     * @return string
     */
    protected function getTranslatedErrorMessage()
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
